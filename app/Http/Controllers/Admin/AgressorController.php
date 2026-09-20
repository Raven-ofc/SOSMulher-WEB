<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbAgressor;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AgressorController extends BaseCrudController
{
    protected string $modelo = TbAgressor::class;
    protected string $visualizacaoBase = 'administracao.agressor';
    protected string $rotaBase = 'admin.agressores';

    protected function colunaStatus(): string
    {
        return 'statusAgressor';
    }

    protected function tabelaAuditoria(): string
    {
        return 'tbagressor';
    }

    protected function colunasBusca(): array
    {
        return ['nomeAgressor', 'cpfAgressor'];
    }

    protected function mapearDados(array $dados): array
    {
        return [
            'nomeAgressor' => $dados['nome'],
            'cpfAgressor' => $dados['cpf'],
            'dataNascimentoAgressor' => $dados['data_nascimento'],
            'telefoneAgressor' => $dados['telefone'],
        ];
    }

    protected function variavelLista(): string
    {
        return 'agressores';
    }

    protected function variavelSingular(): string
    {
        return 'agressor';
    }

    protected function ordenacaoPadrao(): array
    {
        return ['nomeAgressor', 'asc'];
    }

    protected function regrasFiltros(): array
    {
        return [
            'busca' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['ativo', 'inativo'])],
        ];
    }

    protected function regrasCriacao(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbagressor', 'cpfAgressor')],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'telefone' => ['required', 'string', 'regex:/^\d{10,11}$/'],
        ];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbagressor', 'cpfAgressor')->ignore($id)],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'telefone' => ['required', 'string', 'regex:/^\d{10,11}$/'],
        ];
    }

    protected function processarDados(Request $request, array $dados): array
    {
        $dados['cpf'] = preg_replace('/\D/', '', $dados['cpf']);
        $dados['telefone'] = preg_replace('/\D/', '', $dados['telefone']);
        return $dados;
    }

    protected function temVinculosAtivos($registro): bool
    {
        return $registro->medidas()->where('statusMedida', 'ativo')->exists()
            || $registro->ocorrencias()->where('statusAtendimento', 'andamento')->exists();
    }

    protected function dadosExtrasVisualizacao($registro): array
    {
        $occurrences = $registro->ocorrencias()->latest('dataOcorrencia')->paginate(8);

        return compact('occurrences');
    }
}