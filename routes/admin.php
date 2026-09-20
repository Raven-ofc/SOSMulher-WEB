<?php

use App\Http\Controllers\Admin\VitimaController;
use App\Http\Controllers\Admin\AgressorController;
use App\Http\Controllers\Admin\OcorrenciaController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\LocalSeguroController;
use App\Http\Controllers\Admin\PainelController;
use App\Http\Controllers\Admin\PerfilController;
use Illuminate\Support\Facades\Route;

Route::prefix('administracao')->name('admin.')->group(function () {
    Route::get('/estatisticas', [PainelController::class, 'index'])->name('estatisticas');
    Route::get('/painel', [PainelController::class, 'index'])->name('painel');
    Route::get('/dashboard', [PainelController::class, 'index'])->name('dashboard');

    // Rotas específicas ANTES dos resources para evitar conflitos
    Route::get('vitimas/solicitacoes', [LocalSeguroController::class, 'index'])->name('vitimas.solicitacoes');

    // Resource routes
    Route::resource('vitimas', VitimaController::class)
        ->names([
            'index' => 'vitimas.index',
            'create' => 'vitimas.criar',
            'store' => 'vitimas.salvar',
            'show' => 'vitimas.visualizar',
            'edit' => 'vitimas.editar',
            'update' => 'vitimas.atualizar',
            'destroy' => 'vitimas.remover',
        ])
        ->parameters(['vitimas' => 'vitima']);
    Route::patch('vitimas/{vitima}/status', [VitimaController::class, 'alterarStatus'])->name('vitimas.status');
    Route::redirect('vitimas/visualizar', 'administracao/vitimas')->name('vitimas.preview');

    Route::resource('agressores', AgressorController::class)
        ->names([
            'index' => 'agressores.index',
            'create' => 'agressores.criar',
            'store' => 'agressores.salvar',
            'show' => 'agressores.visualizar',
            'edit' => 'agressores.editar',
            'update' => 'agressores.atualizar',
            'destroy' => 'agressores.remover',
        ])
        ->parameters(['agressores' => 'agressor']);
    Route::patch('agressores/{agressor}/status', [AgressorController::class, 'alterarStatus'])->name('agressores.status');
    Route::redirect('agressores/visualizar', 'administracao/agressores')->name('agressores.preview');

    Route::resource('locais-seguros', LocalSeguroController::class)
        ->names([
            'index' => 'locais-seguros.index',
            'create' => 'locais-seguros.criar',
            'store' => 'locais-seguros.salvar',
            'show' => 'locais-seguros.visualizar',
            'edit' => 'locais-seguros.editar',
            'update' => 'locais-seguros.atualizar',
            'destroy' => 'locais-seguros.remover',
        ])
        ->only(['create', 'store']);
    Route::patch('locais-seguros/{id}/decidir', [LocalSeguroController::class, 'decidir'])->name('locais-seguros.decidir');
    Route::delete('locais-seguros/{id}', [LocalSeguroController::class, 'remover'])->name('locais-seguros.remover');

    Route::get('ocorrencias', [OcorrenciaController::class, 'index'])->name('ocorrencias.index');
    Route::get('ocorrencias/criar', [OcorrenciaController::class, 'create'])->name('ocorrencias.criar');
    Route::post('ocorrencias', [OcorrenciaController::class, 'store'])->name('ocorrencias.salvar');
    Route::get('ocorrencias/{ocorrencia}', [OcorrenciaController::class, 'show'])->name('ocorrencias.visualizar');
    Route::get('ocorrencias/{ocorrencia}/relatorio', [OcorrenciaController::class, 'relatorio'])->name('ocorrencias.relatorio');
    Route::post('ocorrencias/{ocorrencia}/finalizar', [OcorrenciaController::class, 'finalizar'])->name('ocorrencias.finalizar');
    Route::redirect(
        'ocorrencias/visualizar',
        'administracao/ocorrencias'
    )->name('ocorrencias.preview');

    Route::resource('relatorios', RelatorioController::class)
        ->names([
            'index' => 'relatorios.index',
            'create' => 'relatorios.criar',
            'store' => 'relatorios.salvar',
            'show' => 'relatorios.visualizar',
            'edit' => 'relatorios.editar',
            'update' => 'relatorios.atualizar',
            'destroy' => 'relatorios.remover',
        ])
        ->parameters(['relatorios' => 'relatorio'])
        ->only(['index', 'show']);
    Route::redirect('relatorios/visualizar', 'administracao/relatorios')->name('relatorios.preview');

    // Aliases para compatibilidade (apenas os que não conflitam com resource routes)
    Route::get('vitimas/adicionar', fn () => redirect()->route('admin.vitimas.criar'))->name('victims.create');
    Route::get('vitimas/{id}/editar', fn ($id) => redirect()->route('admin.vitimas.editar', $id))->name('victims.edit');
    Route::get('vitimas/{id}', fn ($id) => redirect()->route('admin.vitimas.visualizar', $id))->name('victims.show');

    Route::get('agressores/adicionar', fn () => redirect()->route('admin.agressores.criar'))->name('aggressors.create');
    Route::get('agressores/{id}/editar', fn ($id) => redirect()->route('admin.agressores.editar', $id))->name('aggressors.edit');
    Route::get('agressores/{id}', fn ($id) => redirect()->route('admin.agressores.visualizar', $id))->name('aggressors.show');

    Route::get('ocorrencias/adicionar', fn () => redirect()->route('admin.ocorrencias.criar'))->name('occurrences.create');

    Route::get('perfil', fn () => redirect()->route('admin.perfil'))->name('profile');
    Route::get('perfil/editar', fn () => redirect()->route('admin.perfil.editar'))->name('profile.edit');
    Route::get('perfil/foto', fn () => redirect()->route('admin.perfil.foto'))->name('profile.photo');

    Route::view('/tornozeleiras', 'administracao.tornozeleira.lista')->name('devices');
    Route::view('/tornozeleiras/adicionar', 'administracao.tornozeleira.cadastro')->name('devices.create');

    Route::get('perfil', [PerfilController::class, 'visualizar'])->name('perfil');
    Route::get('perfil/editar', [PerfilController::class, 'editar'])->name('perfil.editar');
    Route::patch('perfil', [PerfilController::class, 'atualizar'])->name('perfil.atualizar');
    Route::get('perfil/foto', [PerfilController::class, 'foto'])->name('perfil.foto');

    Route::view('/monitoramento', 'administracao.monitoramento')->name('monitoramento');
    Route::view('/medidas-protetivas', 'administracao.medida-protetiva.lista')->name('medidas-protetivas');
    Route::view('/medidas-protetivas/adicionar', 'administracao.medida-protetiva.cadastro')->name('medidas-protetivas.criar');
    Route::view('/medidas-protetivas/visualizar', 'administracao.medida-protetiva.detalhes')->name('medidas-protetivas.visualizar');
});