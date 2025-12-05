<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'precio_venta' => 'required|numeric|min:0',
            'existencia'   => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $datos['sku'] = $this->generarSku($datos['nombre']);

        // Mantener consistencia
        $datos['precio_lista'] = $datos['precio_venta'];
        $datos['unidad']       = 'pieza';             // fijo

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
            'nombre'       => 'required|string|max:150',
            'descripcion'  => 'nullable|string',
            'categoria_id' => 'required|exists:categorias,categoria_id',
            'precio_venta' => 'required|numeric|min:0',
            'existencia'   => 'required|integer|min:0',
            'imagen'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $datos['precio_lista'] = $datos['precio_venta'];
        $datos['unidad']       = 'pieza';

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
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
        $consulta = Producto::with('categoria')
            ->orderBy('nombre');

        if ($busqueda = $request->input('q')) {
            $consulta->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                ->orWhere('sku', 'like', "%{$busqueda}%");
            });
        }

        $productos = $consulta->get();

        // Carrito desde sesión
        $carrito = session()->get('carrito', []);

        // 🔢 Total correcto: precio unitario × cantidad
        $total = 0;
        foreach ($carrito as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('catalogo.index', [
            'productos' => $productos,
            'carrito'   => $carrito,
            'total'     => $total,
        ]);
    }
    protected function generarSku(string $nombre): string
{
    $prefijo = 'MK';
    $base    = strtoupper(Str::slug($nombre, '-')); // ej: BASE-MATTE-W130
    $base    = substr($base, 0, 12);                // lo recortamos por si es muy largo

    return $prefijo . '-' . $base . '-' . now()->format('His'); // MK-BASE-MATTE-123045
}

}
