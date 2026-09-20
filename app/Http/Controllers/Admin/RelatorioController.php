<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbOcorrencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RelatorioController extends \App\Http\Controllers\Controller
{
    public function index(Request $request)
    {
        $dados = $request->validate([
            'busca' => ['nullable', 'string', 'max:100'],
            'de' => ['nullable', 'date_format:Y-m-d'],
            'ate' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:de'],
            'status' => ['nullable', Rule::in(['andamento', 'realizado'])],
        ]);

        $query = TbOcorrencia::with(['vitima', 'agressor'])->where('statusAtendimento', 'realizado');

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

        return view('administracao.relatorio.lista', [
            'relatorios' => $query->latest('dataOcorrencia')->paginate(15)->withQueryString()
        ]);
    }

    public function show(int $id)
    {
        return $this->visualizar($id);
    }

    public function visualizar(int $id)
    {
        $ocorrencia = TbOcorrencia::with(['vitima', 'agressor'])->findOrFail($id);
        $relatorio = DB::table('relatorios_atendimento')->where('ocorrencia_id', $id)->first();

        return view('administracao.relatorio.detalhes', compact('ocorrencia', 'relatorio'));
    }
}