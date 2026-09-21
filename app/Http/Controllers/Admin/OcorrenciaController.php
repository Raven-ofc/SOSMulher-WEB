<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbAgressor;
use App\Models\TbOcorrencia;
use App\Models\TbVitima;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class OcorrenciaController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $dados = $request->validate([
            'busca' => ['nullable', 'string', 'max:100'],
            'de' => ['nullable', 'date_format:Y-m-d'],
            'ate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:de'],
            'status' => ['nullable', Rule::in(['andamento', 'realizado'])],
        ]);

        $query = TbOcorrencia::with(['vitima', 'agressor']);

        if ($v = $dados['busca'] ?? $request->query('search')) {
            $query->where(fn ($q) => $q->where('tipoOcorrencia', 'like', "%$v%")->orWhere('localOcorrencia', 'like', "%$v%"));
        }
        if ($v = $dados['de'] ?? $request->query('from')) {
            $query->whereDate('dataOcorrencia', '>=', $v);
        }
        if ($v = $dados['ate'] ?? $request->query('to')) {
            $query->whereDate('dataOcorrencia', '<=', $v);
        }
        if ($v = $dados['status'] ?? null) {
            $query->where('statusAtendimento', $v);
        }

        return view('administracao.ocorrencia.lista', [
            'ocorrencias' => $query->latest('dataOcorrencia')->paginate(15)->withQueryString()
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
        // Ocorrências não podem ser editadas, apenas finalizadas
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
            'agressores' => TbAgressor::where('statusAgressor', 'ativo')->orderBy('nomeAgressor')->get(),
        ]);
    }

    public function salvar(Request $request)
    {
        $dados = $request->validate([
            'vitima_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'agressor_id' => ['required', Rule::exists('tbagressor', 'id')->where('statusAgressor', 'ativo')],
            'tipo' => ['required', 'string', 'max:100'],
            'gravidade' => ['required', Rule::in(['baixa', 'media', 'alta'])],
            'data_hora' => ['required', 'date_format:Y-m-d\TH:i', 'before_or_equal:now'],
            'local' => ['required', 'string', 'max:255'],
            'bairro' => ['required', 'string', 'max:100'],
            'descricao' => ['nullable', 'string', 'max:10000'],
        ], [
            'data_hora.before_or_equal' => 'A data e hora da ocorrência não podem estar no futuro.',
            'data_hora.date_format' => 'Informe uma data e hora válidas.',
            'data_hora.required' => 'Informe a data e hora da ocorrência.',
        ]);

        $ocorrencia = DB::transaction(function () use ($dados) {
            $ocorrencia = new TbOcorrencia;
            $ocorrencia->forceFill([
                'idVitima' => $dados['vitima_id'],
                'idAgressor' => $dados['agressor_id'],
                'tipoOcorrencia' => $dados['tipo'],
                'gravidadeOcorrencia' => $dados['gravidade'],
                'dataOcorrencia' => substr($dados['data_hora'], 0, 10),
                'dataHoraOcorrencia' => str_replace('T', ' ', $dados['data_hora']),
                'localOcorrencia' => $dados['local'],
                'bairroOcorrencia' => $dados['bairro'],
                'descricaoOcorrencia' => $dados['descricao'] ?? null,
                'statusAtendimento' => 'andamento',
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

    public function relatorio(int $id)
    {
        $ocorrencia = TbOcorrencia::findOrFail($id);
        abort_if($ocorrencia->statusAtendimento === 'realizado', 409, 'Atendimento já finalizado.');

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
            abort_if($ocorrencia->statusAtendimento === 'realizado', 409, 'Atendimento já finalizado.');
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
            $ocorrencia->forceFill(['statusAtendimento' => 'realizado'])->save();
        });

        return redirect()->route('admin.relatorios.visualizar', $id)
            ->with('status', 'Atendimento finalizado e relatório salvo.');
    }
}