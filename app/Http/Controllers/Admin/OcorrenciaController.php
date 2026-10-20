<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbAgressor;
use App\Models\TbOcorrencia;
use App\Models\TbVitima;
use App\Support\AdminAudit;
use App\Support\Formatos;
use App\Support\Regiao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OcorrenciaController extends Controller
{
    public const TIPOS = [
        'Descumprimento de Medida Protetiva',
        'Violação de Perímetro (Tornozeleira)',
        'Ameaça',
        'Agressão Física / Lesão Corporal',
        'Perseguição (Stalking)',
        'Falha Técnica de Equipamento',
        'Alerta Improcedente',
    ];

    public function index(Request $request)
    {
        $dados = $request->validate([
            'busca' => ['nullable', 'string', 'max:100'],
            'de' => ['nullable', 'date_format:Y-m-d'],
            'ate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:de'],
            'status' => ['nullable', Rule::in(TbOcorrencia::statusValidos())],
            'gravidade' => ['nullable', Rule::in(['baixa', 'media', 'alta'])],
        ]);

        $query = TbOcorrencia::with(['vitima', 'agressor']);

        if ($v = $dados['busca'] ?? null) {
            $query->where(fn ($q) => $q
                ->where('tipoOcorrencia', 'like', "%$v%")
                ->orWhere('localOcorrencia', 'like', "%$v%")
                ->orWhere('bairroOcorrencia', 'like', "%$v%")
                ->orWhere('cep', 'like', "%$v%"));
        }
        if ($v = $dados['de'] ?? null) {
            $query->whereDate('dataOcorrencia', '>=', $v);
        }
        if ($v = $dados['ate'] ?? null) {
            $query->whereDate('dataOcorrencia', '<=', $v);
        }
        if ($v = $dados['status'] ?? null) {
            $query->where('statusAtendimento', $v);
        }
        if ($v = $dados['gravidade'] ?? null) {
            $query->where('gravidadeOcorrencia', $v);
        }

        // Abertas (vermelho) primeiro, depois em andamento (amarelo) e por fim encerradas (verde); dentro de cada grupo, as mais novas antes.
        $query->orderByRaw("CASE statusAtendimento WHEN 'aberta' THEN 0 WHEN 'andamento' THEN 1 ELSE 2 END")
            ->orderByDesc('dataHoraOcorrencia');

        return view('administracao.ocorrencia.lista', [
            'ocorrencias' => $query->paginate(15)->withQueryString(),
            'contagem' => TbOcorrencia::select('statusAtendimento', DB::raw('COUNT(*) as total'))
                ->groupBy('statusAtendimento')
                ->pluck('total', 'statusAtendimento'),
        ]);
    }

    public function create()
    {
        return $this->criar();
    }

    public function store(Request $request)
    {
        return $this->salvar($request);
    }

    public function show(int $id)
    {
        return $this->visualizar($id);
    }

    public function edit(int $id)
    {
        return $this->criar();
    }

    public function update(Request $request, int $id)
    {
        abort(405, 'Ocorrências não podem ser editadas. Use a finalização.');
    }

    public function destroy(int $id)
    {
        abort(405, 'Ocorrências não podem ser removidas.');
    }

    public function criar()
    {
        return view('administracao.ocorrencia.formulario', [
            'vitimas' => TbVitima::where('statusVitima', 'ativo')->orderBy('nomeVitima')->get(),
            'agressores' => TbAgressor::whereIn('statusAgressor', ['Monitorado', 'Foragido'])
                ->orderBy('nomeAgressor')
                ->get(),
            'tipos' => self::TIPOS,
        ]);
    }

    public function salvar(Request $request)
    {
        if ($request->filled('cep')) {
            $request->merge(['cep' => Formatos::cep($request->input('cep'))]);
        }

        $dados = $request->validate([
            'vitima_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'agressor_id' => ['required', Rule::exists('tbagressor', 'id')->whereIn('statusAgressor', ['Monitorado', 'Foragido'])],
            'tipo' => ['required', 'string', Rule::in(self::TIPOS)],
            'gravidade' => ['required', Rule::in(['baixa', 'media', 'alta'])],
            'data_hora' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'cep' => ['required', 'string', 'regex:/^\d{5}-\d{3}$/'],
            'rua' => ['required', 'string', 'max:200'],
            'numero' => ['required', 'string', 'max:50'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['nullable', 'string', 'max:100'],
            'uf' => ['nullable', 'string', 'size:2'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'descricao' => ['nullable', 'string', 'max:10000'],
        ], [
            'data_hora.before_or_equal' => 'A data e hora da ocorrência não podem estar no futuro.',
            'data_hora.date_format' => 'Informe uma data e hora válidas.',
            'data_hora.required' => 'Informe a data e hora da ocorrência.',
            'cep.regex' => 'Informe um CEP válido (00000-000).',
        ]);

        // Sem coordenadas da API de CEP, usa o centro do bairro (quando conhecido) para o mapa.
        $lat = $dados['latitude'] ?? null;
        $lng = $dados['longitude'] ?? null;
        if ($lat === null || $lng === null) {
            [$lat, $lng] = Regiao::centroDoBairro($dados['bairro']);
        }

        $ocorrencia = DB::transaction(function () use ($dados, $request, $lat, $lng) {
            $ocorrencia = new TbOcorrencia;
            $ocorrencia->forceFill([
                'idAutoridade' => $request->user()->id,
                'idVitima' => $dados['vitima_id'],
                'idAgressor' => $dados['agressor_id'],
                'tipoOcorrencia' => $dados['tipo'],
                'gravidadeOcorrencia' => $dados['gravidade'],
                'dataOcorrencia' => substr($dados['data_hora'], 0, 10),
                'dataHoraOcorrencia' => str_replace('T', ' ', $dados['data_hora']),
                'cep' => $dados['cep'],
                'localOcorrencia' => $dados['rua'] . ', ' . $dados['numero'],
                'bairroOcorrencia' => $dados['bairro'],
                'latitudeOcorrencia' => $lat,
                'longitudeOcorrencia' => $lng,
                'descricaoOcorrencia' => $dados['descricao'] ?? null,
                'statusAtendimento' => TbOcorrencia::STATUS_ABERTA,
            ])->save();
            AdminAudit::record('tbocorrencia', $ocorrencia->id, 'criacao');

            return $ocorrencia;
        });

        return redirect()->route('admin.ocorrencias.visualizar', $ocorrencia->id)
            ->with('status', 'Ocorrência registrada.');
    }

    public function visualizar(int $id)
    {
        $ocorrencia = TbOcorrencia::with(['vitima', 'agressor'])->findOrFail($id);
        $relatorio = DB::table('relatorios_atendimento')->where('ocorrencia_id', $id)->first();

        return view('administracao.ocorrencia.detalhes', compact('ocorrencia', 'relatorio'));
    }

    /** Aberta (vermelho) -> Em andamento (amarelo): a equipe assumiu o atendimento. */
    public function iniciar(int $id)
    {
        DB::transaction(function () use ($id) {
            $ocorrencia = TbOcorrencia::lockForUpdate()->findOrFail($id);
            abort_if($ocorrencia->estaEncerrada(), 409, 'Atendimento já finalizado.');

            if ($ocorrencia->statusAtendimento === TbOcorrencia::STATUS_ABERTA) {
                $ocorrencia->forceFill(['statusAtendimento' => TbOcorrencia::STATUS_ANDAMENTO])->save();
                AdminAudit::record('tbocorrencia', $ocorrencia->id, 'atendimento_iniciado');
            }
        });

        return redirect()->route('admin.ocorrencias.relatorio', $id)
            ->with('status', 'Atendimento iniciado. Registre o relatório ao finalizar.');
    }

    public function relatorio(int $id)
    {
        $ocorrencia = TbOcorrencia::findOrFail($id);
        abort_if($ocorrencia->estaEncerrada(), 409, 'Atendimento já finalizado.');

        return view('administracao.ocorrencia.relatorio', compact('ocorrencia'));
    }

    public function finalizar(Request $request, int $id)
    {
        $dados = $request->validate([
            'inicio' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'fim' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:inicio', 'before_or_equal:now'],
            'relato' => ['required', 'string', 'min:20', 'max:30000'],
        ]);

        DB::transaction(function () use ($id, $dados, $request) {
            $ocorrencia = TbOcorrencia::lockForUpdate()->findOrFail($id);
            abort_if($ocorrencia->estaEncerrada(), 409, 'Atendimento já finalizado.');
            if (substr($dados['inicio'], 0, 10) < $ocorrencia->dataOcorrencia) {
                throw ValidationException::withMessages(['inicio' => 'O atendimento não pode começar antes da data da ocorrência.']);
            }
            DB::table('relatorios_atendimento')->insert([
                'ocorrencia_id' => $id,
                'idAutoridade' => $request->user()->id,
                'inicio' => str_replace('T', ' ', $dados['inicio']),
                'fim' => str_replace('T', ' ', $dados['fim']),
                'relato' => $dados['relato'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $ocorrencia->forceFill(['statusAtendimento' => TbOcorrencia::STATUS_ENCERRADA])->save();
        });

        return redirect()->route('admin.relatorios.visualizar', $id)
            ->with('status', 'Atendimento finalizado e relatório salvo.');
    }
}
