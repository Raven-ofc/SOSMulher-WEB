<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbTornozeleira;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TornozeleiraController extends BaseCrudController
{
    protected string $modelo = TbTornozeleira::class;
    protected string $visualizacaoBase = 'administracao.tornozeleira';
    protected string $rotaBase = 'admin.tornozeleiras';

    protected function colunaStatus(): string
    {
        return 'statusTornozeleira';
    }

    protected function statusInicial(): string
    {
        return 'Disponível';
    }

    protected function colunasBusca(): array
    {
        return ['numeroSerieTornozeleira'];
    }

    protected function mapearDados(array $dados): array
    {
        return [
            'numeroSerieTornozeleira' => $dados['numeroSerie'],
            'dataInstalacaoTornozeleira' => $dados['dataInstalacao'],
            'bateriaTornozeleira' => 100,
        ];
    }

    protected function variavelLista(): string
    {
        return 'tornozeleiras';
    }

    protected function variavelSingular(): string
    {
        return 'tornozeleira';
    }

    protected function ordenacaoPadrao(): array
    {
        return ['numeroSerieTornozeleira', 'asc'];
    }

    protected function regrasFiltros(): array
    {
        return [
            'busca' => ['nullable', 'string', 'max:100'],
            'status' => [
                'nullable',
                Rule::in(['Disponivel', 'Ativa', 'Inativa', 'Manutenção']),
            ],
        ];
    }

    protected function regrasCriacao(): array
    {
        return [
            'numeroSerie' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tbtornozeleira', 'numeroSerieTornozeleira'),
            ],
            'dataInstalacao' => [
                'required',
                'date',
            ],
        ];
    }
    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'numeroSerie' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tbtornozeleira', 'numeroSerieTornozeleira')
                    ->ignore($id),
            ],
            'status' => [
                'required',
                'string',
                Rule::in(['Disponivel', 'Ativa', 'Inativa', 'Manutenção']),
            ],
            'dataInstalacao' => [
                'required',
                'date',
            ],
            'bateria' => [
                'required',
                'integer',
                'between:0,100',
            ],
        ];
    }

    protected function temVinculosAtivos($registro): bool
    {
        return $registro->agressor()->exists();
    }
}
