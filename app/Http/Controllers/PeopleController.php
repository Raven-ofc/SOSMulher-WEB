<?php

namespace App\Http\Controllers;

use App\Models\tbagressor;
use App\Models\tbvitima;
use App\Rules\Cpf;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PeopleController extends Controller
{
    private function config(Request $r): array
    {
        return str_contains($r->route()->getName(), 'victims')
            ? ['model' => tbvitima::class, 'table' => 'tbvitima', 'suffix' => 'Vitima', 'base' => 'admin.victims', 'label' => 'Usuárias/Vítimas', 'victim' => true]
            : ['model' => tbagressor::class, 'table' => 'tbagressor', 'suffix' => 'Agressor', 'base' => 'admin.aggressors', 'label' => 'Agressores', 'victim' => false];
    }

    public function index(Request $r)
    {
        $c = $this->config($r);
        $s = $c['suffix'];
        $filters = $r->validate(['search' => ['nullable', 'string', 'max:100'], 'status' => ['nullable', Rule::in(['ativo', 'inativo'])]]);
        $query = $c['model']::query()->with($c['victim'] ? ['telefones'] : []);
        if ($search = $filters['search'] ?? null) {
            $query->where(function ($q) use ($s, $search) {
                $q->where("nome$s", 'like', "%$search%")->orWhere("cpf$s", 'like', '%'.preg_replace('/[^0-9a-zA-Z]/', '', $search).'%');
            });
        }
        if ($status = $filters['status'] ?? null) {
            $query->where("status$s", $status);
        }

        return view('admin.people.index', ['c' => $c, 'records' => $query->orderBy("nome$s")->paginate(15)->withQueryString()]);
    }

    public function create(Request $r)
    {
        return view('admin.people.form', ['c' => $this->config($r), 'record' => null]);
    }

    public function edit(Request $r, int $id)
    {
        $c = $this->config($r);

        return view('admin.people.form', ['c' => $c, 'record' => $c['model']::findOrFail($id)]);
    }

    public function show(Request $r, int $id)
    {
        $c = $this->config($r);
        $record = $c['model']::findOrFail($id);
        $occurrences = $record->ocorrencias()->latest('dataOcorrencia')->paginate(8);
        $places = $c['victim'] ? $record->solicitacoes()->where('statusSolicitacao', 'aprovado')->whereNull('removidoEm')->get() : collect();

        return view('admin.people.show', compact('c', 'record', 'occurrences', 'places'));
    }

    public function store(Request $r)
    {
        return $this->save($r);
    }

    public function update(Request $r, int $id)
    {
        return $this->save($r, $id);
    }

    private function save(Request $r, ?int $id = null)
    {
        $c = $this->config($r);
        $s = $c['suffix'];
        $record = $id ? $c['model']::findOrFail($id) : new $c['model'];
        $r->validate(['cpf' => ['required', 'string'], 'phone' => ['required', 'string']]);
        $r->merge(['cpf' => preg_replace('/\D/', '', $r->cpf), 'phone' => preg_replace('/\D/', '', $r->phone)]);
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'cpf' => ['required', new Cpf, Rule::unique($c['table'], "cpf$s")->ignore($id)],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'phone' => ['required', 'regex:/^\d{10,11}$/'],
        ];
        if ($c['victim']) {
            $rules['email'] = ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')->ignore($id)];
        }
        $d = $r->validate($rules);
        DB::transaction(function () use ($record, $d, $c, $s, $id) {
            $record->forceFill(["nome$s" => $d['name'], "cpf$s" => $d['cpf'], "dataNascimento$s" => $d['birth_date']]);
            if (! $id) {
                $record->setAttribute("status$s", 'ativo');
            }
            if ($c['victim']) {
                $record->emailVitima = $d['email'];
            } else {
                $record->telefoneAgressor = $d['phone'];
            }
            $record->save();
            if ($c['victim']) {
                $phone = $record->telefones()->orderBy('id')->first();
                if ($phone) {
                    $phone->update(['numeroTelefoneVitima' => $d['phone']]);
                } else {
                    $record->telefones()->create(['numeroTelefoneVitima' => $d['phone']]);
                }
            }
            AdminAudit::record($c['table'], $record->id, $id ? 'update' : 'create');
        });

        return redirect()->route($c['base'].'.show', $record->id)->with('status', 'Cadastro salvo.');
    }

    public function status(Request $r, int $id)
    {
        $c = $this->config($r);
        $s = $c['suffix'];
        $d = $r->validate(['status' => ['required', Rule::in(['ativo', 'inativo'])]]);
        DB::transaction(function () use ($c, $s, $id, $d) {
            $record = $c['model']::lockForUpdate()->findOrFail($id);
            if ($d['status'] === 'inativo' && ($record->medidas()->where('statusMedida', 'ativo')->exists() || $record->ocorrencias()->where('statusAtendimento', 'andamento')->exists())) {
                throw ValidationException::withMessages(['status' => 'Há medidas ativas ou atendimentos em andamento. Resolva os vínculos antes de inativar.']);
            }
            $record->setAttribute("status$s", $d['status']);
            $record->save();
            AdminAudit::record($c['table'],$id,$d['status']);
        });

        return back()->with('status','Status atualizado.');
    }
}
