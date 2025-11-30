<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $query = Cliente::with('direcciones')->withCount('pedidos');

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

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        // 🔒 VALIDACIONES
        $datos = $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'nullable|email|max:150',
            'telefono'  => 'nullable|string|max:20',
        ], [
            // MENSAJES PERSONALIZADOS (opcional)
            'nombres.required'   => 'El nombre del cliente es obligatorio.',
            'apellidos.required' => 'Los apellidos son obligatorios.',
            'email.email'        => 'El correo electrónico no tiene un formato válido.',
        ]);

        // Si llega aquí, la validación pasó ✅
        $datos['fecha_reg'] = now();

        Cliente::create($datos);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }
}
