<?php

use App\Http\Controllers\AgressorController;
use App\Http\Controllers\PainelController;
use App\Http\Controllers\AcessoController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\SenhaController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\LocalSeguroController;
use App\Http\Controllers\VitimaController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'inicio')->name('home');
Route::view('/login', 'autenticacao.acesso')->name('login');
Route::post('/login', [AcessoController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::view('/esqueci-senha', 'autenticacao.esqueci-senha')->name('password.request');
Route::post('/esqueci-senha', [SenhaController::class, 'email'])->middleware('throttle:5,1')->name('password.email');
Route::get('/email-enviado', [SenhaController::class, 'sent'])->name('password.email.sent');
Route::get('/redefinir-senha', [SenhaController::class, 'edit'])->name('password.reset');
Route::post('/redefinir-senha', [SenhaController::class, 'update'])->middleware('throttle:5,1')->name('password.update');
Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::post('/logout', [AcessoController::class, 'destroy'])->name('logout');
    Route::get('/painel', [PainelController::class, 'index'])->name('dashboard');
    Route::prefix('administracao')->name('admin.')->group(function () {
        Route::get('/estatisticas', [PainelController::class, 'index'])->name('statistics');
        // Vitimas
        Route::get('/vitimas', [VitimaController::class, 'index'])->name('victims');
        Route::get('/vitimas/adicionar', [VitimaController::class, 'create'])->name('victims.create');
        Route::post('/vitimas', [VitimaController::class, 'store'])->name('victims.store');
        Route::get('/vitimas/{id}/editar', [VitimaController::class, 'edit'])->whereNumber('id')->name('victims.edit');
        Route::get('/vitimas/{id}', [VitimaController::class, 'show'])->whereNumber('id')->name('victims.show');
        Route::patch('/vitimas/{id}', [VitimaController::class, 'update'])->whereNumber('id')->name('victims.update');
        Route::patch('/vitimas/{id}/status', [VitimaController::class, 'status'])->whereNumber('id')->name('victims.status');
        Route::redirect('/vitimas/visualizar', '/administracao/vitimas')->name('victims.preview');

        // Agressores
        Route::get('/agressores', [AgressorController::class, 'index'])->name('aggressors');
        Route::get('/agressores/adicionar', [AgressorController::class, 'create'])->name('aggressors.create');
        Route::post('/agressores', [AgressorController::class, 'store'])->name('aggressors.store');
        Route::get('/agressores/{id}/editar', [AgressorController::class, 'edit'])->whereNumber('id')->name('aggressors.edit');
        Route::get('/agressores/{id}', [AgressorController::class, 'show'])->whereNumber('id')->name('aggressors.show');
        Route::patch('/agressores/{id}', [AgressorController::class, 'update'])->whereNumber('id')->name('aggressors.update');
        Route::patch('/agressores/{id}/status', [AgressorController::class, 'status'])->whereNumber('id')->name('aggressors.status');
        Route::redirect('/agressores/visualizar', '/administracao/agressores')->name('aggressors.preview');

        Route::get('/vitimas/solicitacoes', [LocalSeguroController::class, 'index'])->name('victims.requests');
        Route::get('/locais-seguros/adicionar', [LocalSeguroController::class, 'create'])->name('places.create');
        Route::post('/locais-seguros', [LocalSeguroController::class, 'store'])->name('places.store');
        Route::patch('/locais-seguros/{id}', [LocalSeguroController::class, 'decide'])->whereNumber('id')->name('places.decide');
        Route::delete('/locais-seguros/{id}', [LocalSeguroController::class, 'remove'])->whereNumber('id')->name('places.remove');
        Route::get('/ocorrencias', [OcorrenciaController::class, 'index'])->name('occurrences');
        Route::get('/ocorrencias/adicionar', [OcorrenciaController::class, 'create'])->name('occurrences.create');
        Route::post('/ocorrencias', [OcorrenciaController::class, 'store'])->name('occurrences.store');
        Route::redirect('/ocorrencias/visualizar', '/administracao/ocorrencias')->name('occurrences.preview');
        Route::redirect('/ocorrencias/visualizar/relatorio', '/administracao/ocorrencias')->name('occurrences.report');
        Route::get('/ocorrencias/{id}', [OcorrenciaController::class, 'show'])->whereNumber('id')->name('occurrences.show');
        Route::get('/ocorrencias/{id}/relatorio', [OcorrenciaController::class, 'report'])->whereNumber('id')->name('occurrences.finish');
        Route::post('/ocorrencias/{id}/relatorio', [OcorrenciaController::class, 'finish'])->whereNumber('id')->name('occurrences.complete');
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('reports');
        Route::redirect('/relatorios/visualizar', '/administracao/relatorios')->name('reports.preview');
        Route::get('/relatorios/{id}', [RelatorioController::class, 'show'])->whereNumber('id')->name('reports.show');
        Route::get('/perfil', [PerfilController::class, 'show'])->name('profile');
        Route::get('/perfil/editar', [PerfilController::class, 'edit'])->name('profile.edit');
        Route::patch('/perfil', [PerfilController::class, 'update'])->name('profile.update');
        Route::get('/perfil/foto', [PerfilController::class, 'photo'])->name('profile.photo');
        // Telas de dispositivos e medidas: integração visual pendente.
        // A API original da tornozeleira está em routes/api.php.
        Route::view('/monitoramento', 'administracao.monitoramento')->name('monitoring');
        Route::view('/tornozeleiras', 'administracao.tornozeleira.lista')->name('devices');
        Route::view('/tornozeleiras/adicionar', 'administracao.tornozeleira.cadastro')->name('devices.create');
        Route::view('/medidas-protetivas', 'administracao.medida-protetiva.lista')->name('measures');
        Route::view('/medidas-protetivas/adicionar', 'administracao.medida-protetiva.cadastro')->name('measures.create');
        Route::view('/medidas-protetivas/visualizar', 'administracao.medida-protetiva.detalhes')->name('measures.preview');
    });
});
