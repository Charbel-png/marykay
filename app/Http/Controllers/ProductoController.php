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
            'nombre'        => 'required|string|max:150',
            'sku'           => 'required|string|max:50|unique:productos,sku',
            'descripcion'   => 'nullable|string',
            'precio_lista'  => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'categoria_id'  => 'nullable|exists:categorias,categoria_id',
            'existencia'    => 'required|integer|min:0',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nombre.required'       => 'El nombre es obligatorio.',
            'sku.required'          => 'El SKU es obligatorio.',
            'sku.unique'            => 'Este SKU ya está registrado.',
            'precio_lista.required' => 'El precio de lista es obligatorio.',
            'precio_venta.required' => 'El precio de venta es obligatorio.',
            'precio_lista.numeric'  => 'El precio de lista debe ser numérico.',
            'precio_venta.numeric'  => 'El precio de venta debe ser numérico.',
            'existencia.required'   => 'La existencia es obligatoria.',
            'existencia.integer'    => 'La existencia debe ser un número entero.',
            'imagen.image'          => 'El archivo debe ser una imagen.',
            'imagen.mimes'          => 'Solo se permiten imágenes JPG, JPEG, PNG o WEBP.',
            'imagen.max'            => 'La imagen no debe pesar más de 2 MB.',
        ]);

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('productos', 'public');
            $datos['imagen'] = $ruta;
        }

        Producto::create($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
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
            'nombre'        => 'required|string|max:150',
            'sku'           => 'required|string|max:50|unique:productos,sku,' . $producto->producto_id . ',producto_id',
            'descripcion'   => 'nullable|string',
            'precio_lista'  => 'required|numeric|min:0',
            'precio_venta'  => 'required|numeric|min:0',
            'categoria_id'  => 'nullable|exists:categorias,categoria_id',
            'existencia'    => 'required|integer|min:0',
            'imagen'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $ruta = $request->file('imagen')->store('productos', 'public');
            $datos['imagen'] = $ruta;
        }

        $producto->update($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // ELIMINAR PRODUCTO
    public function destroy(Producto $producto)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        try {
            $producto->delete();
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque tiene información relacionada (por ejemplo, pedidos).');
        }
    }

    public function catalogoCliente(Request $request)
    {
        $query = Producto::with('categoria');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');
            $query->where(function ($q2) use ($busqueda) {
                $q2->where('nombre', 'like', '%' . $busqueda . '%')
                ->orWhere('sku', 'like', '%' . $busqueda . '%');
            });
        }

        $productos = $query->orderBy('nombre')->get();

        // Carrito desde la sesión
        $carrito = session()->get('carrito', []);
        $total   = 0;

        foreach ($carrito as &$item) {
            $item['subtotal'] = $item['cantidad'] * $item['precio'];
            $item['iva']      = $item['subtotal'] * 0.16;
            $item['total']    = $item['subtotal'] + $item['iva'];
            $total           += $item['total'];
        }
        unset($item);

        return view('catalogo.index', compact('productos', 'carrito', 'total'));
    }
}
