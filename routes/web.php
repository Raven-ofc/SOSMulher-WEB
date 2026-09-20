<?php

// use App\Http\Controllers\Auth\AcessoController;
use App\Http\Controllers\Auth\SenhaController;
use App\Http\Controllers\AutoridadeController;
use App\Http\Controllers\Admin\PainelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::view('/', 'home')->name('home');

Route::view('/login', 'autenticacao.acesso')->name('login');

Route::post('/fazerLogin', [AutoridadeController::class, 'fazerLogin'])
    ->name('fazerLogin');

Route::view('/esqueci-senha', 'autenticacao.esqueci-senha')
    ->name('senha.solicitar');

Route::post('/esqueci-senha', [SenhaController::class, 'email'])
    ->middleware('throttle:5,1')
    ->name('senha.email');

Route::get('/email-enviado', [SenhaController::class, 'enviado'])
    ->name('senha.email.enviado');

Route::get('/redefinir-senha', [SenhaController::class, 'editar'])
    ->name('senha.redefinir');

Route::post('/redefinir-senha', [SenhaController::class, 'atualizar'])
    ->middleware('throttle:5,1')
    ->name('senha.atualizar');

Route::view('/autoridades', 'administracao.autoridade.lista')->name('autoridades');
Route::view('/autoridades/adicionar', 'administracao.autoridade.formulario')->name('autoridades.criar');

Route::post('/autoridades', function (Request $request) {
        $request->validate(['cpf' => ['required', 'string'], 'telefone' => ['required', 'string']]);
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'telefone' => preg_replace('/\D/', '', $request->telefone),
        ]);
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'regex:/^\d{11}$/'],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'telefone' => ['required', 'regex:/^\d{10,11}$/'],
            'email' => ['sometimes', 'email', 'max:100'],
            'cargo' => ['nullable', 'string', 'max:100'],
        ]);

        return redirect()->route('admin.autoridades')->with('status', 'Autoridade cadastrada.');
    })->name('autoridades.salvar');

    Route::view('/autoridades/{id}/editar', 'administracao.autoridade.formulario')->whereNumber('id')->name('autoridades.editar');

    Route::patch('/autoridades/{id}', function (Request $request, int $id) {
        $request->validate(['cpf' => ['required', 'string'], 'telefone' => ['required', 'string']]);
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'telefone' => preg_replace('/\D/', '', $request->telefone),
        ]);
        $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'regex:/^\d{11}$/'],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'telefone' => ['required', 'regex:/^\d{10,11}$/'],
            'email' => ['sometimes', 'email', 'max:100'],
            'cargo' => ['nullable', 'string', 'max:100'],
        ]);

        return redirect()->route('admin.autoridades')->with('status', 'Autoridade atualizada.');
    })->whereNumber('id')->name('autoridades.atualizar');

    Route::delete('/autoridades/{id}', function () {
        return back()->with('status', 'Autoridade excluída.');
    })->whereNumber('id')->name('autoridades.remover');


Route::middleware(['auth', 'auth.session'])->group(function () {

    Route::post('/logout', [AutoridadeController::class, 'sair'])
        ->name('logout');

    Route::get('/painel', [PainelController::class, 'index'])
        ->name('painel');

    require __DIR__ . '/admin.php';
});