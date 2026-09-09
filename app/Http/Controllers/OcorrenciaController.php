<?php

namespace App\Http\Controllers;

use App\Models\tbagressor;
use App\Models\tbocorrencia;
use App\Models\tbvitima;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OcorrenciaController extends Controller
{
    public function index(Request $request)
    {
        $dados = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:from'],
            'status' => ['nullable', Rule::in(['andamento', 'realizado'])],
        ]);
        $query = tbocorrencia::with(['vitima', 'agressor']);
        if ($v = $dados['search'] ?? null) {
            $query->where(fn ($query) => $query->where('tipoOcorrencia', 'like', "%$v%")->orWhere('localOcorrencia', 'like', "%$v%"));
        }
        if ($v = $dados['from'] ?? null) {
            $query->whereDate('dataOcorrencia', '>=', $v);
        }
        if ($v = $dados['to'] ?? null) {
            $query->whereDate('dataOcorrencia', '<=', $v);
        }
        if ($v = $dados['status'] ?? null) {
            $query->where('statusAtendimento', $v);
        }

        return view('administracao.ocorrencia.lista', ['records' => $query->latest('dataOcorrencia')->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('administracao.ocorrencia.formulario', [
            'victims' => tbvitima::where('statusVitima', 'ativo')->orderBy('nomeVitima')->get(),
            'aggressors' => tbagressor::where('statusAgressor', 'ativo')->orderBy('nomeAgressor')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'victim_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'aggressor_id' => ['required', Rule::exists('tbagressor', 'id')->where('statusAgressor', 'ativo')],
            'type' => ['required', 'string', 'max:100'], 'severity' => ['required', Rule::in(['baixa', 'media', 'alta'])],
            'occurred_at' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'location' => ['required', 'string', 'max:255'], 'district' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:10000'],
        ], [
            'occurred_at.before_or_equal' => 'A data e hora da ocorrência não podem estar no futuro. Informe quando ela aconteceu.',
            'occurred_at.date_format' => 'Informe uma data e hora válidas para a ocorrência.',
            'occurred_at.required' => 'Informe a data e hora da ocorrência.',
        ]);
        $case = DB::transaction(function () use ($dados) {
            $case = new tbocorrencia;
            $case->forceFill([
                'idVitima' => $dados['victim_id'],
                'idAgressor' => $dados['aggressor_id'],
                'tipoOcorrencia' => $dados['type'],
                'gravidadeOcorrencia' => $dados['severity'],
                'dataOcorrencia' => substr($dados['occurred_at'], 0, 10),
                'dataHoraOcorrencia' => str_replace('T', ' ', $dados['occurred_at']),
                'localOcorrencia' => $dados['location'],
                'bairroOcorrencia' => $dados['district'],
                'descricaoOcorrencia' => $dados['description'] ?? null,
                'statusAtendimento' => 'andamento',
            ])->save();
            AdminAudit::record('tbocorrencia', $case->id, 'create');

            return $case;
        });

        return redirect()->route('admin.occurrences.show', $case->id)->with('status', 'Ocorrência registrada.');
    }

    public function show(Request $request, int $id)
    {
        $case = tbocorrencia::with(['vitima', 'agressor'])->findOrFail($id);
        $report = DB::table('relatorios_atendimento')->where('ocorrencia_id', $id)->first();

        return view('administracao.ocorrencia.detalhes', compact('case', 'report'));
    }

    public function report(int $id)
    {
        $case = tbocorrencia::findOrFail($id);
        abort_if($case->statusAtendimento === 'realizado', 409, 'Atendimento já finalizado.');

        return view('administracao.ocorrencia.relatorio', compact('case'));
    }

    public function finish(Request $request, int $id)
    {
        $dados = $request->validate([
            'start' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'end' => ['required', 'date_format:Y-m-d\TH:i', 'after_or_equal:start', 'before_or_equal:now'],
            'report' => ['required', 'string', 'min:20', 'max:30000'],
        ]);
        DB::transaction(function () use ($id, $dados, $request) {
            $case = tbocorrencia::lockForUpdate()->findOrFail($id);
            abort_if($case->statusAtendimento === 'realizado', 409, 'Atendimento já finalizado.');
            if (substr($dados['start'], 0, 10) < $case->dataOcorrencia) {
                throw ValidationException::withMessages(['start' => 'O atendimento não pode começar antes da data da ocorrência.']);
            }
            DB::table('relatorios_atendimento')->insert([
                'ocorrencia_id' => $id,
                'user_id' => $request->user()->id,
                'inicio' => str_replace('T', ' ', $dados['start']),
                'fim' => str_replace('T', ' ', $dados['end']),
                'relato' => $dados['report'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $case->forceFill(['statusAtendimento' => 'realizado'])->save();
            AdminAudit::record('tbocorrencia', $id, 'finish');
        });

        return redirect()->route('admin.reports.show', $id)->with('status', 'Atendimento finalizado e relatório salvo.');
    }
}
