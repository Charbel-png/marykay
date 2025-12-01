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

    use Illuminate\Support\Facades\Auth;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if (in_array($user->role, ['admin', 'operador'])) {
                return redirect()->route('admin.dashboard');
            }

            if ($user->role === 'cliente') {
                return redirect()->route('catalogo.index');
            }

            // Si por alguna razón tiene otro rol raro:
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Tu rol no tiene un destino configurado.']);
        }

        return back()
            ->withErrors(['email' => 'Credenciales incorrectas.'])
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
