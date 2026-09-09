<?php

namespace App\Http\Controllers;

use App\Models\tbvitima;
use App\Rules\Cpf;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class VitimaController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['ativo', 'inativo'])],
        ]);
        $query = tbvitima::query()->with(['telefones']);
        if ($search = $filters['search'] ?? null) {
            $query->where(function ($query) use ($search) {
                $query->where('nomeVitima', 'like', "%$search%")->orWhere('cpfVitima', 'like', '%'.preg_replace('/[^0-9a-zA-Z]/', '', $search).'%');
            });
        }
        if ($status = $filters['status'] ?? null) {
            $query->where('statusVitima', $status);
        }

        return view('administracao.vitima.lista', ['vitimas' => $query->orderBy('nomeVitima')->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('administracao.vitima.formulario', ['vitima' => null]);
    }

    public function edit(int $id)
    {
        return view('administracao.vitima.formulario', ['vitima' => tbvitima::findOrFail($id)]);
    }

    public function show(int $id)
    {
        $vitima = tbvitima::findOrFail($id);
        $occurrences = $vitima->ocorrencias()->latest('dataOcorrencia')->paginate(8);
        $places = $vitima->solicitacoes()->where('statusSolicitacao', 'aprovado')->whereNull('removidoEm')->get();

        return view('administracao.vitima.detalhes', compact('vitima', 'occurrences', 'places'));
    }

    public function store(Request $request)
    {
        return $this->save($request);
    }

    public function update(Request $request, int $id)
    {
        return $this->save($request, $id);
    }

    private function save(Request $request, ?int $id = null)
    {
        $vitima = $id ? tbvitima::findOrFail($id) : new tbvitima;
        $request->validate(['cpf' => ['required', 'string'], 'phone' => ['required', 'string']]);
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'phone' => preg_replace('/\D/', '', $request->phone),
        ]);
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'cpf' => ['required', new Cpf, Rule::unique('tbvitima', 'cpfVitima')->ignore($id)],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'phone' => ['required', 'regex:/^\d{10,11}$/'],
        ];
        $rules['email'] = ['required', 'email', 'max:100', Rule::unique('tbvitima', 'emailVitima')->ignore($id)];
        $dados = $request->validate($rules);
        DB::transaction(function () use ($vitima, $dados, $id) {
            $vitima->forceFill([
                'nomeVitima' => $dados['name'],
                'cpfVitima' => $dados['cpf'],
                'dataNascimentoVitima' => $dados['birth_date'],
            ]);
            if (! $id) {
                $vitima->statusVitima = 'ativo';
            }
            $vitima->emailVitima = $dados['email'];
            $vitima->save();
            $phone = $vitima->telefones()->orderBy('id')->first();
            if ($phone) {
                $phone->update(['numeroTelefoneVitima' => $dados['phone']]);
            } else {
                $vitima->telefones()->create(['numeroTelefoneVitima' => $dados['phone']]);
            }
            AdminAudit::record('tbvitima', $vitima->id, $id ? 'update' : 'create');
        });

        return redirect()->route('admin.victims.show', $vitima->id)->with('status', 'Cadastro salvo.');
    }

    public function status(Request $request, int $id)
    {
        $dados = $request->validate(['status' => ['required', Rule::in(['ativo', 'inativo'])]]);
        DB::transaction(function () use ($id, $dados) {
            $vitima = tbvitima::lockForUpdate()->findOrFail($id);
            if ($dados['status'] === 'inativo' && ($vitima->medidas()->where('statusMedida', 'ativo')->exists() || $vitima->ocorrencias()->where('statusAtendimento', 'andamento')->exists())) {
                throw ValidationException::withMessages(['status' => 'Há medidas ativas ou atendimentos em andamento. Resolva os vínculos antes de inativar.']);
            }
            $vitima->statusVitima = $dados['status'];
            $vitima->save();
            AdminAudit::record('tbvitima', $id, $dados['status']);
        });

        return back()->with('status', 'Status atualizado.');
    }
}
