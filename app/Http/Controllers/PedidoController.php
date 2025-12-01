<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    // LISTA DE PEDIDOS
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

    // DETALLE DE UN PEDIDO
    public function show(Pedido $pedido)
    {
        // Cargamos todas las relaciones necesarias
        $pedido->load([
            'cliente',
            'vendedor',
            'estado',
            'direccionEnvio',
            'detalles.producto',
        ]);

        // Calculamos subtotales, IVA, total por renglón
        $detalles = $pedido->detalles->map(function ($detalle) {
            $subtotal  = $detalle->cantidad * $detalle->precio_unitario;
            $descuento = $detalle->descuento ?? 0;
            $base      = $subtotal - $descuento;
            $ivaPorc   = $detalle->iva_porcentaje ?? 0;
            $ivaMonto  = $base * $ivaPorc / 100;
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
    public function carritoAgregar(Request $request, Producto $producto)
    {
        $cantidad = (int) $request->input('cantidad', 1);
        if ($cantidad < 1) {
            $cantidad = 1;
        }

        $carrito = session()->get('carrito', []);

        $id = $producto->producto_id;

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad'] += $cantidad;
        } else {
            $carrito[$id] = [
                'producto_id' => $id,
                'nombre'      => $producto->nombre,
                'sku'         => $producto->sku,
                'precio'      => $producto->precio_lista,
                'cantidad'    => $cantidad,
            ];
        }

        session()->put('carrito', $carrito);

        return back()->with('success', 'Producto añadido al pedido.');
    }

    // Ver el carrito / pedido actual
    public function carritoVer()
    {
        $carrito = session()->get('carrito', []);

        $total = 0;
        foreach ($carrito as &$item) {
            $item['subtotal'] = $item['cantidad'] * $item['precio'];
            $item['iva']      = $item['subtotal'] * 0.16;
            $item['total']    = $item['subtotal'] + $item['iva'];
            $total           += $item['total'];
        }
        unset($item);

        return view('catalogo.carrito', compact('carrito', 'total'));
    }

    // Confirmar el pedido: guardar en pedidos + detalle_pedido
    public function carritoConfirmar(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->route('catalogo.index')
                ->with('error', 'Tu pedido está vacío.');
        }

        $user = Auth::user();

        // Buscamos el cliente ligando por email
        $cliente = Cliente::where('email', $user->email)->first();

        if (!$cliente) 
            {
            // Crear cliente automático a partir del usuario logueado
            $cliente = Cliente::create
            ([
                'nombres'   => $user->name,
                'apellidos' => '',
                'email'     => $user->email,
                'telefono'  => null,
                'fecha_reg' => now(),
            ]);
            }   

        DB::beginTransaction();

        try {
            $pedido = new Pedido();
            $pedido->cliente_id        = $cliente->cliente_id;
            $pedido->vendedor_id       = null;
            $pedido->estado_id         = 1; // 'Creado'
            $pedido->direccion_envio_id = null;
            $pedido->total             = 0;
            $pedido->save();

            $total   = 0;
            $renglon = 1;

            foreach ($carrito as $item) {
                $subtotal   = $item['cantidad'] * $item['precio'];
                $ivaPorc    = 16;
                $iva        = $subtotal * $ivaPorc / 100;
                $totalLinea = $subtotal + $iva;

                DB::table('detalle_pedido')->insert([
                    'pedido_id'      => $pedido->pedido_id,
                    'renglon'        => $renglon++,
                    'producto_id'    => $item['producto_id'],
                    'cantidad'       => $item['cantidad'],
                    'precio_unitario'=> $item['precio'],
                    'descuento'      => 0,
                    'iva_porcentaje' => $ivaPorc,
                ]);

                $total += $totalLinea;
            }

            $pedido->total = $total;
            $pedido->save();

            DB::commit();
            session()->forget('carrito');

            return redirect()->route('catalogo.index')
                ->with('success', 'Tu pedido #' . $pedido->pedido_id . ' fue registrado correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->route('catalogo.index')
                ->with('error', 'Ocurrió un error al registrar tu pedido. Intenta nuevamente.');
        }
    }
}
