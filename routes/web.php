<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubirImagenController;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/instagram', 'InstagramController@index');
Route::post('/instagram', 'InstagramController@upload');
