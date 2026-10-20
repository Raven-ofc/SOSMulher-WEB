<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\VitimaCadastradaMail;
use App\Models\TbAgressor;
use App\Models\TbMedida;
use App\Models\TbTornozeleira;
use App\Models\TbVitima;
use App\Rules\Cpf;
use App\Support\AdminAudit;
use App\Support\Formatos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Cadastro único, dividido em fases:
 *   1) vítima  >  2) agressor  >  3) tornozeleira  >  4) medida protetiva.
 *
 * Cada fase é validada no servidor antes de o usuário avançar (rota "validar") e, no envio final,
 * tudo é validado de novo e gravado numa única transação: ou entra tudo, ou não entra nada.
 */
class CadastroController extends Controller
{
    public const FASES = ['vitima', 'agressor', 'tornozeleira', 'medida'];

    public function index()
    {
        return view('administracao.cadastro.formulario', [
            'tornozeleirasDisponiveis' => TbTornozeleira::where('statusTornozeleira', 'Disponível')
                ->whereDoesntHave('agressor')
                ->orderBy('numeroSerieTornozeleira')
                ->get(),
            'tiposMedida' => TbMedida::TIPOS,
            'riscos' => TbMedida::RISCOS,
            'ufs' => Formatos::UFS,
        ]);
    }

