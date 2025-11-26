<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogoController;

Route::get('/catalogo', [CatalogoController::class, 'index'])
    ->name('catalogo.index');

Route::get('/', function () {
    return redirect()->route('catalogo.index');
});
