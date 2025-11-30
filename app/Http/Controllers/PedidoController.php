<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\EstadoPedido;
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
}
