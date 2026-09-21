<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbVitima;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\VitimaCadastradaMail;
use Illuminate\Support\Facades\Mail;

class VitimaController extends BaseCrudController
{
    protected string $modelo = TbVitima::class;
    protected string $visualizacaoBase = 'administracao.vitima';
    protected string $rotaBase = 'admin.vitimas';
    protected array $carregarRelacionamentos = ['telefones'];

    protected function colunaStatus(): string
    {
        return 'statusVitima';
    }

    protected function tabelaAuditoria(): string
    {
        return 'tbvitima';
    }

    protected function colunasBusca(): array
    {
        return ['nomeVitima', 'cpfVitima'];
    }

    protected function mapearDados(array $dados): array
    {
        return [
            'nomeVitima' => $dados['nome'],
            'cpfVitima' => $dados['cpf'],
            'dataNascimentoVitima' => $dados['data_nascimento'],
            'emailVitima' => $dados['email'],
            'senhaVitima' => Hash::make($dados['senha_temporaria']),
        ];
    }

    protected function variavelLista(): string
    {
        return 'vitimas';
    }

    protected function variavelSingular(): string
    {
        return 'vitima';
    }

    protected function ordenacaoPadrao(): array
    {
        return ['nomeVitima', 'asc'];
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
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbvitima', 'cpfVitima')],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
        ];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbvitima', 'cpfVitima')->ignore($id)],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')->ignore($id)],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
        ];
    }

    protected function processarDados(Request $request, array $dados): array
    {
        // Formata o CPF
        $cpf = preg_replace('/\D/', '', $dados['cpf']);
        $dados['cpf'] = substr($cpf, 0, 3) . '.' .
            substr($cpf, 3, 3) . '.' .
            substr($cpf, 6, 3) . '-' .
            substr($cpf, 9, 2);

        // Formata o telefone
        $telefone = preg_replace('/\D/', '', $dados['telefone']);

        if (strlen($telefone) === 11) {
            $dados['telefone'] = '(' . substr($telefone, 0, 2) . ') ' .
                substr($telefone, 2, 5) . '-' .
                substr($telefone, 7, 4);
        } elseif (strlen($telefone) === 10) {
            $dados['telefone'] = '(' . substr($telefone, 0, 2) . ') ' .
                substr($telefone, 2, 4) . '-' .
                substr($telefone, 6, 4);
        }

        if (!$request->route('id') && empty($dados['senha_temporaria'])) {
            $dados['senha_temporaria'] = Str::random(12);
        }
        return $dados;
    }

    protected function aposSalvar($registro, array $dados, ?int $id): void
    {
        $telefone = $registro->telefones()->orderBy('id')->first();
        if ($telefone) {
            $telefone->update(['numeroTelefoneVitima' => $dados['telefone']]);
        } else {
            $registro->telefones()->create(['numeroTelefoneVitima' => $dados['telefone']]);
        }
        if (!$id) {
            Mail::to($registro->emailVitima)->send(
                new VitimaCadastradaMail(
                    $registro->nomeVitima,
                    $dados['senha_temporaria']
                )
            );
        }
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
