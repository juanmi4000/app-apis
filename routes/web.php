<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramController;


Route::get('/', [InstagramController::class, 'index'])->name('instagram.index');
Route::post('/subir-imagen', [InstagramController::class, 'uploadImage'])->name('instagram.upload');
