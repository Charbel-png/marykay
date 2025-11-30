<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\PedidoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin',[AdminController::class,'index'])
    ->name('admin.dashboard');

// Productos / catálogo
Route::get('/productos', [ProductoController::class, 'index'])
    ->name('productos.index');

Route::get('/catalogo', [ProductoController::class, 'index'])
    ->name('catalogo.index');

// Clientes CRUD completo
Route::resource('clientes', ClienteController::class)->except(['show']);

// Vendedores
Route::get('/vendedores', [VendedorController::class, 'index'])
    ->name('vendedores.index');

// Pedidos
Route::get('/pedidos', [PedidoController::class, 'index'])
    ->name('pedidos.index');
