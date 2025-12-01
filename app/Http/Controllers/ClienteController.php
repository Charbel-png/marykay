<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    // LISTAR CLIENTES
    public function index(Request $request)
    {
        $query = Cliente::withCount('pedidos');

        if ($request->filled('q')) {
            $busqueda = $request->input('q');

            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', '%' . $busqueda . '%')
                  ->orWhere('apellidos', 'like', '%' . $busqueda . '%')
                  ->orWhere('email', 'like', '%' . $busqueda . '%')
                  ->orWhere('telefono', 'like', '%' . $busqueda . '%')
                  ->orWhere('fecha_reg', 'like', '%' . $busqueda . '%');
            });
        }

        $clientes = $query->orderBy('nombres')->get();

        return view('clientes.index', compact('clientes'));
    }

    // FORMULARIO CREAR
    public function create()
    {
        return view('clientes.create');
    }

    // GUARDAR NUEVO CLIENTE
    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'nullable|email|max:150',
            'telefono'  => 'nullable|string|max:20',
        ], [
            'nombres.required'   => 'El nombre del cliente es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.email'        => 'El correo electrónico no tiene un formato válido.',
        ]);

        $datos['fecha_reg'] = now();

        Cliente::create($datos);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    // FORMULARIO EDITAR
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    // ACTUALIZAR CLIENTE
    public function update(Request $request, Cliente $cliente)
    {
        $datos = $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'nullable|email|max:150',
            'telefono'  => 'nullable|string|max:20',
        ], [
            'nombres.required'   => 'El nombre del cliente es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.email'        => 'El correo electrónico no tiene un formato válido.',
        ]);

        $cliente->update($datos);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    // ELIMINAR CLIENTE
    public function destroy(Cliente $cliente)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        try {
            $cliente->delete();
            return redirect()->route('clientes.index')
                ->with('success', 'Cliente eliminado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->route('clientes.index')
                ->with('error', 'No se puede eliminar el cliente porque tiene información relacionada.');
        }
    }
}
