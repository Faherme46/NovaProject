<?php

use App\Http\Middleware\isAsambleaEnd;
use Illuminate\Support\Facades\Route;


use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\EnsureAsambleaOn;


use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\isAsambleaInit;
use App\Livewire\PresentQuestion;
use App\Livewire\Votacion;
use App\Livewire\LiderSetup;
use App\Livewire\Reports;
use App\Livewire\Main;
use App\Livewire\Consulta;
use App\Livewire\Registrar;
use App\Livewire\Asignacion;
use App\Livewire\Entregar;
use App\Livewire\JobStatus;
use App\Livewire\ListUsers;
use App\Livewire\Setup;
use App\Livewire\Signing;
use App\Livewire\Signs;

//rutas de redireccion


//rutas de las predios

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin')]], function () {
    Route::delete('/session/destroy', [SessionController::class, 'destroyAll'])->name('session.destroy');
    Route::get('gestion/informes', Reports::class)->name('gestion.report');
    Route::get('setup', Setup::class)->name('setup.main');
    Route::get('gestion/informes/Informe', [ReportController::class,'createReport'])->name('gestion.report.docs');
    Route::post('/predios/import', [PrediosController::class, 'import'])->name('predios.import');
    Route::post('questions/setResult', [QuestionsController::class,   'setResult'])->name('questions.setResult');
    Route::post('gestion/asamblea/update', [AsambleaController::class,'updateAsamblea'])->name('asamblea.update');
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider')]], function () {

    Route::resource('asambleas', AsambleaController::class)->withoutMiddleware(EnsureAsambleaOn::class);


    Route::get('/users', ListUsers::class)->name('users.index')->withoutMiddleware([EnsureAsambleaOn::class]);;
    Route::post('users/create', [UsersController::class, 'createUser'])->name('users.create');
    Route::get('users/import', [UsersController::class, 'importUsers'])->name('users.import');
    Route::delete('users/delete', [UsersController::class, 'deleteUser'])->name('users.delete');

    Route::get('gestion/asamblea', LiderSetup::class)->name('gestion.asamblea');

    Route::get('votacion', Votacion::class)->name('votacion')->middleware(isAsambleaEnd::class)->middleware(isAsambleaInit::class);
    Route::get('questions/show/{questionId}',PresentQuestion::class)->name('questions.show');

    Route::post('predios/update', [PrediosController::class, 'updatePredio'])->name('predios.update');
    Route::post('personas/update', [PersonasController::class, 'updatePersona'])->name('personas.update');
    Route::post('questions/create',[QuestionsController::class,'createQuestion'])->name('questions.create')->middleware(isAsambleaEnd::class)->middleware(isAsambleaInit::class);



});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider|Operario')]], function () {
    Route::get('/asistencia/asignacion', Asignacion::class)->name('asistencia.asignacion')->middleware(isAsambleaEnd::class);

    Route::get('/asistencia/registrar', Registrar::class)->name('asistencia.registrar')->middleware(isAsambleaEnd::class);
    Route::get('/consulta', Consulta::class)->name('consulta');
    Route::get('/entregar', Entregar::class)->name('entregar');
    Route::get('/', Main::class)->name('home')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::post('/gestion/saveSign', [FileController::class,'saveSignImg'])->name('gestion.sign.save')->middleware(isAsambleaEnd::class);
    Route::get('/asistencia/firmas', Signs::class)->name('asistencia.signs')->middleware(isAsambleaEnd::class);
    Route::get('/asistencia/firmando', Signing::class)->name('asistencia.signing')->middleware(isAsambleaEnd::class);
});
//rutas para registro


//Rutas de autenticacion
Route::get('/login', [LoginController::class, 'logView'])->name('login')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::post('/login', [LoginController::class, 'authenticate'])->name('users.authenticate')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);;
Route::get('/logout', [LoginController::class, 'logout'])->name('users.logout')->withoutMiddleware(EnsureAsambleaOn::class);

//rutas de prueba
Route::get('/proofAsignacion', [Asignacion::class, 'proofAsignacion'])->name('proofAsignacion');
Route::get('proofQuestion',[FileController::class,'exportAllQuestions']);
Route::get('proofExport',[FileController::class,'exportTables']);

