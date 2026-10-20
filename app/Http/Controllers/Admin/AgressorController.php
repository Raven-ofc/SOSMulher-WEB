<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbOcorrencia;
use App\Models\TbAgressor;
use App\Support\Formatos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Lista, detalhes e edição de agressores.
 * O CADASTRO de um novo agressor (e a atribuição da tornozeleira) acontece somente
 * no fluxo unificado (CadastroController).
 */
class AgressorController extends BaseCrudController
{
    public const STATUS = ['Monitorado', 'Foragido', 'Preso', 'Inativo'];

    protected string $modelo = TbAgressor::class;
    protected string $visualizacaoBase = 'administracao.agressor';
    protected string $rotaBase = 'admin.agressores';
    protected array $carregarRelacionamentos = ['tornozeleira'];

    protected function colunaStatus(): string
    {
        return 'statusAgressor';
    }

    protected function statusPermitidos(): array
    {
        return self::STATUS;
    }

    protected function statusInativo(): string
    {
        return 'Inativo';
    }

    protected function colunasBusca(): array
    {
        return ['nomeAgressor', 'cpfAgressor'];
    }

    protected function mapearDados(array $dados): array
    {
        $mapeado = [
            'nomeAgressor' => $dados['nome'],
            'nomeMaeAgressor' => $dados['nome_mae'] ?? null,
            'nomePaiAgressor' => $dados['nome_pai'] ?? null,
            'telefoneAgressor' => $dados['telefone'],
            'emailAgressor' => $dados['email'] ?? null,
            'cepAgressor' => $dados['cep'],
            'logradouroAgressor' => $dados['rua'],
            'numLogradouroAgressor' => $dados['numero'],
            'bairroAgressor' => $dados['bairro'],
            'cidadeAgressor' => $dados['cidade'],
            'ufAgressor' => $dados['uf'],
            'complementoAgressor' => $dados['complemento'] ?? null,
        ];

        if (!empty($dados['imagemAgressor'])) {
            $mapeado['imagemAgressor'] = $dados['imagemAgressor'];
        }

        if (!empty($dados['latitude']) && !empty($dados['longitude'])) {
            $mapeado['latitudeAgressor'] = $dados['latitude'];
            $mapeado['longitudeAgressor'] = $dados['longitude'];
        }

        return $mapeado;
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
            'status' => ['nullable', Rule::in(self::STATUS)],
        ];
    }

    /** Mesmos campos do cadastro da vítima. O CPF e a data de nascimento não mudam após o cadastro. */
    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100', 'regex:/^\S+(\s+\S+)+$/u'],
            'foto' => ['nullable', 'image', 'max:5120'],
            'nome_mae' => ['required', 'string', 'max:100'],
            'nome_pai' => ['nullable', 'string', 'max:100'],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'email' => ['nullable', 'email', 'max:100'],
            'cep' => ['required', 'string', 'regex:/^\d{5}-\d{3}$/'],
            'rua' => ['required', 'string', 'max:150'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', Rule::in(Formatos::UFS)],
            'complemento' => ['nullable', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    protected function normalizarEntrada(array $dados, ?int $id = null): array
    {
        unset($dados['cpf'], $dados['data_nascimento'], $dados['idTornozeleira']);

        if (isset($dados['telefone'])) {
            $dados['telefone'] = Formatos::telefone($dados['telefone']);
        }
        if (isset($dados['cep'])) {
            $dados['cep'] = Formatos::cep($dados['cep']);
        }
        if (isset($dados['uf'])) {
            $dados['uf'] = strtoupper(trim($dados['uf']));
        }

        return $dados;
    }

    protected function processarDados(Request $request, array $dados, ?int $id = null): array
    {
        if ($request->hasFile('foto')) {
            $dados['imagemAgressor'] = $request->file('foto')->store('agressores', 'public');
        }

        return $dados;
    }

    protected function temVinculosAtivos($registro): bool
    {
        return $registro->medidas()->where('statusMedida', 'Ativa')->exists()
            || $registro->ocorrencias()->whereIn('statusAtendimento', [TbOcorrencia::STATUS_ABERTA, TbOcorrencia::STATUS_ANDAMENTO])->exists();
    }

    protected function dadosExtrasVisualizacao($registro): array
    {
        $occurrences = $registro->ocorrencias()->latest('dataOcorrencia')->paginate(8);

        return compact('occurrences');
    }
}
