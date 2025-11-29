<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('direcciones')
            ->withCount('pedidos');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', '%' . $busqueda . '%')
                  ->orWhere('apellidos', 'like', '%' . $busqueda . '%')
                  ->orWhere('email', 'like', '%' . $busqueda . '%');
            });
        }

        $clientes = $query->orderBy('nombres')->get();

        return view('clientes.index', compact('clientes'));
    }
}
