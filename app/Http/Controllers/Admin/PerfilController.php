<?php

namespace App\Http\Controllers\Admin;

use App\Models\TbAutoridade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PerfilController extends \App\Http\Controllers\Controller
{
    private function usuario(): TbAutoridade
    {
        /** @var TbAutoridade $usuario */
        $usuario = Auth::user();

        return $usuario;
    }

    private function dadosPerfil(): array
    {
        $usuario = $this->usuario();

        $atendimentos = DB::table('relatorios_atendimento')
            ->where('idAutoridade', $usuario->id)
            ->count();

        $telefone = DB::table('tbTelefoneAutoridade')
            ->where('idAutoridade', $usuario->id)
            ->first();

        $cpfFormatado = $this->formatarCpf($usuario->cpfAutoridade);
        $telefoneFormatado = $this->formatarTelefone($telefone->numTelefoneAutoridade ?? null);

        return compact('atendimentos', 'telefone', 'cpfFormatado', 'telefoneFormatado');
    }

    public function visualizar(Request $request)
    {
        return view('administracao.perfil.detalhes', $this->dadosPerfil());
    }

    public function editar(Request $request)
    {
        return view('administracao.perfil.editar', $this->dadosPerfil());
    }

    public function atualizar(Request $request)
{
    $request->merge([
        'telefone' => $request->filled('telefone')
            ? preg_replace('/\D/', '', $request->telefone)
            : null,
    ]);

    $dados = $request->validate([
        'nome' => ['required', 'string', 'max:100'],
        'telefone' => ['nullable', 'regex:/^\d{10,11}$/'],
        'foto' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:2048',
            'dimensions:max_width=4096,max_height=4096',
        ],
    ]);

    $usuario = $this->usuario();
    $fotoAntiga = $usuario->imagemAutoridade;
    $novaFoto = null;

    if ($request->hasFile('foto')) {
        $novaFoto = $request->file('foto')->store('profile-photos', 'local');
    }

    try {
        DB::table('tbautoridade')
            ->where('id', $usuario->id)
            ->update([
                'nomeAutoridade' => $dados['nome'],
                'imagemAutoridade' => $novaFoto ?? $fotoAntiga,
            ]);

        DB::table('tbTelefoneAutoridade')->updateOrInsert(
            ['idAutoridade' => $usuario->id],
            ['numTelefoneAutoridade' => $dados['telefone']]
        );
    } catch (\Throwable $e) {
        if ($novaFoto) {
            Storage::disk('local')->delete($novaFoto);
        }

        throw $e;
    }

    if (
        $novaFoto &&
        $fotoAntiga &&
        $fotoAntiga !== $novaFoto &&
        Storage::disk('local')->exists($fotoAntiga)
    ) {
        Storage::disk('local')->delete($fotoAntiga);
    }

    return redirect()
        ->route('admin.perfil')
        ->with('status', 'Perfil atualizado.');
}

public function foto(Request $request)
{
    $usuario = $this->usuario();
    $caminho = $usuario->imagemAutoridade;

    abort_unless(
        $caminho && Storage::disk('local')->exists($caminho),
        404
    );

    $arquivo = Storage::disk('local')->path($caminho);

    return response()->file($arquivo, [
        'Cache-Control' => 'private, no-store',
        'X-Content-Type-Options' => 'nosniff',
    ]);
}

    private function formatarCpf(?string $valor): ?string
    {
        $digitos = preg_replace('/\D/', '', (string) $valor);

        if (strlen($digitos) !== 11) {
            return $valor ?: null;
        }

        return substr($digitos, 0, 3) . '.' . substr($digitos, 3, 3) . '.' . substr($digitos, 6, 3) . '-' . substr($digitos, 9, 2);
    }

    private function formatarTelefone(?string $valor): ?string
    {
        $digitos = preg_replace('/\D/', '', (string) $valor);

        if (strlen($digitos) === 11) {
            return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 5) . '-' . substr($digitos, 7);
        }

        if (strlen($digitos) === 10) {
            return '(' . substr($digitos, 0, 2) . ') ' . substr($digitos, 2, 4) . '-' . substr($digitos, 6);
        }

        return $valor ?: null;
    }
}