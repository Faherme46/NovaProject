<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\AsistentesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;


use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\EnsureAsambleaOn;

//rutas de redireccion



Route::get('/login', function () { return view('login'); })->name('login')->withoutMiddleware(ValidateLogin::class);

Route::get('/', function () {  return view('welcome'); })->name('home')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::get('/crearUsuarios', function () { return view('crearUsuario'); });
Route::get('/votos', function () { return view('votos') ;});
Route::get('/preguntas', function () { return view('preguntas'); });
Route::get('/resultados', function () { return view('resultados') ;});


//rutas para las asambleas
Route::get('admin/asambleas', [AsambleaController::class, 'index'])->name('admin.asambleas')->withoutMiddleware(EnsureAsambleaOn::class);
Route::resource('asambleas', AsambleaController::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::delete('/session/destroy', [SessionController::class, 'destroyAll'])->name('session.destroy');
Route::post('admin/inicio', [AsambleaController::class, 'iniciarAsamblea'])->name('asambleas.inicia');
Route::post('admin/termina', [AsambleaController::class, 'terminarAsamblea'])->name('asambleas.termina');

Route::get('/files/export', [FileController::class, 'export'])->name('files.export');

//rutas de las predios

Route::post('/predios/import', [PrediosController::class, 'import'])->name('predios.import');
Route::delete('predios/destroy', [PrediosController::class, 'destroyAll'])->name('predios.destroyAll');

//rutas para registro

Route::get('/asistencia', [AsistentesController::class, 'index'])->name('asistencia');
Route::get('/asistencia/registrar', [AsistentesController::class, 'index'])->name('asistencia.index');
Route::get('/asistencia/buscar', [AsistentesController::class, 'buscar'])->name('asistencia.buscar');
Route::get('/asistencia/limpiar', [AsistentesController::class, 'limpiar'])->name('asistencia.limpiar');
Route::get('/asistencia/getPredios', [AsistentesController::class, 'getPredios'])->name('asistencia.getPredios');
Route::get('/asistencia/dropPoderdante', [AsistentesController::class, 'dropPoderdante'])->name('asistencia.dropPoderdante');
Route::get('/asistencia/dropAllPoderdantes', [AsistentesController::class, 'dropAllPoderdantes'])->name('asistencia.dropAllPoderdante');
Route::get('/asistencia/dropAsignacion', [AsistentesController::class, 'dropAsignacion'])->name('asistencia.dropAsignacion');
Route::get('/asistencia/changeAsignacion', [AsistentesController::class, 'changeAsignacion'])->name('asistencia.changeAsignacion');
Route::get('/asistencia/addPredio', [AsistentesController::class, 'addPredio'])->name('asistencia.addPredio');
Route::post('/asistencia/creaPersona', [AsistentesController::class, 'creaPersona'])->name('asistencia.creaPersona');
Route::post('/asistencia/asignar', [AsistentesController::class, 'asignar'])->name('asistencia.asignar');
Route::post('/asistencia/editAsignacion', [AsistentesController::class, 'editAsignacion'])->name('asistencia.editAsignacion');






//rutas de prueba
Route::get('/asistenciaa', [AsistentesController::class, 'asistencia'])->name('asistenciaa');
