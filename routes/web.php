<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\TwitterController;

// Route::get('/', [InstagramController::class, 'index'])->name('instagram.index');
Route::get('/', function () {
    return view('index');
});
Route::post('/subir-imagen', [InstagramController::class, 'uploadImage'])->name('subir_imagen');
Route::get('/instagram', [InstagramController::class, 'index'])->name('instagram.index');
Route::post('/instagram/programar-publicacion', [InstagramController::class, 'programarPublicacion'])->name('instagram.programarPublicacion');
Route::post('/upload/image', 'InstagramController@uploadImage')->name('upload.image');
Route::get('/subir-tweet', [TwitterController::class, 'crearTweet']);
