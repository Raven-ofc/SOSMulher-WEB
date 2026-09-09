<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SafePlaceController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/login', 'auth.login')->name('login');
Route::post('/login', [LoginController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::view('/esqueci-senha', 'auth.forgot-password')->name('password.request');
Route::post('/esqueci-senha', [PasswordController::class, 'email'])->middleware('throttle:5,1')->name('password.email');
Route::get('/email-enviado', [PasswordController::class, 'sent'])->name('password.email.sent');
Route::get('/redefinir-senha', [PasswordController::class, 'edit'])->name('password.reset');
Route::post('/redefinir-senha', [PasswordController::class, 'update'])->middleware('throttle:5,1')->name('password.update');
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('administracao')->name('admin.')->group(function () {
        Route::get('/estatisticas', [DashboardController::class, 'index'])->name('statistics');
        foreach (['vitimas' => 'victims', 'agressores' => 'aggressors'] as $path => $name) {
            Route::prefix($path)->name($name)->group(function () use ($path) {
                Route::get('/', [PeopleController::class, 'index'])->name('');
                Route::get('/adicionar', [PeopleController::class, 'create'])->name('.create');
                Route::post('/', [PeopleController::class, 'store'])->name('.store');
                Route::redirect('/visualizar', '/administracao/'.$path)->name('.preview');
                Route::get('/{id}/editar', [PeopleController::class, 'edit'])->whereNumber('id')->name('.edit');
                Route::get('/{id}', [PeopleController::class, 'show'])->whereNumber('id')->name('.show');
                Route::patch('/{id}', [PeopleController::class, 'update'])->whereNumber('id')->name('.update');
                Route::patch('/{id}/status', [PeopleController::class, 'status'])->whereNumber('id')->name('.status');
            });
        }
        Route::get('/vitimas/solicitacoes', [SafePlaceController::class, 'index'])->name('victims.requests');
        Route::get('/locais-seguros/adicionar', [SafePlaceController::class, 'create'])->name('places.create');
        Route::post('/locais-seguros', [SafePlaceController::class, 'store'])->name('places.store');
        Route::patch('/locais-seguros/{id}', [SafePlaceController::class, 'decide'])->whereNumber('id')->name('places.decide');
        Route::delete('/locais-seguros/{id}', [SafePlaceController::class, 'remove'])->whereNumber('id')->name('places.remove');
        Route::get('/ocorrencias', [CaseController::class, 'index'])->name('occurrences');
        Route::get('/ocorrencias/adicionar', [CaseController::class, 'create'])->name('occurrences.create');
        Route::post('/ocorrencias', [CaseController::class, 'store'])->name('occurrences.store');
        Route::redirect('/ocorrencias/visualizar', '/administracao/ocorrencias')->name('occurrences.preview');
        Route::redirect('/ocorrencias/visualizar/relatorio', '/administracao/ocorrencias')->name('occurrences.report');
        Route::get('/ocorrencias/{id}', [CaseController::class, 'show'])->whereNumber('id')->name('occurrences.show');
        Route::get('/ocorrencias/{id}/relatorio', [CaseController::class, 'report'])->whereNumber('id')->name('occurrences.finish');
        Route::post('/ocorrencias/{id}/relatorio', [CaseController::class, 'finish'])->whereNumber('id')->name('occurrences.complete');
        Route::get('/relatorios', [CaseController::class, 'index'])->name('reports');
        Route::redirect('/relatorios/visualizar', '/administracao/relatorios')->name('reports.preview');
        Route::get('/relatorios/{id}', [CaseController::class, 'show'])->whereNumber('id')->name('reports.show');
        Route::view('/perfil', 'admin.profile.show')->name('profile');
        Route::view('/perfil/editar', 'admin.profile.edit')->name('profile.edit');
        Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/perfil/foto', [ProfileController::class, 'photo'])->name('profile.photo');
        // Somente referências visuais: sem API, telemetria, cadastro ou cálculos.
        Route::view('/monitoramento', 'admin.monitoring')->name('monitoring');
        Route::view('/tornozeleiras', 'admin.devices.index')->name('devices');
        Route::view('/tornozeleiras/adicionar', 'admin.devices.create')->name('devices.create');
        Route::view('/medidas-protetivas', 'admin.measures.index')->name('measures');
        Route::view('/medidas-protetivas/adicionar','admin.measures.create')->name('measures.create');
        Route::view('/medidas-protetivas/visualizar','admin.measures.show')->name('measures.preview');
    });
});
