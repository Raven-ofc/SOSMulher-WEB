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
            ->where('user_id', $request->user()->id)
            ->count();

        return view('administracao.perfil.detalhes', compact('atendimentos'));
    }

    public function edit(Request $request)
    {
        $atendimentos = DB::table('relatorios_atendimento')
            ->where('user_id', $request->user()->id)
            ->count();

        return view('administracao.perfil.editar', compact('atendimentos'));
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
            'cpf' => ['nullable', new Cpf, Rule::unique('users', 'cpf')->ignore($request->user()->id)],
            'phone' => ['nullable', 'regex:/^\d{10,11}$/'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'],
        ]);
        $user = $request->user();
        $old = $user->photo_path;
        $photo = $request->file('photo')?->store('profile-photos', 'local');
        try {
            $user->forceFill([
                'name' => $dados['name'],
                'cpf' => $dados['cpf'],
                'phone' => $dados['phone'],
                'photo_path' => $photo ?? $old,
            ])->save();
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
        $path = $request->user()->photo_path;
        abort_unless($path && Storage::disk('local')->exists($path), 404);

        return response()->file(Storage::disk('local')->path($path), ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
