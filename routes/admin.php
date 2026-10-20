<?php

use App\Http\Controllers\Admin\AgressorController;
use App\Http\Controllers\Admin\CadastroController;
use App\Http\Controllers\Admin\LocalSeguroController;
use App\Http\Controllers\Admin\MapaController;
use App\Http\Controllers\Admin\MedidaController;
use App\Http\Controllers\Admin\OcorrenciaController;
use App\Http\Controllers\Admin\PainelController;
use App\Http\Controllers\Admin\PerfilController;
use App\Http\Controllers\Admin\RelatorioController;
use App\Http\Controllers\Admin\TornozeleiraController;
use App\Http\Controllers\Admin\VitimaController;
use Illuminate\Support\Facades\Route;

// Os parâmetros de rota de recursos só aceitam números (evita que "adicionar" etc. caiam em {id}).
Route::pattern('vitima', '[0-9]+');
Route::pattern('agressor', '[0-9]+');
Route::pattern('tornozeleira', '[0-9]+');
Route::pattern('relatorio', '[0-9]+');

Route::prefix('administracao')->name('admin.')->group(function () {

    /* ------------------------------ Home e estatísticas ------------------------------ */
    Route::get('/estatisticas', [PainelController::class, 'index'])->name('estatisticas');
    Route::get('/painel', [PainelController::class, 'index'])->name('painel');
    Route::get('/dashboard', [PainelController::class, 'index'])->name('dashboard');

    /* ------------------------------ Mapa de monitoramento ------------------------------ */
    Route::get('/monitoramento', [MapaController::class, 'index'])->name('monitoramento');

    /* ------------------------------ Cadastro unificado em fases ------------------------------ */
    // vítima > agressor > tornozeleira > medida protetiva
    Route::get('/cadastro', [CadastroController::class, 'index'])->name('cadastro.index');
    Route::post('/cadastro/validar/{fase}', [CadastroController::class, 'validar'])
        ->whereIn('fase', CadastroController::FASES)
        ->name('cadastro.validar');
    Route::post('/cadastro', [CadastroController::class, 'salvar'])->name('cadastro.salvar');

    // Os antigos cadastros isolados agora levam para o fluxo unificado.
    Route::redirect('vitimas/adicionar', '/administracao/cadastro')->name('vitimas.criar');
    Route::redirect('agressores/adicionar', '/administracao/cadastro')->name('agressores.criar');
    Route::redirect('tornozeleiras/adicionar', '/administracao/cadastro')->name('tornozeleiras.criar');
    Route::redirect('medidas-protetivas/adicionar', '/administracao/cadastro')->name('medidas-protetivas.criar');

    /* ------------------------------ Vítimas (lista, detalhes, edição) ------------------------------ */
    Route::get('vitimas/solicitacoes', [LocalSeguroController::class, 'index'])->name('vitimas.solicitacoes');

    Route::resource('vitimas', VitimaController::class)
        ->except(['create', 'store'])
        ->names([
            'index' => 'vitimas.index',
            'show' => 'vitimas.visualizar',
            'edit' => 'vitimas.editar',
            'update' => 'vitimas.atualizar',
            'destroy' => 'vitimas.remover',
        ])
        ->parameters(['vitimas' => 'vitima']);
    Route::patch('vitimas/{vitima}/status', [VitimaController::class, 'alterarStatus'])->whereNumber('vitima')->name('vitimas.status');

    /* ------------------------------ Agressores ------------------------------ */
    Route::resource('agressores', AgressorController::class)
        ->except(['create', 'store'])
        ->names([
            'index' => 'agressores.index',
            'show' => 'agressores.visualizar',
            'edit' => 'agressores.editar',
            'update' => 'agressores.atualizar',
            'destroy' => 'agressores.remover',
        ])
        ->parameters(['agressores' => 'agressor']);
    Route::patch('agressores/{agressor}/status', [AgressorController::class, 'alterarStatus'])->whereNumber('agressor')->name('agressores.status');

    /* ------------------------------ Tornozeleiras ------------------------------ */
    Route::resource('tornozeleiras', TornozeleiraController::class)
        ->except(['create', 'store'])
        ->names([
            'index' => 'tornozeleiras.index',
            'show' => 'tornozeleiras.visualizar',
            'edit' => 'tornozeleiras.editar',
            'update' => 'tornozeleiras.atualizar',
            'destroy' => 'tornozeleiras.remover',
        ]);
    Route::patch('tornozeleiras/{tornozeleira}/status', [TornozeleiraController::class, 'alterarStatus'])->whereNumber('tornozeleira')->name('tornozeleiras.status');

    /* ------------------------------ Medidas protetivas ------------------------------ */
    Route::get('medidas-protetivas', [MedidaController::class, 'index'])->name('medidas-protetivas');
    Route::get('medidas-protetivas/{medida}', [MedidaController::class, 'show'])->whereNumber('medida')->name('medidas-protetivas.visualizar');
    Route::patch('medidas-protetivas/{medida}/status', [MedidaController::class, 'alterarStatus'])->whereNumber('medida')->name('medidas-protetivas.status');

    /* ------------------------------ Locais seguros ------------------------------ */
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

    /* ------------------------------ Ocorrências e relatórios ------------------------------ */
    Route::get('ocorrencias', [OcorrenciaController::class, 'index'])->name('ocorrencias.index');
    Route::get('ocorrencias/criar', [OcorrenciaController::class, 'create'])->name('ocorrencias.criar');
    Route::post('ocorrencias', [OcorrenciaController::class, 'store'])->name('ocorrencias.salvar');
    Route::get('ocorrencias/{ocorrencia}', [OcorrenciaController::class, 'show'])->whereNumber('ocorrencia')->name('ocorrencias.visualizar');
    Route::post('ocorrencias/{ocorrencia}/iniciar', [OcorrenciaController::class, 'iniciar'])->whereNumber('ocorrencia')->name('ocorrencias.iniciar');
    Route::get('ocorrencias/{ocorrencia}/relatorio', [OcorrenciaController::class, 'relatorio'])->whereNumber('ocorrencia')->name('ocorrencias.relatorio');
    Route::post('ocorrencias/{ocorrencia}/finalizar', [OcorrenciaController::class, 'finalizar'])->whereNumber('ocorrencia')->name('ocorrencias.finalizar');

    Route::resource('relatorios', RelatorioController::class)
        ->names([
            'index' => 'relatorios.index',
            'show' => 'relatorios.visualizar',
        ])
        ->parameters(['relatorios' => 'relatorio'])
        ->only(['index', 'show']);

    /* ------------------------------ Perfil ------------------------------ */
    Route::get('perfil', [PerfilController::class, 'visualizar'])->name('perfil');
    Route::get('perfil/editar', [PerfilController::class, 'editar'])->name('perfil.editar');
    Route::patch('perfil', [PerfilController::class, 'atualizar'])->name('perfil.atualizar');
    Route::get('perfil/foto', [PerfilController::class, 'foto'])->name('perfil.foto');
});
