<?php

namespace App\Http\Controllers;

use App\Models\tbagressor;
use App\Rules\Cpf;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AgressorController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(['ativo', 'inativo'])],
        ]);
        $query = tbagressor::query();
        if ($search = $filters['search'] ?? null) {
            $query->where(function ($query) use ($search) {
                $query->where('nomeAgressor', 'like', "%$search%")->orWhere('cpfAgressor', 'like', '%'.preg_replace('/[^0-9a-zA-Z]/', '', $search).'%');
            });
        }
        if ($status = $filters['status'] ?? null) {
            $query->where('statusAgressor', $status);
        }

        return view('administracao.agressor.lista', ['agressores' => $query->orderBy('nomeAgressor')->paginate(15)->withQueryString()]);
    }

    public function create()
    {
        return view('administracao.agressor.formulario', ['agressor' => null]);
    }

    public function edit(int $id)
    {
        return view('administracao.agressor.formulario', ['agressor' => tbagressor::findOrFail($id)]);
    }

    public function show(int $id)
    {
        $agressor = tbagressor::findOrFail($id);
        $occurrences = $agressor->ocorrencias()->latest('dataOcorrencia')->paginate(8);

        return view('administracao.agressor.detalhes', compact('agressor', 'occurrences'));
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
        $agressor = $id ? tbagressor::findOrFail($id) : new tbagressor;
        $request->validate(['cpf' => ['required', 'string'], 'phone' => ['required', 'string']]);
        $request->merge([
            'cpf' => preg_replace('/\D/', '', $request->cpf),
            'phone' => preg_replace('/\D/', '', $request->phone),
        ]);
        $rules = [
            'name' => ['required', 'string', 'max:100'],
            'cpf' => ['required', new Cpf, Rule::unique('tbagressor', 'cpfAgressor')->ignore($id)],
            'birth_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'phone' => ['required', 'regex:/^\d{10,11}$/'],
        ];

        $dados = $request->validate($rules);
        DB::transaction(function () use ($agressor, $dados, $id) {
            $agressor->forceFill([
                'nomeAgressor' => $dados['name'],
                'cpfAgressor' => $dados['cpf'],
                'dataNascimentoAgressor' => $dados['birth_date'],
            ]);
            if (! $id) {
                $agressor->statusAgressor = 'ativo';
            }
            $agressor->telefoneAgressor = $dados['phone'];
            $agressor->save();
            AdminAudit::record('tbagressor', $agressor->id, $id ? 'update' : 'create');
        });

        return redirect()->route('admin.aggressors.show', $agressor->id)->with('status', 'Cadastro salvo.');
    }

    public function status(Request $request, int $id)
    {
        $dados = $request->validate(['status' => ['required', Rule::in(['ativo', 'inativo'])]]);
        DB::transaction(function () use ($id, $dados) {
            $agressor = tbagressor::lockForUpdate()->findOrFail($id);
            if ($dados['status'] === 'inativo' && ($agressor->medidas()->where('statusMedida', 'ativo')->exists() || $agressor->ocorrencias()->where('statusAtendimento', 'andamento')->exists())) {
                throw ValidationException::withMessages(['status' => 'Há medidas ativas ou atendimentos em andamento. Resolva os vínculos antes de inativar.']);
            }
            $agressor->statusAgressor = $dados['status'];
            $agressor->save();
            AdminAudit::record('tbagressor', $id, $dados['status']);
        });

        return back()->with('status', 'Status atualizado.');
    }
}
