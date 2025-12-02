<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // ===================== LISTA DE PEDIDOS (ADMIN) =====================
    public function index(Request $request)
    {
        $query = Pedido::with(['cliente', 'vendedor', 'estado'])
            ->withCount('detalles');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');

            $query->where(function ($q2) use ($busqueda) {
                $q2->whereHas('cliente', function ($qc) use ($busqueda) {
                        $qc->where('nombres', 'like', '%' . $busqueda . '%')
                           ->orWhere('apellidos', 'like', '%' . $busqueda . '%');
                    })
                    ->orWhereHas('vendedor', function ($qv) use ($busqueda) {
                        $qv->where('nombre', 'like', '%' . $busqueda . '%');
                    });
            });
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->input('estado_id'));
        }

        $pedidos = $query->orderBy('fecha', 'desc')->get();
        $estados = EstadoPedido::orderBy('nombre')->get();

        return view('pedidos.index', compact('pedidos', 'estados'));
    }

    // ===================== DETALLE DE UN PEDIDO (ADMIN) =====================
    public function show(Pedido $pedido)
    {
        $pedido->load([
            'cliente',
            'vendedor',
            'estado',
            'direccionEnvio',
            'detalles.producto',
        ]);

        $detalles = $pedido->detalles->map(function ($detalle) {
            $subtotal   = $detalle->cantidad * $detalle->precio_unitario;
            $descuento  = $detalle->descuento ?? 0;
            $base       = $subtotal - $descuento;
            $ivaPorc    = $detalle->iva_porcentaje ?? 0;
            $ivaMonto   = $base * $ivaPorc / 100;
            $totalLinea = $base + $ivaMonto;

            $detalle->subtotal_calculado = $subtotal;
            $detalle->iva_monto          = $ivaMonto;
            $detalle->total_linea        = $totalLinea;

            return $detalle;
        });

        $totales = [
            'subtotal'  => $detalles->sum('subtotal_calculado'),
            'descuento' => $detalles->sum('descuento'),
            'iva'       => $detalles->sum('iva_monto'),
            'total'     => $detalles->sum('total_linea'),
        ];

        return view('pedidos.show', [
            'pedido'   => $pedido,
            'detalles' => $detalles,
            'totales'  => $totales,
        ]);
    }

    // ===================== CARRITO / PEDIDO DEL CLIENTE =====================

    // Ver resumen completo del carrito
    public function verCarrito()
    {
        $carrito = session()->get('carrito', []);
        $total   = 0;

        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('pedidos.carrito', compact('carrito', 'total'));
    }

    // Añadir producto al carrito (desde catálogo)
    public function carritoAgregar(Request $request, Producto $producto)
    {
        $cantidad = max(1, (int) $request->input('cantidad', 1));

        // Validar existencia
        if ($producto->existencia < $cantidad) {
            return back()->with('error', 'No hay existencia suficiente de este producto.');
        }

        $carrito = session()->get('carrito', []);
        $id      = $producto->producto_id;

        if (isset($carrito[$id])) {
            // Si ya está en el carrito, sumamos cantidades
            $nuevaCantidad = $carrito[$id]['cantidad'] + $cantidad;

            if ($nuevaCantidad > $producto->existencia) {
                return back()->with('error', 'No puedes añadir más piezas de las que hay en existencia.');
            }

            $carrito[$id]['cantidad'] = $nuevaCantidad;
        } else {
            // Nuevo producto en el carrito
            $carrito[$id] = [
                'producto_id' => $producto->producto_id,
                'nombre'      => $producto->nombre,
                'sku'         => $producto->sku,
                // precio unitario (sin total aquí)
                'precio'      => (float) $producto->precio_venta,
                'cantidad'    => $cantidad,
            ];
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto añadido al pedido.');
    }

    // Actualizar cantidad de un producto en el carrito
    public function carritoActualizar(Request $request, Producto $producto)
    {
        $carrito = session()->get('carrito', []);
        $id      = $producto->producto_id;

        if (!isset($carrito[$id])) {
            return back()->with('error', 'Ese producto no está en tu pedido.');
        }

        $cantidad = (int) $request->input('cantidad', 1);

        if ($cantidad <= 0) {
            unset($carrito[$id]);
        } else {
            if ($cantidad > $producto->existencia) {
                return back()->with('error', 'No hay existencia suficiente de este producto.');
            }
            $carrito[$id]['cantidad'] = $cantidad;
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Tu pedido fue actualizado.');
    }

    // Eliminar un producto del carrito
    public function carritoEliminar(Producto $producto)
    {
        $carrito = session()->get('carrito', []);
        $id      = $producto->producto_id;

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            session()->put('carrito', $carrito);
        }

        return back()->with('success', 'Producto eliminado del pedido.');
    }

    // Vaciar por completo el carrito
    public function carritoVaciar()
    {
        session()->forget('carrito');

        return back()->with('success', 'Tu pedido fue cancelado.');
    }

    // Confirmar pedido: crea registros y RESTA existencia
    public function carritoConfirmar(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return back()->with('error', 'Tu pedido está vacío.');
        }

        $user = auth()->user();

        // Ajusta esta parte si relacionas cliente de otra forma
        $cliente = Cliente::where('email', $user->email)->first();

        if (!$cliente) {
            return back()->with('error', 'No se encontró un cliente asociado a tu usuario. Contacta al administrador.');
        }

        try {
            DB::beginTransaction();

            // Calcular total del pedido (precio unitario × cantidad)
            $total = 0;
            foreach ($carrito as $item) {
                $total += $item['precio'] * $item['cantidad'];
            }

            // Crear registro en PEDIDOS
            $pedido = Pedido::create([
                'cliente_id'         => $cliente->cliente_id,
                'vendedor_id'        => null,
                'estado_id'          => 1,   // 1 = Pendiente (ajusta si usas otro catálogo)
                'direccion_envio_id' => null,
                'total'              => $total,
            ]);

            // Crear DETALLE y actualizar existencia
            $renglon = 1;

            foreach ($carrito as $item) {
                // Bloqueo de fila para evitar problemas si hubiera concurrencia
                $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);

                if ($producto->existencia < $item['cantidad']) {
                    throw new \Exception("No hay existencia suficiente de {$producto->nombre}.");
                }

                // Insert en tabla detalle_pedido
                DB::table('detalle_pedido')->insert([
                    'pedido_id'       => $pedido->pedido_id,
                    'renglon'         => $renglon++,
                    'producto_id'     => $producto->producto_id,
                    'cantidad'        => $item['cantidad'],
                    'precio_unitario' => $item['precio'],
                    'descuento'       => 0,
                    'iva_porcentaje'  => 0,
                ]);

                // RESTAR existencia
                $producto->existencia -= $item['cantidad'];
                $producto->save();
            }

            DB::commit();

            // Vaciar carrito
            session()->forget('carrito');

            return redirect()->route('catalogo.index')
                ->with('success', "Tu pedido #{$pedido->pedido_id} fue registrado correctamente.");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'No se pudo registrar tu pedido: ' . $e->getMessage());
        }
    }
    // ===================== HISTORIAL DEL CLIENTE =====================

    public function historialCliente()
    {
        $user = auth()->user();

        $cliente = Cliente::where('email', $user->email)->first();

        if (!$cliente) {
            return redirect()->route('catalogo.index')
                ->with('error', 'No se encontró un cliente asociado a tu usuario.');
        }

        $pedidos = Pedido::with('estado')
            ->where('cliente_id', $cliente->cliente_id)
            ->orderBy('fecha', 'desc')
            ->get();

        return view('cliente.pedidos.index', compact('pedidos'));
    }

    public function detalleCliente(Pedido $pedido)
    {
        $user = auth()->user();
        $cliente = Cliente::where('email', $user->email)->first();

        if (!$cliente || $pedido->cliente_id !== $cliente->cliente_id) {
            abort(403);
        }

        $pedido->load([
            'estado',
            'detalles.producto',
        ]);

        // Reutilizamos la lógica de totales (simplificada)
        $detalles = $pedido->detalles->map(function ($detalle) {
            $subtotal = $detalle->cantidad * $detalle->precio_unitario;
            $detalle->subtotal_calculado = $subtotal;
            return $detalle;
        });

        $totales = [
            'total' => $detalles->sum('subtotal_calculado'),
        ];

        return view('cliente.pedidos.show', [
            'pedido'   => $pedido,
            'detalles' => $detalles,
            'totales'  => $totales,
        ]);
    }
    public function cancelarCliente(Pedido $pedido)
    {
        $user = auth()->user();
        $cliente = Cliente::where('email', $user->email)->first();

        if (!$cliente || $pedido->cliente_id !== $cliente->cliente_id) {
            abort(403);
        }

        $pedido->load('estado', 'detalles');

        $estadoCanceladoId = $this->getEstadoCanceladoId();
        if (!$estadoCanceladoId) {
            return back()->with('error', 'No está configurado el estado "Cancelado" en la tabla estados de pedido.');
        }

        // Ya cancelado
        if ($pedido->estado_id == $estadoCanceladoId) {
            return back()->with('error', 'Este pedido ya está cancelado.');
        }

        // Opcional: solo permitir cancelar si está pendiente
        if ($pedido->estado && strtolower($pedido->estado->nombre) !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar pedidos en estado "Pendiente".');
        }

        try {
            $this->cancelarPedidoYRevertirStock($pedido, $estadoCanceladoId);

            return back()->with('success', 'Tu pedido fue cancelado y el stock fue restablecido.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo cancelar el pedido: ' . $e->getMessage());
        }
    }
    public function cancelarAdmin(Pedido $pedido)
    {
        $pedido->load('estado', 'detalles');

        $estadoCanceladoId = $this->getEstadoCanceladoId();
        if (!$estadoCanceladoId) {
            return back()->with('error', 'No está configurado el estado "Cancelado" en la tabla estados de pedido.');
        }

        if ($pedido->estado_id == $estadoCanceladoId) {
            return back()->with('error', 'Este pedido ya está cancelado.');
        }

        // Si quieres bloquear pedidos pagados/enviados:
        if ($pedido->estado && in_array(strtolower($pedido->estado->nombre), ['pagado', 'enviado', 'completado'])) {
            return back()->with('error', 'No se puede cancelar un pedido en estado ' . $pedido->estado->nombre . '.');
        }

        try {
            $this->cancelarPedidoYRevertirStock($pedido, $estadoCanceladoId);

            return back()->with('success', 'El pedido fue cancelado y el stock fue restablecido.');
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo cancelar el pedido: ' . $e->getMessage());
        }
    }
        // ===================== MÉTODOS AUXILIARES =====================

    /**
     * Obtiene el ID del estado "Cancelado" buscando por nombre (case-insensitive).
     */
    protected function getEstadoCanceladoId()
    {
        $estado = EstadoPedido::whereRaw('LOWER(nombre) = ?', ['cancelado'])->first();
        return $estado ? $estado->estado_id : null;
    }

    /**
     * Marca un pedido como cancelado y regresa el stock de todos sus productos.
     */
    protected function cancelarPedidoYRevertirStock(Pedido $pedido, int $estadoCanceladoId): void
    {
        DB::transaction(function () use ($pedido, $estadoCanceladoId) {
            // Asegurarnos de tener los detalles
            $pedido->loadMissing('detalles');

            foreach ($pedido->detalles as $detalle) {
                $producto = Producto::lockForUpdate()->find($detalle->producto_id);
                if ($producto) {
                    $producto->existencia += $detalle->cantidad;
                    $producto->save();
                }
            }

            $pedido->estado_id = $estadoCanceladoId;
            $pedido->save();
        });
    }
}
