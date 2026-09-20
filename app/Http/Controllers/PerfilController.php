<?php

namespace App\Http\Controllers;

use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerfilController extends Controller
{
    public function show(Request $request)
    {
        $atendimentos = DB::table('relatorios_atendimento')
            ->where('idAutoridade', $request->user()->id)
            ->count();
        $telefone = $request->user()->telefones()->first();

        return view('administracao.perfil.detalhes', compact('atendimentos', 'telefone'));
    }

    public function edit(Request $request)
    {
        $atendimentos = DB::table('relatorios_atendimento')
            ->where('idAutoridade', $request->user()->id)
            ->count();
        $telefone = $request->user()->telefones()->first();

        return view('administracao.perfil.editar', compact('atendimentos', 'telefone'));
    }

    public function update(Request $request)
    {
        $request->validate(['cpf' => ['nullable', 'string'], 'phone' => ['nullable', 'string']]);
        $request->merge([
            'cpf' => $request->filled('cpf') ? preg_replace('/\D/', '', $request->cpf) : null,
            'phone' => $request->filled('phone') ? preg_replace('/\D/', '', $request->phone) : null,
        ]);
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'cpf' => ['nullable', new Cpf, Rule::unique('tbautoridade', 'cpfAutoridade')->ignore($request->user()->id)],
            'phone' => ['nullable', 'regex:/^\d{10,11}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ]);
        $user = $request->user();
        $old = $user->imagemAutoridade;
        $photo = $request->file('photo')?->store('profile-photos', 'local');
        try {
            $user->forceFill([
                'nomeAutoridade' => $dados['name'],
                'cpfAutoridade' => $dados['cpf'],
                'imagemAutoridade' => $photo ?? $old,
            ])->save();

            // Telefone vive em tbtelefoneautoridade (um-para-muitos). Aqui
            // editamos o primeiro registro existente, ou criamos um novo
            // caso a autoridade ainda não tenha telefone cadastrado.
            if ($dados['phone']) {
                $user->telefones()->updateOrCreate(
                    ['idAutoridade' => $user->id],
                    ['numTelefoneAutoridade' => $dados['phone']]
                );
            }
        } catch (\Throwable $e) {
            if ($photo) {
                Storage::disk('local')->delete($photo);
            } throw $e;
        }
        if ($photo && $old) {
            Storage::disk('local')->delete($old);
        }

        return redirect()->route('admin.profile')->with('status', 'Perfil atualizado.');
    }

    public function photo(Request $request)
    {
        $path = $request->user()->imagemAutoridade;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
