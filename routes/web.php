<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\AsistentesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


//rutas de redireccion
Route::get('/', function () {  return view('welcome'); })->name('home');
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

Route::post('/predios/import', [PrediosController::class, 'import'])->name('predios.import');
Route::delete('predios/destroy', [PrediosController::class, 'destroyAll'])->name('predios.destroyAll');

//rutas para registro
Route::get('/asistentes', [AsistentesController::class, 'index'])->name('asistentes.index');
Route::get('/asistentesAssigned', [AsistentesController::class, 'indexAssigned'])->name('asistentes.index2');
Route::get('/asistentes/buscar', [AsistentesController::class, 'buscar'])->name('asistentes.buscar');
Route::get('/asistentes/anadir', [AsistentesController::class, 'anadirPredio'])->name('asistentes.addPredio');
Route::get('/asistentes/limpiar', [AsistentesController::class, 'limpiar'])->name('asistentes.limpiar');
Route::get('/asistentes/allPrediosCheck', [AsistentesController::class, 'allPrediosCheck'])->name('asistentes.allPrediosCheck');
Route::get('/asistentes/allPrediosUncheck', [AsistentesController::class, 'allPrediosUncheck'])->name('asistentes.allPrediosUncheck');
Route::post('/asistentes/asignar', [AsistentesController::class, 'asignar'])->name('asistentes.asignar');

