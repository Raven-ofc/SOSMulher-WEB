<?php

namespace App\Http\Controllers;

use App\Models\tbsolicitacao;
use App\Models\tbvitima;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SafePlaceController extends Controller
{
    public function index()
    {
        return view('admin.places.index', ['records' => tbsolicitacao::with('vitima')->where('statusSolicitacao', 'pendente')->oldest()->paginate(12)]);
    }

    public function create()
    {
        return view('admin.places.form', ['victims' => tbvitima::where('statusVitima', 'ativo')->orderBy('nomeVitima')->get()]);
    }

    public function store(Request $r)
    {
        $d = $r->validate([
            'victim_id' => ['required', Rule::exists('tbvitima', 'id')->where('statusVitima', 'ativo')],
            'label' => ['required', 'string', 'max:100'], 'street' => ['required', 'string', 'max:255'],
            'number' => ['required', 'string', 'max:10'], 'district' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'], 'state' => ['required', 'regex:/^[A-Za-z]{2}$/'],
            'postal_code' => ['required', 'regex:/^\d{5}-?\d{3}$/'], 'complement' => ['nullable', 'string', 'max:100'],
            'latitude' => ['required', 'numeric', 'between:-90,90'], 'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);
        DB::transaction(function () use ($d) {
            $record = tbsolicitacao::create(['idVitima' => $d['victim_id'], 'tipoSolicitacao' => $d['label'], 'logradouroSolicitacao' => $d['street'], 'numLogradouroSolicitacao' => $d['number'], 'bairroSolicitacao' => $d['district'], 'cidadeSolicitacao' => $d['city'], 'ufSolicitacao' => strtoupper($d['state']), 'cepSolicitacao' => $d['postal_code'], 'complementoSolicitacao' => $d['complement'] ?? null, 'latitudeSolicitacao' => $d['latitude'], 'longitudeSolicitacao' => $d['longitude'], 'statusSolicitacao' => 'pendente', 'dataSolicitacao' => today()]);
            AdminAudit::record('tbsolicitacao', $record->id, 'create');
        });

        return redirect()->route('admin.victims.requests')->with('status', 'Solicitação registrada para análise.');
    }

    public function decide(Request $r, int $id)
    {
        $d = $r->validate(['decision' => ['required', Rule::in(['aprovado', 'reprovado'])]]);
        DB::transaction(function () use ($d, $id, $r) {
            $record = tbsolicitacao::lockForUpdate()->findOrFail($id);
            abort_unless($record->statusSolicitacao === 'pendente', 409, 'Solicitação já analisada.');
            $record->forceFill(['statusSolicitacao' => $d['decision'], 'dataAnalise' => today(), 'analisadoPor' => $r->user()->id])->save();
            AdminAudit::record('tbsolicitacao', $id, $d['decision']);
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

        return back()->with('status','Local removido da lista de locais seguros.');
    }
}