    /** Valida somente uma fase (usado pelo botão "Próximo" via fetch). */
    public function validar(Request $request, string $fase): JsonResponse
    {
        abort_unless(in_array($fase, self::FASES, true), 404);

        $dados = $this->normalizar($request->all());

        $validator = validator($dados, $this->regras($fase, $dados), $this->mensagens());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Corrija os campos destacados para continuar.',
                'errors' => $validator->errors(),
            ], 422);
        }

        return response()->json(['ok' => true]);
    }

    public function salvar(Request $request)
    {
        $entrada = $this->normalizar($request->all());

        $regras = [];
        foreach (self::FASES as $fase) {
            $regras = array_merge($regras, $this->regras($fase, $entrada));
        }

        $validator = validator($entrada, $regras, $this->mensagens());

        if ($validator->fails()) {
            // withInput() mantém tudo o que foi digitado; a view reabre a primeira fase com erro.
            return back()->withErrors($validator)->withInput($request->except(['vitima.foto', 'agressor.foto']));
        }

        $dados = $validator->validated();

        try {
            $resultado = DB::transaction(fn () => $this->gravar($request, $dados));
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except(['vitima.foto', 'agressor.foto']));
        }

        /** @var TbMedida $medida */
        $medida = $resultado['medida'];
        $mensagem = 'Cadastro concluído: vítima, agressor, tornozeleira e medida protetiva ' . $medida->numeroFormatado() . ' registrados.';

        $retorno = redirect()->route('admin.medidas-protetivas.visualizar', $medida->id)->with('status', $mensagem);

        try {
            Mail::to($resultado['vitima']->emailVitima)->send(
                new VitimaCadastradaMail($resultado['vitima']->nomeVitima, $resultado['senha'])
            );
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de cadastro da vítima: ' . $e->getMessage());
            $retorno->with('aviso', 'O cadastro foi salvo, mas o e-mail de acesso da vítima não pôde ser enviado.');
        }

        return $retorno;
    }

    /* --------------------------------------------------------------------- */
    /* Gravação                                                              */
    /* --------------------------------------------------------------------- */

    private function gravar(Request $request, array $dados): array
    {
        $v = $dados['vitima'];
        $a = $dados['agressor'];
        $t = $dados['tornozeleira'];
        $m = $dados['medida'];

        // 1) Vítima -----------------------------------------------------------
        $senha = Str::random(12);

        $vitima = new TbVitima;
        $vitima->forceFill([
            'nomeVitima' => $v['nome'],
            'cpfVitima' => $v['cpf'],
            'emailVitima' => $v['email'],
            'senhaVitima' => Hash::make($senha),
            'dataNascimentoVitima' => $v['data_nascimento'],
            'nomeMaeVitima' => $v['nome_mae'],
            'nomePaiVitima' => $v['nome_pai'] ?? null,
            'imagemVitima' => $request->hasFile('vitima.foto') ? $request->file('vitima.foto')->store('vitimas', 'public') : null,
            'statusVitima' => 'ativo',
        ])->save();

        $vitima->telefones()->create(['numeroTelefoneVitima' => $v['telefone']]);
        $vitima->endereco()->create([
            'logradouroVitima' => $v['rua'],
            'numLogradouroVitima' => $v['numero'],
            'bairroVitima' => $v['bairro'],
            'cidadeVitima' => $v['cidade'],
            'ufVitima' => $v['uf'],
            'complementoVitima' => $v['complemento'] ?? null,
            'cepVitima' => $v['cep'],
            'latitudeVitima' => $v['latitude'] ?? null,
            'longitudeVitima' => $v['longitude'] ?? null,
        ]);

        // 2) Tornozeleira (criada/atribuída antes do agressor por causa da chave estrangeira) ----
        if (($t['modo'] ?? 'nova') === 'existente') {
            $tornozeleira = TbTornozeleira::lockForUpdate()->findOrFail($t['tornozeleira_id']);

            if ($tornozeleira->statusTornozeleira !== 'Disponível' || $tornozeleira->agressor()->exists()) {
                throw ValidationException::withMessages([
                    'tornozeleira.tornozeleira_id' => 'Esta tornozeleira acabou de ser utilizada. Escolha outra.',
                ]);
            }

            $tornozeleira->forceFill([
                'statusTornozeleira' => 'Ativa',
                'dataInstalacaoTornozeleira' => $t['dataInstalacao'] ?? now()->toDateString(),
            ])->save();
        } else {
            $tornozeleira = new TbTornozeleira;
            $tornozeleira->forceFill([
                'numeroSerieTornozeleira' => $t['numeroSerie'],
                'statusTornozeleira' => 'Ativa',
                'dataInstalacaoTornozeleira' => $t['dataInstalacao'],
                'bateriaTornozeleira' => 100,
            ])->save();
        }

        // 3) Agressor (já com a tornozeleira atribuída) ------------------------
        $agressor = new TbAgressor;
        $agressor->forceFill([
            'imagemAgressor' => $request->hasFile('agressor.foto') ? $request->file('agressor.foto')->store('agressores', 'public') : null,
            'nomeAgressor' => $a['nome'],
            'cpfAgressor' => $a['cpf'],
            'dataNascimentoAgressor' => $a['data_nascimento'],
            'nomeMaeAgressor' => $a['nome_mae'],
            'nomePaiAgressor' => $a['nome_pai'] ?? null,
            'telefoneAgressor' => $a['telefone'],
            'emailAgressor' => $a['email'] ?? null,
            'cepAgressor' => $a['cep'],
            'logradouroAgressor' => $a['rua'],
            'numLogradouroAgressor' => $a['numero'],
            'bairroAgressor' => $a['bairro'],
            'cidadeAgressor' => $a['cidade'],
            'ufAgressor' => $a['uf'],
            'complementoAgressor' => $a['complemento'] ?? null,
            'latitudeAgressor' => $a['latitude'] ?? null,
            'longitudeAgressor' => $a['longitude'] ?? null,
            'statusAgressor' => 'Monitorado',
            'idTornozeleira' => $tornozeleira->id,
        ])->save();

        // 4) Medida protetiva ---------------------------------------------------
        $medida = new TbMedida;
        $medida->forceFill([
            'idVitima' => $vitima->id,
            'idAgressor' => $agressor->id,
            'idAutoridade' => $request->user()?->id,
            'dataInicioMedida' => $m['data_inicio'],
            'dataFimMedida' => empty($m['indeterminada']) ? ($m['data_fim'] ?? null) : null,
            'distanciaMedida' => $m['distancia'],
            'raioAlertaMedida' => $m['raio'],
            'tiposMedida' => array_values($m['tipos']),
            'nivelRiscoMedida' => $m['nivel_risco'],
            'numeroProcessoMedida' => $m['numero_processo'] ?? null,
            'varaMedida' => $m['vara'] ?? null,
            'descricaoMedida' => $m['descricao'],
            'observacoesMedida' => $m['observacoes'] ?? null,
            'statusMedida' => 'Ativa',
        ])->save();

        AdminAudit::record('tbvitima', $vitima->id, 'criacao');
        AdminAudit::record('tbtornozeleira', $tornozeleira->id, 'atribuicao');
        AdminAudit::record('tbagressor', $agressor->id, 'criacao');
        AdminAudit::record('tbmedida', $medida->id, 'criacao');

        return compact('vitima', 'agressor', 'tornozeleira', 'medida', 'senha');
    }

    /* --------------------------------------------------------------------- */
    /* Regras                                                                */
    /* --------------------------------------------------------------------- */

    /** Regras de uma fase, já com o prefixo (ex.: "vitima.nome"). */
    private function regras(string $fase, array $dados): array
    {
        return match ($fase) {
            'vitima' => array_merge(
                $this->regrasPessoa('vitima', 'tbvitima', 'cpfVitima', true),
                ['vitima.email' => ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')]]
            ),

            // array_merge (e não "+") para que a regra de CPF do agressor, com "different", substitua a genérica.
            'agressor' => array_merge(
                $this->regrasPessoa('agressor', 'tbagressor', 'cpfAgressor', false),
                [
                    'agressor.email' => ['nullable', 'email', 'max:100'],
                    'agressor.cpf' => [
                        'required', 'string', new Cpf,
                        Rule::unique('tbagressor', 'cpfAgressor'),
                        'different:vitima.cpf',
                    ],
                ]
            ),

            'tornozeleira' => $this->regrasTornozeleira($dados),

            'medida' => $this->regrasMedida(),
        };
    }

    private function regrasPessoa(string $p, string $tabela, string $colunaCpf, bool $comEmail): array
    {
        return [
            "$p.foto" => ['nullable', 'image', 'max:5120'],
            "$p.nome" => ['required', 'string', 'max:100', 'regex:/^\S+(\s+\S+)+$/u'],
            "$p.cpf" => ['required', 'string', new Cpf, Rule::unique($tabela, $colunaCpf)],
            "$p.data_nascimento" => ['required', 'date_format:Y-m-d', 'after:1900-01-01', 'before_or_equal:today'],
            "$p.nome_mae" => ['required', 'string', 'max:100'],
            "$p.nome_pai" => ['nullable', 'string', 'max:100'],
            "$p.telefone" => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            "$p.cep" => ['required', 'string', 'regex:/^\d{5}-\d{3}$/'],
            "$p.rua" => ['required', 'string', 'max:150'],
            "$p.numero" => ['required', 'string', 'max:20'],
            "$p.bairro" => ['required', 'string', 'max:100'],
            "$p.cidade" => ['required', 'string', 'max:100'],
            "$p.uf" => ['required', 'string', Rule::in(Formatos::UFS)],
            "$p.complemento" => ['nullable', 'string', 'max:100'],
            "$p.latitude" => ['nullable', 'numeric', 'between:-90,90'],
            "$p.longitude" => ['nullable', 'numeric', 'between:-180,180'],
        ];
    }

    private function regrasTornozeleira(array $dados): array
    {
        $modo = $dados['tornozeleira']['modo'] ?? 'nova';

        $regras = ['tornozeleira.modo' => ['required', Rule::in(['nova', 'existente'])]];

        if ($modo === 'existente') {
            $regras['tornozeleira.tornozeleira_id'] = [
                'required',
                Rule::exists('tbtornozeleira', 'id')->where('statusTornozeleira', 'Disponível'),
                Rule::unique('tbagressor', 'idTornozeleira'),
            ];
            $regras['tornozeleira.dataInstalacao'] = ['required', 'date_format:Y-m-d', 'before_or_equal:today'];
        } else {
            $regras['tornozeleira.numeroSerie'] = [
                'required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\-_.]+$/',
                Rule::unique('tbtornozeleira', 'numeroSerieTornozeleira'),
            ];
            $regras['tornozeleira.dataInstalacao'] = ['required', 'date_format:Y-m-d', 'before_or_equal:today'];
        }

        return $regras;
    }

    private function regrasMedida(): array
    {
        return [
            'medida.data_inicio' => ['required', 'date_format:Y-m-d', 'after:2000-01-01', 'before_or_equal:today'],
            'medida.indeterminada' => ['nullable', 'boolean'],
            'medida.data_fim' => ['nullable', 'date_format:Y-m-d', 'after:medida.data_inicio'],
            'medida.tipos' => ['required', 'array', 'min:1'],
            'medida.tipos.*' => ['string', Rule::in(array_keys(TbMedida::TIPOS))],
            'medida.nivel_risco' => ['required', Rule::in(array_keys(TbMedida::RISCOS))],
            'medida.distancia' => ['required', 'integer', 'min:10', 'max:5000'],
            'medida.raio' => ['required', 'integer', 'gte:medida.distancia', 'max:10000'],
            'medida.numero_processo' => ['nullable', 'string', 'regex:/^\d{7}-\d{2}\.\d{4}\.\d\.\d{2}\.\d{4}$/'],
            'medida.vara' => ['nullable', 'string', 'max:100'],
            'medida.descricao' => ['required', 'string', 'min:10', 'max:255'],
            'medida.observacoes' => ['nullable', 'string', 'max:2000'],
        ];
    }

    private function mensagens(): array
    {
        return [
            'vitima.nome.regex' => 'Informe o nome e o sobrenome.',
            'agressor.nome.regex' => 'Informe o nome e o sobrenome.',
            '*.telefone.regex' => 'Informe um telefone válido, com DDD.',
            '*.cep.regex' => 'Informe um CEP válido (00000-000).',
            'agressor.cpf.different' => 'O CPF do agressor não pode ser igual ao da vítima.',
            'tornozeleira.numeroSerie.regex' => 'Use apenas letras, números, hífen, ponto ou sublinhado.',
            'medida.numero_processo.regex' => 'Use o formato do CNJ: 0000000-00.0000.0.00.0000.',
            'medida.data_fim.after' => 'O término deve ser posterior ao início da medida.',
            'medida.raio.gte' => 'O raio de alerta deve ser maior ou igual à distância mínima.',
            'medida.tipos.required' => 'Selecione ao menos um tipo de medida.',
            'medida.tipos.min' => 'Selecione ao menos um tipo de medida.',
            'medida.descricao.min' => 'Descreva a medida com ao menos 10 caracteres.',
            'tornozeleira.tornozeleira_id.unique' => 'Esta tornozeleira já está vinculada a outro agressor.',
        ];
    }

    /* --------------------------------------------------------------------- */
    /* Normalização                                                          */
    /* --------------------------------------------------------------------- */

    /** Padroniza CPF, telefone e CEP antes de validar/gravar. */
    private function normalizar(array $entrada): array
    {
        foreach (['vitima', 'agressor'] as $pessoa) {
            if (!isset($entrada[$pessoa]) || !is_array($entrada[$pessoa])) {
                $entrada[$pessoa] = [];
            }

            $p = &$entrada[$pessoa];

            foreach (['cpf' => 'cpf', 'telefone' => 'telefone', 'cep' => 'cep'] as $campo => $metodo) {
                if (isset($p[$campo]) && is_string($p[$campo])) {
                    $p[$campo] = Formatos::$metodo(trim($p[$campo]));
                }
            }

            if (isset($p['uf']) && is_string($p['uf'])) {
                $p['uf'] = Str::upper(trim($p['uf']));
            }

            foreach ($p as $campo => $valor) {
                if ($valor === '') {
                    $p[$campo] = null;
                }
            }

            unset($p);
        }

        foreach (['tornozeleira', 'medida'] as $grupo) {
            if (!isset($entrada[$grupo]) || !is_array($entrada[$grupo])) {
                $entrada[$grupo] = [];
            }
            foreach ($entrada[$grupo] as $campo => $valor) {
                if ($valor === '') {
                    $entrada[$grupo][$campo] = null;
                }
            }
        }

        if (!empty($entrada['medida']['indeterminada'])) {
            $entrada['medida']['indeterminada'] = true;
            $entrada['medida']['data_fim'] = null;
        }

        return $entrada;
    }
}
