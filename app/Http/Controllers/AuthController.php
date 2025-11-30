<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Redirección según rol
            if (in_array($user->role, ['admin', 'operador'])) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'cliente') {
                return redirect()->route('catalogo.index');
            }

            // Si tiene un rol raro, lo sacamos
            Auth::logout();

            return back()->withErrors([
                'email' => 'Tu usuario no tiene un rol válido en el sistema.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
