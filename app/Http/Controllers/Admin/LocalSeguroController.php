<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbSolicitacao;
use App\Models\TbVitima;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LocalSeguroController extends \App\Http\Controllers\Controller
{
    public function index()
    {
        return view('administracao.local-seguro.lista', [
            'solicitacoes' => TbSolicitacao::with('vitima')
                ->where('statusSolicitacao', 'pendente')
                ->oldest()
                ->paginate(12)
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

    public function criar()
    {
        return view('administracao.local-seguro.formulario', [
            'vitimas' => TbVitima::where('statusVitima', 'ativo')->orderBy('nomeVitima')->get()
        ]);
    }

    public function salvar(Request $request)
    {
        $dados = $request->validate([
            'vitima_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'rotulo' => ['required', 'string', 'max:100'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:10'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'regex:/^[A-Za-z]{2}$/'],
            'cep' => ['required', 'regex:/^\d{5}-?\d{3}$/'],
            'complemento' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $registro = TbSolicitacao::create([
            'idVitima' => $dados['vitima_id'],
            'tipoSolicitacao' => $dados['rotulo'],
            'logradouroSolicitacao' => $dados['logradouro'],
            'numLogradouroSolicitacao' => $dados['numero'],
            'bairroSolicitacao' => $dados['bairro'],
            'cidadeSolicitacao' => $dados['cidade'],
            'ufSolicitacao' => strtoupper($dados['uf']),
            'cepSolicitacao' => $dados['cep'],
            'complementoSolicitacao' => $dados['complemento'] ?? null,
            'latitudeSolicitacao' => $dados['latitude'],
            'longitudeSolicitacao' => $dados['longitude'],
            'statusSolicitacao' => 'pendente',
            'dataSolicitacao' => today(),
        ]);
        AdminAudit::record('tbsolicitacao', $registro->id, 'criacao');

        return redirect()->route('admin.vitimas.solicitacoes')
            ->with('status', 'Solicitação registrada para análise.');
    }

    public function decidir(Request $request, int $id)
    {
        $dados = $request->validate([
            'decisao' => ['required', Rule::in(['aprovado', 'reprovado'])],
        ]);

        DB::transaction(function () use ($dados, $id, $request) {
            $registro = TbSolicitacao::lockForUpdate()->findOrFail($id);
            abort_unless($registro->statusSolicitacao === 'pendente', 409, 'Solicitação já analisada.');
            $registro->forceFill([
                'statusSolicitacao' => $dados['decisao'],
                'dataAnalise' => today(),
                'analisadoPor' => $request->user()->id,
            ])->save();
            AdminAudit::record('tbsolicitacao', $id, $dados['decisao']);
        });

        return back()->with('status', 'Análise registrada.');
    }

    public function remover(int $id)
    {
        DB::transaction(function () use ($id) {
            $registro = TbSolicitacao::lockForUpdate()->findOrFail($id);
            abort_unless($registro->statusSolicitacao === 'aprovado' && !$registro->removidoEm, 409);
            $registro->forceFill(['removidoEm' => now()])->save();
            AdminAudit::record('tbsolicitacao', $id, 'remocao');
        });

        return back()->with('status', 'Local removido da lista de locais seguros.');
    }
}