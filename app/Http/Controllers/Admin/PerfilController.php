<?php

namespace App\Http\Controllers\Admin;

use App\Rules\Cpf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerfilController extends \App\Http\Controllers\Controller
{
    public function visualizar(Request $request)
    {
        $atendimentos = DB::table('relatorios_atendimento')
            ->where('idAutoridade', $request->user()->id)
            ->count();

        return view('administracao.perfil.detalhes', compact('atendimentos'));
    }

    public function editar(Request $request)
    {
        $atendimentos = DB::table('relatorios_atendimento')
            ->where('idAutoridade', $request->user()->id)
            ->count();

        return view('administracao.perfil.editar', compact('atendimentos'));
    }

    public function atualizar(Request $request)
    {
        $request->validate(['cpf' => ['nullable', 'string'], 'telefone' => ['nullable', 'string']]);
        $request->merge([
            'cpf' => $request->filled('cpf') ? preg_replace('/\D/', '', $request->cpf) : null,
            'telefone' => $request->filled('telefone') ? preg_replace('/\D/', '', $request->telefone) : null,
        ]);

        $dados = $request->validate([
            'nome' => ['required', 'string', 'max:100'],
            'cpf' => ['nullable', new Cpf, Rule::unique('users', 'cpf')->ignore($request->user()->id)],
            'telefone' => ['nullable', 'regex:/^\d{10,11}$/'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ]);

        $usuario = $request->user();
        $antiga = $usuario->photo_path;
        $foto = $request->file('foto')?->store('profile-photos', 'local');

        try {
            $usuario->forceFill([
                'name' => $dados['nome'],
                'cpf' => $dados['cpf'],
                'phone' => $dados['telefone'],
                'photo_path' => $foto ?? $antiga,
            ])->save();
        } catch (\Throwable $e) {
            if ($foto) {
                Storage::disk('local')->delete($foto);
            }
            throw $e;
        }

        if ($foto && $antiga) {
            Storage::disk('local')->delete($antiga);
        }

        return redirect()->route('admin.perfil')->with('status', 'Perfil atualizado.');
    }

    public function foto(Request $request)
    {
        $caminho = $request->user()->photo_path;
        abort_unless($caminho && Storage::disk('local')->exists($caminho), 404);

        return response()->file(Storage::disk('local')->path($caminho), [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff'
        ]);
    }
}