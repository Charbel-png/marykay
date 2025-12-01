<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    // LISTAR VENDEDORES
    public function index(Request $request)
    {
        $query = Vendedor::with('supervisor')->withCount('pedidos');

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

    // FORMULARIO CREAR
    public function create()
    {
        if (auth()->user()->role !== 'admin') 
            {
                abort(403);
            }
        $supervisores = Vendedor::orderBy('nombre')->get();

        return view('vendedores.create', compact('supervisores'));
    }

    // GUARDAR NUEVO VENDEDOR
    public function store(Request $request)
    {
        if (auth()->user()->role !== 'admin') 
            {
                abort(403);
            }
        $datos = $request->validate([
            'nombre'        => 'required|string|max:150',
            'email'         => 'nullable|email|max:150',
            'telefono'      => 'nullable|string|max:20',
            'estatus'       => 'required|in:activo,inactivo',
            'supervisor_id' => 'nullable|exists:vendedores,vendedor_id',
        ], [
            'nombre.required'  => 'El nombre es obligatorio.',
            'email.email'      => 'El correo electrónico no tiene un formato válido.',
            'estatus.required' => 'Debes seleccionar un estatus.',
        ]);

        $datos['fecha_alta'] = now();

        Vendedor::create($datos);

        return redirect()
            ->route('vendedores.index')
            ->with('success', 'Vendedor registrado correctamente.');
    }

    // FORMULARIO EDITAR
    public function edit(Vendedor $vendedor)
    {
        if (auth()->user()->role !== 'admin') 
            {
                abort(403);
            }
        $supervisores = Vendedor::where('vendedor_id', '!=', $vendedor->vendedor_id)
            ->orderBy('nombre')
            ->get();

        return view('vendedores.edit', compact('vendedor', 'supervisores'));
    }

    // ACTUALIZAR VENDEDOR
    public function update(Request $request, Vendedor $vendedor)
    {
        if (auth()->user()->role !== 'admin') 
            {
                abort(403);
            }
        $datos = $request->validate([
            'nombre'        => 'required|string|max:150',
            'email'         => 'nullable|email|max:150',
            'telefono'      => 'nullable|string|max:20',
            'estatus'       => 'required|in:activo,inactivo',
            'supervisor_id' => 'nullable|exists:vendedores,vendedor_id',
        ]);

        $vendedor->update($datos);

        return redirect()
            ->route('vendedores.index')
            ->with('success', 'Vendedor actualizado correctamente.');
    }

    // ELIMINAR VENDEDOR
    public function destroy(Vendedor $vendedor)
    {
        if (auth()->user()->role !== 'admin') 
            {
                abort(403);
            }
        try {
            $vendedor->delete();

            return redirect()
                ->route('vendedores.index')
                ->with('success', 'Vendedor eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()
                ->route('vendedores.index')
                ->with('error', 'No se puede eliminar el vendedor porque tiene información relacionada (por ejemplo, pedidos).');
        }
    }
}
