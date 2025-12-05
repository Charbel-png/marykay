<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Mostrar formulario de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Procesar login
    public function login(Request $request)
    {
        $credenciales = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // intenta iniciar sesión
        if (! Auth::attempt($credenciales, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Las credenciales no son válidas.'])
                ->onlyInput('email');
        }

        // regenerar sesión
        $request->session()->regenerate();

        // Redirección según rol
        $role = Auth::user()->role;

        if (in_array($role, ['admin', 'operador'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'cliente') {
            return redirect()->route('catalogo.index');
        }

        // por si acaso
        return redirect()->intended('/');
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
