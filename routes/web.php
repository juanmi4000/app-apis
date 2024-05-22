<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramController;

Route::get('/', [InstagramController::class, 'index'])->name('instagram.index');
Route::post('/upload', [InstagramController::class, 'uploadImage'])->name('instagram.upload');