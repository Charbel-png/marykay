<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // LISTAR PRODUCTOS
    public function index(Request $request)
    {
        $query = Producto::with('categoria');

        // Búsqueda por nombre o SKU
        if ($request->filled('q')) {
            $busqueda = $request->input('q');

            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', '%' . $busqueda . '%')
                  ->orWhere('sku', 'like', '%' . $busqueda . '%');
            });
        }

        // (Opcional) Filtrar por categoría
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->input('categoria_id'));
        }

        $productos  = $query->orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.index', compact('productos', 'categorias'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.create', compact('categorias'));
    }

    // GUARDAR NUEVO PRODUCTO
    public function store(Request $request)
    {
        $datos = $request->validate([
            'sku'          => 'required|string|max:50|unique:productos,sku',
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'precio_lista' => 'required|numeric|min:0',
            'unidad'       => 'required|string|max:20',
        ], [
            'sku.required'          => 'El SKU es obligatorio.',
            'sku.unique'            => 'Ya existe un producto con este SKU.',
            'nombre.required'       => 'El nombre del producto es obligatorio.',
            'categoria_id.required' => 'Debes seleccionar una categoría.',
            'categoria_id.exists'   => 'La categoría seleccionada no es válida.',
            'precio_lista.required' => 'El precio de lista es obligatorio.',
            'precio_lista.numeric'  => 'El precio debe ser numérico.',
        ]);

        Producto::create($datos);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto registrado correctamente.');
    }

    // FORMULARIO EDITAR
    public function edit(Producto $producto)
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias'));
    }

    // ACTUALIZAR PRODUCTO
    public function update(Request $request, Producto $producto)
    {
        $datos = $request->validate([
            'sku'          => 'required|string|max:50|unique:productos,sku,' . $producto->producto_id . ',producto_id',
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'precio_lista' => 'required|numeric|min:0',
            'unidad'       => 'required|string|max:20',
        ]);

        $producto->update($datos);

        return redirect()
            ->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // ELIMINAR PRODUCTO
    public function destroy(Producto $producto)
    {
        try {
            $producto->delete();

            return redirect()
                ->route('productos.index')
                ->with('success', 'Producto eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque tiene información relacionada (por ejemplo, pedidos).');
        }
    }
}
