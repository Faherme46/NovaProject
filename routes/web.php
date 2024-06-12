<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PropiedadesController;
use Illuminate\Support\Facades\Route;

//rutas de redireccion
Route::get('/', function () { return view('welcome'); })->name('home');
Route::get('/registro', function () { return view('registro'); });
Route::get('/login', function () { return view('login'); });
Route::get('/crearUsuarios', function () { return view('crearUsuario'); });
Route::get('/votos', function () { return view('votos') ;});
Route::get('/preguntas', function () { return view('preguntas'); });
Route::get('/resultados', function () { return view('resultados') ;});


//rutas para las asambleas
Route::get('admin/asambleas', [AsambleaController::class, 'index'])->name('admin.asambleas');
Route::resource('asambleas', AsambleaController::class);
Route::delete('/session/destroy', [SessionController::class, 'destroyAll'])->name('session.destroy');
Route::post('admin/inicio', [AsambleaController::class, 'iniciarAsamblea'])->name('asambleas.inicia');
Route::post('admin/termina', [AsambleaController::class, 'terminarAsamblea'])->name('asambleas.termina');

Route::get('/files/export', [FileController::class, 'export'])->name('files.export');

//rutas de las propiedades
Route::get('/propiedades/index', [PropiedadesController::class, 'index'])->name('propiedades.index');
Route::post('/propiedades/import', [PropiedadesController::class, 'import'])->name('propiedades.import');
Route::delete('propiedades/destroy', [PropiedadesController::class, 'destroyAll'])->name('propiedades.destroyAll');
