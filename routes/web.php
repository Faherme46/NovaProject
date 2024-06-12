<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\RegistroController;
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

//rutas de las predios
Route::get('/predios/index', [PrediosController::class, 'index'])->name('predios.index');
Route::post('/predios/import', [PrediosController::class, 'import'])->name('predios.import');
Route::delete('predios/destroy', [PrediosController::class, 'destroyAll'])->name('predios.destroyAll');

//rutas para registro
Route::get('/registro/find', [PersonasController::class, 'find'])->name('personas.find');
Route::post('/registro/asignaP', [RegistroController::class, 'asignaPredios'])->name('registro.asignaPredios');

