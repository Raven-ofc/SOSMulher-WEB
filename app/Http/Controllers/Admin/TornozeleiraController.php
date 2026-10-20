<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbTornozeleira;
use Illuminate\Validation\Rule;

/**
 * Lista, detalhes e edição de tornozeleiras.
 * Uma tornozeleira NOVA é cadastrada e atribuída ao agressor no fluxo unificado (CadastroController).
 */
class TornozeleiraController extends BaseCrudController
{
    public const STATUS = ['Disponível', 'Ativa', 'Inativa', 'Manutenção'];

    protected string $modelo = TbTornozeleira::class;
    protected string $visualizacaoBase = 'administracao.tornozeleira';
    protected string $rotaBase = 'admin.tornozeleiras';
    protected array $carregarRelacionamentos = ['agressor'];

    protected function colunaStatus(): string
    {
        return 'statusTornozeleira';
    }

    protected function statusPermitidos(): array
    {
        return self::STATUS;
    }

    protected function colunasBusca(): array
    {
        return ['numeroSerieTornozeleira'];
    }

    protected function mapearDados(array $dados): array
    {
        return [
            'numeroSerieTornozeleira' => $dados['numeroSerie'],
            'statusTornozeleira' => $dados['status'],
            'dataInstalacaoTornozeleira' => $dados['dataInstalacao'],
            'bateriaTornozeleira' => $dados['bateria'],
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
            'status' => ['nullable', Rule::in(self::STATUS)],
        ];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'numeroSerie' => [
                'required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\-_.]+$/',
                Rule::unique('tbtornozeleira', 'numeroSerieTornozeleira')->ignore($id),
            ],
            'status' => ['required', 'string', Rule::in(self::STATUS)],
            'dataInstalacao' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'bateria' => ['required', 'integer', 'between:0,100'],
        ];
    }

    protected function temVinculosAtivos($registro): bool
    {
        return $registro->agressor()->exists();
    }
}
