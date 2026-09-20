<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbTornozeleira;
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

    protected function dadosExtrasCriacao(): array
    {
        return [
            'tornozeleiras' => TbTornozeleira::where('statusTornozeleira', 'Disponível')
                ->get(),
        ];
    }

    protected function dadosExtrasEdicao(): array
    {
        return [
            'tornozeleiras' => TbTornozeleira::where('statusTornozeleira', 'Disponível')
                ->get(),
        ];
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

            'logradouroAgressor' => $dados['logradouro'],
            'numLogradouroAgressor' => $dados['numLogradouro'],
            'bairroAgressor' => $dados['bairroAgressor'],
            'cidadeAgressor' => $dados['cidadeAgressor'],
            'ufAgressor' => $dados['ufAgressor'],
            'complementoAgressor' => $dados['complementoAgressor'],
            'idTornozeleira' => $dados['idTornozeleira'],
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

    protected function aposSalvar($registro, array $dados, ?int $id): void
    {
        $tornozeleira = TbTornozeleira::findOrFail($dados['idTornozeleira']);

        $tornozeleira->update([
            'statusTornozeleira' => 'Ativa',
        ]);
    }

    protected function regrasCriacao(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbagressor', 'cpfAgressor')],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],

            'logradouro' => ['required', 'string', 'max:150'],
            'numLogradouro' => ['required', 'string', 'max:20'],
            'bairroAgressor' => ['required', 'string', 'max:100'],
            'cidadeAgressor' => ['required', 'string', 'max:100'],
            'ufAgressor' => ['required', 'string', 'size:2'],
            'complementoAgressor' => ['nullable', 'string', 'max:100'],
            'idTornozeleira' => [
                'required',
                'exists:tbtornozeleira,id',
            ],

        ];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbagressor', 'cpfAgressor')->ignore($id)],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],

            'logradouro' => ['required', 'string', 'max:150'],
            'numLogradouro' => ['required', 'string', 'max:20'],
            'bairroAgressor' => ['required', 'string', 'max:100'],
            'cidadeAgressor' => ['required', 'string', 'max:100'],
            'ufAgressor' => ['required', 'string', 'size:2'],
            'complementoAgressor' => ['nullable', 'string', 'max:100'],

            'idTornozeleira' => [
                'required',
                'exists:tbtornozeleira,id',
            ],
        ];
    }

    protected function processarDados(Request $request, array $dados): array
    {
        $dados['cpf'] = preg_replace('/\D/', '', $dados['cpf']);
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
