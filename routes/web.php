<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\LoginController;

use App\Livewire\Consulta;
use App\Livewire\Registrar;
use App\Livewire\Asignacion;
use App\Livewire\Entregar;

use Illuminate\Support\Facades\Route;


use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\EnsureAsambleaOn;
use App\Livewire\Votacion;

//rutas de redireccion





Route::get('/', function () {
    return view('welcome');
})->name('home')->withoutMiddleware([EnsureAsambleaOn::class]);
Route::get('/votos', function () {
    return view('votos');
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
    Route::get('/users', [UsersController::class, 'index'])->name('users.index')->withoutMiddleware([EnsureAsambleaOn::class]);;
    Route::post('users/create', [UsersController::class, 'createUser'])->name('users.create');
    Route::get('users/import', [UsersController::class, 'importUsers'])->name('users.import');
    Route::get('votacion', Votacion::class)->name('votacion');
    Route::post('admin/inicio', [AsambleaController::class, 'iniciarAsamblea'])->name('asambleas.inicia');
    Route::post('admin/termina', [AsambleaController::class, 'terminarAsamblea'])->name('asambleas.termina');
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider|Operario')]], function () {
    Route::get('/asistencia/asignacion', Asignacion::class)->name('asistencia.asignacion');
    Route::get('/asistencia/registrar', Registrar::class)->name('asistencia.registrar');
    Route::get('/consulta', Consulta::class)->name('consulta');
    Route::get('/entregar', Entregar::class)->name('entregar');

});
//rutas para registro


//Rutas de autenticacion
Route::get('/login', [LoginController::class, 'logView'])->name('login')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::post('/login', [LoginController::class, 'authenticate'])->name('users.authenticate')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);;
Route::get('/logout', [LoginController::class, 'logout'])->name('users.logout')->withoutMiddleware(EnsureAsambleaOn::class);

//rutas de prueba
Route::get('/asistenciaa', [AsambleaController::class, 'asistencia'])->name('asistenciaa');
