<?php

use App\Http\Middleware\isAsambleaEnd;
use Illuminate\Support\Facades\Route;


use App\Http\Middleware\ValidateLogin;
use App\Http\Middleware\EnsureAsambleaOn;


use App\Http\Controllers\SessionController;
use App\Http\Controllers\AsambleaController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EleccionesController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonasController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\EleccionesMiddleware;
use App\Http\Middleware\EnsureAsambleaOff;
use App\Http\Middleware\isAsambleaInit;
use App\Http\Middleware\isLogged;
use App\Livewire\PresentQuestion;
use App\Livewire\Votacion;
use App\Livewire\LiderSetup;
use App\Livewire\Reports;
use App\Livewire\Main;
use App\Livewire\Consulta;
use App\Livewire\Registrar;
use App\Livewire\Asignacion;
use App\Livewire\Elecciones\Elecciones;
use App\Livewire\Elecciones\Programar;
use App\Livewire\Elecciones\Registro;
use App\Livewire\Entregar;
use App\Livewire\ListUsers;
use App\Livewire\LoadAsamblea;
use App\Livewire\QuorumFull;
use App\Livewire\Setup;
use App\Livewire\ShowVotacion;
use App\Livewire\Signing;
use App\Livewire\Signs;
use App\Livewire\ViewQuestion;

//rutas de redireccion


//rutas de las predios

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin')]], function () {
    Route::get('gestion/informes/Informe', [ReportController::class, 'createReport'])->name('gestion.report.docs');

    Route::post('question/update', [Controller::class, 'updateQuestion'])->name('question.update');
    Route::post('question/prefab/create', [Controller::class, 'createPrefabQuestion'])->name('question.prefab.create');
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider')]], function () {

    Route::get('asambleas', LoadAsamblea::class)->name('asambleas')->middleware(EnsureAsambleaOff::class)->withoutMiddleware(EnsureAsambleaOn::class);
    Route::get('asambleas/load', [AsambleaController::class, 'loadAsambleas'])->name('asambleas.load')->withoutMiddleware(EnsureAsambleaOn::class)->middleware(EnsureAsambleaOff::class);
    Route::get('gestion/informes', Reports::class)->name('gestion.report');
    Route::get('setup', Setup::class)->name('setup.main')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::get('backup/download', [BackupController::class, 'downloadBackup']);
    Route::get('users', ListUsers::class)->name('users.index')->withoutMiddleware([EnsureAsambleaOn::class]);
    Route::get('users/import', [UsersController::class, 'importUsers'])->name('users.import')->withoutMiddleware([EnsureAsambleaOn::class]);
    Route::get('gestion/asamblea', LiderSetup::class)->name('gestion.asamblea');
    Route::get('desterminar', [LiderSetup::class, 'desterminar'])->name('desterminar');
    Route::get('votacion', Votacion::class)->name('votacion')->middleware(isAsambleaEnd::class);
    Route::get('questions/show/{questionId}/{plancha?}/{plazas?}', PresentQuestion::class)->name('questions.show');
    Route::get('questions/view/{questionId}', ViewQuestion::class)->name('questions.view');
    Route::get('elecciones/programar', Programar::class)->name('elecciones.programar')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::get('elecciones/registrar', Registro::class)->name('elecciones.registrar')->withoutMiddleware(EnsureAsambleaOn::class);


    Route::post('asambleas/store', [AsambleaController::class, 'store'])->name('asambleas.store')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::post('predios/import', [PrediosController::class, 'import'])->name('predios.import');
    Route::post('predios/update', [PrediosController::class, 'updatePredio'])->name('predios.update');
    Route::post('gestion/asamblea/update', [AsambleaController::class, 'updateAsamblea'])->name('asamblea.update');
    Route::post('personas/update', [PersonasController::class, 'updatePersona'])->name('personas.update');
    Route::post('backup/restore', [BackupController::class, 'restoreBackup'])->middleware(EnsureAsambleaOff::class)->name('backup.restore')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::post('users/create', [UsersController::class, 'createUser'])->name('users.create')->withoutMiddleware([EnsureAsambleaOn::class]);
    Route::post('users/update', [UsersController::class, 'updateUser'])->name('users.update')->withoutMiddleware([EnsureAsambleaOn::class]);
    Route::post('personas/export', [PersonasController::class, 'signsExports'])->name('personas.export');
    Route::post('elecciones/torres/create', [EleccionesController::class, 'createTorres'])->name('elecciones.torres.create');
    Route::post('elecciones/store', [EleccionesController::class, 'store'])->name('elecciones.store')->withoutMiddleware([EnsureAsambleaOn::class]);

    Route::delete('session/destroy', [SessionController::class, 'destroyAll'])->name('session.destroy');
    Route::delete('asamblea/delete', [AsambleaController::class, 'deleteAsamblea'])->name('asamblea.delete')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::delete('users/delete', [UsersController::class, 'deleteUser'])->name('users.delete')->withoutMiddleware([EnsureAsambleaOn::class]);
});

Route::group(['middleware' => [\Spatie\Permission\Middleware\RoleMiddleware::using('Admin|Lider|Operario')]], function () {
    Route::get('/', Main::class)->name('home')->withoutMiddleware(EnsureAsambleaOn::class)->middleware(EleccionesMiddleware::class);
    Route::get('elecciones',  Elecciones::class)->name('home.elecciones')->withoutMiddleware(EnsureAsambleaOn::class);
    Route::get('asistencia/asignacion', Asignacion::class)->name('asistencia.asignacion')->middleware(isAsambleaEnd::class);
    Route::get('asistencia/firmas', Signs::class)->name('asistencia.signs')->middleware(isAsambleaEnd::class);
    Route::get('asistencia/firmando', Signing::class)->name('asistencia.signing')->middleware(isAsambleaEnd::class);
    Route::get('asistencia/registrar', Registrar::class)->name('asistencia.registrar')->middleware(isAsambleaEnd::class);
    Route::get('votacion/show', ShowVotacion::class)->name('votacion.show');
    Route::get('consulta', Consulta::class)->name('consulta');
    Route::get('entregar', Entregar::class)->name('entregar');
    Route::get('quorum', QuorumFull::class)->name('quorum.show');
    Route::post('gestion/saveSign', [FileController::class, 'saveSignImg'])->name('gestion.sign.save')->middleware(isAsambleaEnd::class);
});
//rutas para livewire


//Rutas de autenticacion
Route::get('/login', [LoginController::class, 'logView'])->name('login')->middleware(isLogged::class)->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);
Route::post('/login', [LoginController::class, 'authenticate'])->name('users.authenticate')->withoutMiddleware(ValidateLogin::class)->withoutMiddleware(EnsureAsambleaOn::class);;
Route::get('/logout', [LoginController::class, 'logout'])->name('users.logout')->withoutMiddleware(EnsureAsambleaOn::class);

//rutas de prueba
Route::get('/proofAsignacion', [Asignacion::class, 'proofAsignacion'])->name('proofAsignacion');
Route::get('proofQuestion', [FileController::class, 'exportAllQuestions']);
Route::get('proofExport', [FileController::class, 'exportTables']);

//Route::get('proofExport',[PrediosController::class,'export']);
