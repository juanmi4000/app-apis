<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramController;


// Route::get('/', [InstagramController::class, 'index'])->name('instagram.index');
Route::get('/', function () {
    return view('index');
});
Route::post('/subir-imagen', [InstagramController::class, 'uploadImage'])->name('subir_imagen');