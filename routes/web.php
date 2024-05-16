<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubirImagenController;


Route::get('/', function () {
    return view('welcome');
});



Route::post('/subir-imagen', [SubirImagenController::class, 'subirImagen']);
Route::get('/obtener-imagenes-instagram', [SubirImagenController::class, 'obtenerImagenesInstagram']);
Route::post('/subir-imagen', [SubirImagenController::class, 'subirImagen'])->name('subir-imagen');