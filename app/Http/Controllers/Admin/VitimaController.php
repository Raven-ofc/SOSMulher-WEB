<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbVitima;
use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\VitimaCadastradaMail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

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
        $mapeado = [
            'nomeVitima' => $dados['nome'],
            'emailVitima' => $dados['email'],
            'nomeMaeVitima' => $dados['nome_mae'] ?? null,
            'nomePaiVitima' => $dados['nome_pai'] ?? null,
        ];

        if (!empty($dados['imagemVitima'])) {
            $mapeado['imagemVitima'] = $dados['imagemVitima'];
        }

        if (array_key_exists('cpf', $dados)) {
            $mapeado['cpfVitima'] = $dados['cpf'];
        }

        if (array_key_exists('data_nascimento', $dados)) {
            $mapeado['dataNascimentoVitima'] = $dados['data_nascimento'];
        }

        if (!empty($dados['senha_temporaria'])) {
            $mapeado['senhaVitima'] = Hash::make($dados['senha_temporaria']);
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

    protected function regrasCriacao(): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['required', 'string', new Cpf, Rule::unique('tbvitima', 'cpfVitima')],
            'data_nascimento' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')],
            'telefone' => ['required', 'string'],
            'nome_mae' => ['nullable', 'string', 'max:100'],
            'nome_pai' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:5048'],
            'cep' => ['required', 'string'],
            'rua' => ['required', 'string', 'max:150'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'max:2'],
            'complemento' => ['nullable', 'string', 'max:100'],
        ];
    }

    protected function regrasAtualizacao(int $id = null): array
    {
        return [
            'nome' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')->ignore($id)],
            'telefone' => ['required', 'string'],
            'nome_mae' => ['nullable', 'string', 'max:100'],
            'nome_pai' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:5048'],
            'cep' => ['required', 'string'],
            'rua' => ['required', 'string', 'max:150'],
            'numero' => ['required', 'string', 'max:20'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'max:2'],
            'complemento' => ['nullable', 'string', 'max:100'],
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


    protected function processarDados(Request $request, array $dados): array
    {
        $ehAtualizacao = (bool) $request->route('id');

        if ($ehAtualizacao) {
            unset($dados['cpf'], $dados['data_nascimento'], $dados['senha_temporaria']);
        }

        if ($request->hasFile('foto')) {
            $caminho = $request->file('foto')->store('vitimas', 'public');
            $dados['imagemVitima'] = $caminho;
        }

        if (!$ehAtualizacao && !empty($dados['cpf'])) {
            $cpf = preg_replace('/\D/', '', $dados['cpf']);

            $dados['cpf'] = substr($cpf, 0, 3) . '.' .
                substr($cpf, 3, 3) . '.' .
                substr($cpf, 6, 3) . '-' .
                substr($cpf, 9, 2);
        }

        if (!empty($dados['telefone'])) {
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
        }

        if (!$ehAtualizacao && empty($dados['senha_temporaria'])) {
            $dados['senha_temporaria'] = Str::random(12);
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
            $telefone->update([
                'numeroTelefoneVitima' => $dados['telefone'],
            ]);
        } else {
            $registro->telefones()->create([
                'numeroTelefoneVitima' => $dados['telefone'],
            ]);
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

        if ($registro->endereco) {
            $registro->endereco()->update($dadosEndereco);
        } else {
            $registro->endereco()->create($dadosEndereco);
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
        return $registro->medidas()->where('statusMedida', 'ativo')->exists()
            || $registro->ocorrencias()->where('statusAtendimento', 'andamento')->exists();
    }

    protected function dadosExtrasVisualizacao($registro): array
    {
        $occurrences = $registro->ocorrencias()
            ->latest('dataOcorrencia')
            ->paginate(8);

        return compact('occurrences');
    }
}
