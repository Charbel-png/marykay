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
    if (Auth::check()) {
        $role = Auth::user()->role;

        if (in_array($role, ['admin', 'operador'])) {
            return redirect()->route('admin.dashboard');
        }

        if ($role === 'cliente') {
            return redirect()->route('catalogo.index');
        }
    }

    // No autenticado → login
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

// =======================================================
// 🔹 ADMIN + OPERADOR (panel administrativo)
// =======================================================
Route::middleware(['auth', 'role:admin,operador'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');

    // CRUD de productos
    Route::resource('productos', ProductoController::class)
        ->except(['show']);

    // CRUD de clientes
    Route::resource('clientes', ClienteController::class)
        ->except(['show']);

    // CRUD de vendedores
    Route::resource('vendedores', VendedorController::class)
        ->parameters(['vendedores' => 'vendedor'])
        ->except(['show']);

    // Pedidos (solo listado y detalle)
    Route::resource('pedidos', PedidoController::class)
        ->only(['index', 'show']);

    // Cancelar pedido desde admin (y revertir stock)
    Route::post('/pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelarAdmin'])
        ->name('pedidos.cancelar');
});

// =======================================================
// 🔹 CLIENTE (catálogo, carrito e historial de pedidos)
// =======================================================
Route::middleware(['auth', 'role:cliente'])->group(function () {

    // Catálogo de productos visible para el cliente
    Route::get('/catalogo', [ProductoController::class, 'catalogoCliente'])
        ->name('catalogo.index');

    Route::post('/catalogo/agregar/{producto}', [PedidoController::class, 'carritoAgregar'])
        ->name('catalogo.agregar');

    // Carrito / pedido actual
    Route::get('/mi-pedido', [PedidoController::class, 'verCarrito'])
        ->name('carrito.ver');

    Route::post('/mi-pedido/actualizar/{producto}', [PedidoController::class, 'carritoActualizar'])
        ->name('carrito.actualizar');

    Route::post('/mi-pedido/eliminar/{producto}', [PedidoController::class, 'carritoEliminar'])
        ->name('carrito.eliminar');

    Route::post('/mi-pedido/vaciar', [PedidoController::class, 'carritoVaciar'])
        ->name('carrito.vaciar');

    Route::post('/mi-pedido/confirmar', [PedidoController::class, 'carritoConfirmar'])
        ->name('catalogo.confirmar');

    // Historial de pedidos del cliente
    Route::get('/mis-pedidos', [PedidoController::class, 'historialCliente'])
        ->name('cliente.pedidos.index');

    Route::get('/mis-pedidos/{pedido}', [PedidoController::class, 'detalleCliente'])
        ->name('cliente.pedidos.show');

    // Cancelar pedido propio (solo pendientes)
    Route::post('/mis-pedidos/{pedido}/cancelar', [PedidoController::class, 'cancelarCliente'])
        ->name('cliente.pedidos.cancelar');
});
