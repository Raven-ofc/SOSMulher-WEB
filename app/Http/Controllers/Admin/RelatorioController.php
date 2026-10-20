<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        $dados = $request->validate([
            'busca' => ['nullable', 'string', 'max:100'],
            'de' => ['nullable', 'date_format:Y-m-d'],
            'ate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:de'],
        ]);

        $query = TbOcorrencia::with(['vitima', 'agressor']);

        $query->whereExists(function ($sub) {
            $sub->select(DB::raw(1))
                ->from('relatorios_atendimento')
                ->whereColumn('relatorios_atendimento.ocorrencia_id', 'tbocorrencia.id');
        });

        $query->where('statusAtendimento', \App\Models\TbOcorrencia::STATUS_ENCERRADA);

        if ($v = $dados['busca'] ?? $request->query('search')) {
            $query->where(fn ($q) => $q->where('tipoOcorrencia', 'like', "%$v%")->orWhere('localOcorrencia', 'like', "%$v%"));
        }
        if ($v = $dados['de'] ?? $request->query('from')) {
            $query->whereDate('dataOcorrencia', '>=', $v);
        }
        if ($v = $dados['ate'] ?? $request->query('to')) {
            $query->whereDate('dataOcorrencia', '<=', $v);
        }

        return view('administracao.relatorio.lista', [
            'relatorios' => $query->latest('dataOcorrencia')->paginate(15)->withQueryString()
        ]);
    }

    public function show(Request $request, int $id)
    {
        $ocorrencia = TbOcorrencia::with(['vitima', 'agressor'])->findOrFail($id);
        $relatorio = DB::table('relatorios_atendimento')->where('ocorrencia_id', $id)->first();

        return view('administracao.relatorio.detalhes', compact('ocorrencia', 'relatorio'));
    }
}