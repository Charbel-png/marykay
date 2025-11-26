<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Categoria;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        if ($request->filled('q')) {
            $busqueda = $request->q;

            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                ->orWhere('sku', 'like', "%{$busqueda}%")
                ->orWhere('descripcion', 'like', "%{$busqueda}%");
            });
        }

        $productos = $query
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        $categorias = Categoria::orderBy('nombre')->get();

        return view('catalogo.index', compact('productos', 'categorias'));
    }
}