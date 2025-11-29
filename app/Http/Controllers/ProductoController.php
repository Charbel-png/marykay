<?php

namespace App\Http\Controllers;

use App\Models\Producto; // Luego lo ajustamos si hace falta
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::all(); // Si falla por el modelo, luego lo cambiamos

        return view('productos.index', compact('productos'));
    }
}
