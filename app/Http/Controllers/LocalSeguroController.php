<?php

namespace App\Http\Controllers;

use App\Models\tbsolicitacao;
use App\Models\tbvitima;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LocalSeguroController extends Controller
{
    public function index()
    {
        return view('administracao.local-seguro.lista', ['records' => tbsolicitacao::with('vitima')->where('statusSolicitacao', 'pendente')->oldest()->paginate(12)]);
    }

    public function create()
    {
        return view('administracao.local-seguro.formulario', ['victims' => tbvitima::where('statusVitima', 'ativo')->orderBy('nomeVitima')->get()]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'victim_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'label' => ['required', 'string', 'max:100'], 'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:10'], 'district' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'], 'state' => ['required', 'regex:/^[A-Za-z]{2}$/'],
            'postal_code' => ['required', 'regex:/^\d{5}-?\d{3}$/'], 'complement' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'], 'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);
        DB::transaction(function () use ($dados) {
            $record = tbsolicitacao::create([
                'idVitima' => $dados['victim_id'],
                'tipoSolicitacao' => $dados['label'],
                'logradouroSolicitacao' => $dados['street'],
                'numLogradouroSolicitacao' => $dados['number'],
                'bairroSolicitacao' => $dados['district'],
                'cidadeSolicitacao' => $dados['city'],
                'ufSolicitacao' => strtoupper($dados['state']),
                'cepSolicitacao' => $dados['postal_code'],
                'complementoSolicitacao' => $dados['complement'] ?? null,
                'latitudeSolicitacao' => $dados['latitude'],
                'longitudeSolicitacao' => $dados['longitude'],
                'statusSolicitacao' => 'pendente',
                'dataSolicitacao' => today(),
            ]);
            AdminAudit::record('tbsolicitacao', $record->id, 'create');
        });

        return redirect()->route('admin.victims.requests')->with('status', 'Solicitação registrada para análise.');
    }

    public function decide(Request $request, int $id)
    {
        $dados = $request->validate(['decision' => ['required', Rule::in(['aprovado', 'reprovado'])]]);
        DB::transaction(function () use ($dados, $id, $request) {
            $record = tbsolicitacao::lockForUpdate()->findOrFail($id);
            abort_unless($record->statusSolicitacao === 'pendente', 409, 'Solicitação já analisada.');
            $record->forceFill([
                'statusSolicitacao' => $dados['decision'],
                'dataAnalise' => today(),
                'analisadoPor' => $request->user()->id,
            ])->save();
            AdminAudit::record('tbsolicitacao', $id, $dados['decision']);
        });

        return back()->with('status', 'Análise registrada.');
    }

    public function remove(int $id)
    {
        DB::transaction(function () use ($id) {
            $record = tbsolicitacao::lockForUpdate()->findOrFail($id);
            abort_unless($record->statusSolicitacao === 'aprovado' && ! $record->removidoEm, 409);
            $record->forceFill(['removidoEm' => now()])->save();
            AdminAudit::record('tbsolicitacao', $id, 'remove');
        });

        return back()->with('status', 'Local removido da lista de locais seguros.');
    }
}
