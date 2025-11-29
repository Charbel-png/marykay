<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\EstadoPedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $query = Pedido::with(['cliente', 'vendedor', 'estado'])
            ->withCount('detalles');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');

            $query->whereHas('cliente', function ($qc) use ($busqueda) {
                $qc->where('nombres', 'like', '%' . $busqueda . '%')
                   ->orWhere('apellidos', 'like', '%' . $busqueda . '%');
            })->orWhereHas('vendedor', function ($qv) use ($busqueda) {
                $qv->where('nombre', 'like', '%' . $busqueda . '%');
            });
        }

        if ($request->filled('estado_id')) {
            $query->where('estado_id', $request->input('estado_id'));
        }

        $pedidos = $query->orderBy('fecha', 'desc')->get();
        $estados = EstadoPedido::orderBy('nombre')->get();

        return view('pedidos.index', compact('pedidos', 'estados'));
    }
}
