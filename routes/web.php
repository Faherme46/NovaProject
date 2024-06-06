<?php

use App\Http\Controllers\adminController;
use App\Http\Controllers\reunionController;
use App\Http\Controllers\routeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/registro',function(){
    return view('registro');
});
Route::get('/login',function(){
    return view('login');
});
Route::get('/crearUsuarios',function(){
    return view('crearUsuario');
});
Route::get('/votos',function(){
    return view('votos');
});
Route::get('/preguntas',function(){
    return view('preguntas');
});
Route::get('/resultados',function(){
    return view('resultados');
});

Route::get('admin/creaReunion',[ReunionController::class,'index']);

Route::get('/alert', function(){
    return view('welcome');
});

Route::resource('reuniones', ReunionController::class);


