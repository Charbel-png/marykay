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

// Clientes
Route::get('/clientes', [ClienteController::class, 'index'])
    ->name('clientes.index');
Route::get('/clientes/crear',[ClienteController::class,'create'])
    ->name('clientes.create');
Route:get('/clientes',[ClienteController::class,'store'])
    ->name('clientes.store');        

// Vendedores
Route::get('/vendedores', [VendedorController::class, 'index'])
    ->name('vendedores.index');

// Pedidos
Route::get('/pedidos', [PedidoController::class, 'index'])
    ->name('pedidos.index');
