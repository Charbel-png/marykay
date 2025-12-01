<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\PedidoController;

// 🔹 Página raíz
Route::get('/', function () {
    // Si YA está autenticado, mándalo según su rol
    if (Auth::check()) {
        $role = Auth::user()->role;

        if (in_array($role, ['admin', 'operador'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'cliente') {
            return redirect()->route('catalogo.index');
        }
    }

    // Si NO está autenticado, mándalo al login
    return redirect()->route('login');
});

// 🔹 Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// 🔹 ADMIN + OPERADOR
Route::middleware(['auth', 'role:admin,operador'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('productos', ProductoController::class)->except(['show']);

    Route::resource('clientes', ClienteController::class)->except(['show']);

    Route::resource('vendedores', VendedorController::class)
        ->parameters(['vendedores' => 'vendedor'])
        ->except(['show']);

    Route::resource('pedidos', PedidoController::class)->only(['index', 'show']);
});

// 🔹 CLIENTE
Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('/catalogo', [ProductoController::class, 'catalogoCliente'])
        ->name('catalogo.index');

    Route::post('/catalogo/agregar/{producto}', [PedidoController::class, 'carritoAgregar'])
        ->name('catalogo.agregar');

    Route::post('/mi-pedido/confirmar', [PedidoController::class, 'carritoConfirmar'])
        ->name('catalogo.confirmar');
});
