<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductoController;

Route::get('/', function () {
    return view('welcome'); // o tu vista de inicio
});

// Ruta que ya funciona
Route::get('/productos', [ProductoController::class, 'index'])
    ->name('productos.index');

// Hacer que /catalogo también muestre el mismo catálogo
Route::get('/catalogo', [ProductoController::class, 'index'])
    ->name('catalogo.index');
