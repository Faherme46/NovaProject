<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PropiedadesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');})->name('home');

Route::get('/registro', function () {
    return view('registro');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/crearUsuarios', function () {
    return view('crearUsuario');
});
Route::get('/votos', function () {
    return view('votos');
});
Route::get('/preguntas', function () {
    return view('preguntas');
});
Route::get('/resultados', function () {
    return view('resultados');
});

Route::get('admin/creaAsamblea', [AsambleaController::class, 'index'])->name('admin.crearAsamblea');

Route::get('/alert', function () {
    return view('welcome');
});

Route::resource('asambleas', AsambleaController::class);
Route::delete('/session',[SessionController::class,'destroyAll'])->name('session.destroy');


Route::get('/files/export', [FileController::class, 'export'])->name('files.export');

Route::get('/propiedades/index', [PropiedadesController::class, 'index'])->name('propiedades.index');
Route::post('/propiedades/import', [PropiedadesController::class, 'import'])->name('propiedades.import');
Route::delete('propiedades/destroy', [PropiedadesController::class, 'destroyAll'])->name('propiedades.destroyAll');
