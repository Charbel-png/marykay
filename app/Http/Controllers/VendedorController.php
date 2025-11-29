<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Vendedor::with('supervisor')
            ->withCount('pedidos');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', '%' . $busqueda . '%')
                  ->orWhere('email', 'like', '%' . $busqueda . '%');
            });
        }

        $vendedores = $query->orderBy('nombre')->get();

        return view('vendedores.index', compact('vendedores'));
    }
}
