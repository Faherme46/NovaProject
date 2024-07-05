<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\AsistentesController;
use App\Http\Controllers\LoginController;
use App\Livewire\Registrar;
use Illuminate\Support\Facades\Route;


use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\EnsureAsambleaOn;
use App\View\Components\AllPredios;

//rutas de redireccion





Route::get('/', function () {
    return view('welcome');
})->name('home')->withoutMiddleware([EnsureAsambleaOn::class, ValidateLogin::class]);
Route::get('/votos', function () {
    return view('votos');
});
Route::get('/preguntas', function () {
    return view('preguntas');
});
Route::get('/resultados', function () {
    return view('resultados');
});


//rutas para las asambleas



Route::get('/files/export', [FileController::class, 'export'])->name('files.export');

//rutas de las predios



Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin')]], function () {
    Route::delete('/session/destroy', [SessionController::class, 'destroyAll'])->name('session.destroy');
    //Rutas de Usuarios

    Route::post('/predios/import', [PrediosController::class, 'import'])->name('predios.import');
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider')]], function () {
    Route::get('admin/asambleas', [AsambleaController::class, 'index'])->name('admin.asambleas')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::resource('asambleas', AsambleaController::class)->withoutMiddleware(EnsureAsambleaOn::class);
    Route::get('/users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users/create', [UsersController::class, 'createUser'])->name('users.create');
    Route::get('users/import', [UsersController::class, 'importUsers'])->name('users.import');
    Route::post('admin/inicio', [AsambleaController::class, 'iniciarAsamblea'])->name('asambleas.inicia');
    Route::post('admin/termina', [AsambleaController::class, 'terminarAsamblea'])->name('asambleas.termina');
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider|Operario')]], function () {
    Route::get('/asistencia/registro', [AsistentesController::class, 'index'])->name('asistencia.index');
    Route::get('/asistencia/registrar', Registrar::class)->name('asistencia.registrar');
    Route::get('/asistencia/buscar', [AsistentesController::class, 'buscar'])->name('asistencia.buscar');
    Route::get('/asistencia/limpiar', [AsistentesController::class, 'limpiar'])->name('asistencia.limpiar');
    Route::get('/asistencia/addPoderdante', [AsistentesController::class, 'addPoderdante'])->name('asistencia.addPoderdante');
    Route::get('/asistencia/addPoderdante/{id}', [AsistentesController::class, 'addPoderdanteId'])->name('asistencia.addPoderdanteId');
    Route::get('/asistencia/dropPoderdante', [AsistentesController::class, 'dropPoderdante'])->name('asistencia.dropPoderdante');
    Route::get('/asistencia/dropAllPoderdantes', [AsistentesController::class, 'dropAllPoderdantes'])->name('asistencia.dropAllPoderdante');
    Route::get('/asistencia/dropAsignacion', [AsistentesController::class, 'dropAsignacion'])->name('asistencia.dropAsignacion');
    Route::get('/asistencia/changeAsignacion', [AsistentesController::class, 'changeAsignacion'])->name('asistencia.changeAsignacion');
    Route::get('/asistencia/addPredio', [AsistentesController::class, 'addPredio'])->name('asistencia.addPredio');
    Route::post('/asistencia/creaPersona', [AsistentesController::class, 'creaPersona'])->name('asistencia.creaPersona');
    Route::post('/asistencia/asignar', [AsistentesController::class, 'asignar'])->name('asistencia.asignar');
    Route::post('/asistencia/editAsignacion', [AsistentesController::class, 'editAsignacion'])->name('asistencia.editAsignacion');
});
//rutas para registro


//Rutas de autenticacion
Route::get('/login', [LoginController::class, 'logView'])->name('login')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::post('/login', [LoginController::class, 'authenticate'])->name('users.authenticate')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);;
Route::get('/logout', [LoginController::class, 'logout'])->name('users.logout')->withoutMiddleware(EnsureAsambleaOn::class);

//rutas de prueba
Route::get('/asistenciaa', [AsistentesController::class, 'asistencia'])->name('asistenciaa');
