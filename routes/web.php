<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\PedidoController;

// Al entrar a la página principal, mandar al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autenticación
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

// Rutas para ADMIN y OPERADOR (panel administrativo)
Route::middleware(['auth', 'role:admin,operador'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    Route::resource('productos', ProductoController::class)->except(['show']);

    Route::resource('clientes', ClienteController::class)->except(['show']);

    Route::resource('vendedores', VendedorController::class)->except(['show']);

    Route::resource('pedidos', PedidoController::class)->only(['index', 'show']);
});

// Rutas para CLIENTE (catálogo, pedidos, pagos)
Route::middleware(['auth', 'role:cliente'])->group(function () {

    Route::get('/catalogo', [ProductoController::class, 'catalogoCliente'])
        ->name('catalogo.index');

    // Añadir un producto al pedido (carrito)
    Route::post('/catalogo/agregar/{producto}', [PedidoController::class, 'carritoAgregar'])
        ->name('catalogo.agregar');

    // Ver el pedido actual
    Route::get('/mi-pedido', [PedidoController::class, 'carritoVer'])
        ->name('catalogo.carrito');

    // Confirmar pedido y guardarlo en la BD
    Route::post('/mi-pedido/confirmar', [PedidoController::class, 'carritoConfirmar'])
        ->name('catalogo.confirmar');
});
