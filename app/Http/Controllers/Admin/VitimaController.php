<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbOcorrencia;
use App\Models\TbVitima;
use App\Support\Formatos;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\VitimaCadastradaMail;
use Illuminate\Support\Facades\Mail;

/**
 * Lista, detalhes e edição de vítimas.
 * O CADASTRO de uma nova vítima acontece somente no fluxo unificado (CadastroController).
 */
class VitimaController extends BaseCrudController
{
    protected string $modelo = TbVitima::class;
    protected string $visualizacaoBase = 'administracao.vitima';
    protected string $rotaBase = 'admin.vitimas';
    protected array $carregarRelacionamentos = ['telefones', 'endereco'];

    protected function colunaStatus(): string
    {
        return 'statusVitima';
    }

    protected function colunasBusca(): array
    {
        return ['nomeVitima', 'cpfVitima'];
    }

    protected function mapearDados(array $dados): array
    {
        $mapeado = [
            'nomeVitima' => $dados['nome'],
            'emailVitima' => $dados['email'],
            'nomeMaeVitima' => $dados['nome_mae'] ?? null,
            'nomePaiVitima' => $dados['nome_pai'] ?? null,
        ];

        if (!empty($dados['imagemVitima'])) {
            $mapeado['imagemVitima'] = $dados['imagemVitima'];
        }

        return $mapeado;
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

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100', 'regex:/^\S+(\s+\S+)+$/u'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')->ignore($id)],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'nome_mae' => ['nullable', 'string', 'max:100'],
            'nome_pai' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:5120'],
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
    private function buscarCoord(string $dadosEndereco): ?array
    {
        try {
            $resposta = Http::withHeaders([
                'User-Agent' => 'SOSMulher/1.0',
            ])->timeout(5)->get(config('services.nominatim.url') . '/search', [
                'q' => $dadosEndereco,
                'format' => 'jsonv2',
                'limit' => 1,
                'countrycodes' => 'br',
            ]);
            if (!$resposta->successful()) {
                throw ValidationException::withMessages([
                    'rua' => 'Não foi possível validar o endereço. Tente novamente.',
                ]);
            }
            $resultado = $resposta->json();

            if (empty($resultado) || !isset($resultado[0]['lat'], $resultado[0]['lon'])) {
                throw ValidationException::withMessages([
                    'rua' => 'O endereço cadastrado não foi encontrado. Verifique os dados.',
                ]);
            }
            return [
                'latitudeVitima' => (float) $resultado[0]['lat'],
                'longitudeVitima' => (float) $resultado[0]['lon'],
            ];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw ValidationException::withMessages([
                'rua' => 'Não foi possível validar o endereço. Tente novamente.',
            ]);
        }
    }


    /** CPF e data de nascimento não mudam depois do cadastro; por isso são descartados na edição. */
    protected function normalizarEntrada(array $dados, ?int $id = null): array
    {
        unset($dados['cpf'], $dados['data_nascimento'], $dados['senha_temporaria']);

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
            $dados['imagemVitima'] = $request->file('foto')->store('vitimas', 'public');
        }

        $enderecoCompleto = implode(',', array_filter([
            $dados['rua'] ?? null,
            $dados['numero'] ?? null,
            $dados['bairro'] ?? null,
            $dados['cidade'] ?? null,
            $dados['uf'] ?? null,
            $dados['cep'] ?? null,
            'Brasil',
        ]));

        $coordenadas = $this->buscarCoord($enderecoCompleto);

        $dados['latitudeVitima'] = $coordenadas['latitudeVitima'];
        $dados['longitudeVitima'] = $coordenadas['longitudeVitima'];

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

        $dadosEndereco = [
            'logradouroVitima' => $dados['rua'],
            'numLogradouroVitima' => $dados['numero'],
            'bairroVitima' => $dados['bairro'],
            'cidadeVitima' => $dados['cidade'],
            'ufVitima' => $dados['uf'],
            'complementoVitima' => $dados['complemento'] ?? null,
            'cepVitima' => $dados['cep'],
            'latitudeVitima' => $dados['latitudeVitima'],
            'longitudeVitima' => $dados['longitudeVitima'],
        ];

        // Só atualiza as coordenadas quando a API de CEP devolveu novas (evita perder as antigas).
        if (!empty($dados['latitude']) && !empty($dados['longitude'])) {
            $dadosEndereco['latitudeVitima'] = $dados['latitude'];
            $dadosEndereco['longitudeVitima'] = $dados['longitude'];
        }

        if ($registro->endereco) {
            $registro->endereco()->update($dadosEndereco);
        } else {
            $registro->endereco()->create($dadosEndereco);
        }
    }

    public function recuperarSenhaAPI(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $vitima = TbVitima::where('emailVitima', '=', $request->email)->first();

        if (!$vitima) {
            return response()->json([
                'message' => 'Seu cadastro não foi encontrado, verifique se digitou seu email corretamente.',
            ], 404);
        }

        $senhaTemporaria = Str::random(12);

        $vitima->senhaVitima = Hash::make($senhaTemporaria);
        $vitima->save();

        Mail::to($vitima->emailVitima)->send(
            new VitimaCadastradaMail(
                $vitima->nomeVitima,
                $senhaTemporaria
            )
        );

        return response()->json([
            'message'=> 'Verifique se sua senha chegou em seu email.'
        ],200);
    }

    protected function temVinculosAtivos($registro): bool
    {
        return $registro->medidas()->where('statusMedida', 'Ativa')->exists()
            || $registro->ocorrencias()->whereIn('statusAtendimento', [TbOcorrencia::STATUS_ABERTA, TbOcorrencia::STATUS_ANDAMENTO])->exists();
    }

    protected function dadosExtrasVisualizacao($registro): array
    {
        $occurrences = $registro->ocorrencias()
            ->latest('dataOcorrencia')
            ->paginate(8);

        return compact('occurrences');
    }
}
