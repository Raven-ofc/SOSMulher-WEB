<?php

namespace App\Http\Controllers;

use App\Models\tbocorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RelatorioController extends Controller
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
        $query->where('statusAtendimento', 'realizado');
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

        return view('administracao.relatorio.lista', ['records' => $query->latest('dataOcorrencia')->paginate(15)->withQueryString()]);
    }

    public function show(Request $request, int $id)
    {
        $case = tbocorrencia::with(['vitima', 'agressor'])->findOrFail($id);
        $report = DB::table('relatorios_atendimento')->where('ocorrencia_id', $id)->first();

        return view('administracao.relatorio.detalhes', compact('case', 'report'));
    }
}
