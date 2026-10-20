<?php

namespace App\Http\Controllers;

use App\Models\tbvitima;
use App\Models\tbtelefonevitima;
use App\Models\tbenderecoVitima;
use App\Models\tblocalizacaoVitima;
use App\Models\TbVitima as ModelsTbVitima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\Concerns\Has;

class VitimaController extends Controller
{
    public function index()
    {
        $vitimas = TbVitima::paginate(10);

        return view('administracao.vitima.lista', compact('vitimas'));
    }

    public function show(string $id)
    {
        $vitima = TbVitima::findOrFail($id);

        return view('administracao.vitima.formulario', compact('vitima'));
    }

    public function indexAPIs(string $email, string $senha)
    {
        $vitima = TbVitima::leftJoin(
            'tbtelefonevitima',
            'tbtelefonevitima.idvitima',
            '=',
            'tbvitima.id'
        )
            ->select(
                'tbvitima.*',
                'tbtelefonevitima.idvitima as idvitima',
                'tbtelefonevitima.numerotelefonevitima as numFoneVitima'
            )
            ->where('tbvitima.emailVitima', $email)
            ->first();

        if (!$vitima || !Hash::check($senha, $vitima->senhaVitima)) {
            return response()->json([
                'message' => 'E-mail ou senha incorretos',
            ], 401);
        }

        return response()->json([
            'idVitima' => $vitima->id,
            'nomeVitima' => $vitima->nomeVitima,
            'cpfVitima' => $vitima->cpfVitima,
            'emailVitima' => $vitima->emailVitima,
            'telefoneVitima' => $vitima->numFoneVitima,
            'dataNascimentoVitima' => $vitima->dataNascimentoVitima,
            'statusVitima' => $vitima->statusVitima,
            'imagemVitima' => $vitima->imagemVitima,
        ]);
    }

    public function enderecoAPI(string $id)
    {
        $endereco = TbEnderecoVitima::where(
            'tbEnderecoVitima.idVitima',
            $id
        )->first();

        return $endereco;
    }

    public function atualizarAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'nome' => ['sometimes', 'required', 'string', 'min:3'],
            'email' => ['sometimes', 'required', 'email', 'max:200'],
            'numeroTelefone' => ['sometimes', 'required', 'string', 'max:15'],
        ]);

        $vitima = TbVitima::findOrFail($id);

        $vitima->update([
            'nomeVitima' => $validarDados['nome'] ?? $vitima->nomeVitima,
            'emailVitima' => $validarDados['email'] ?? $vitima->emailVitima,
        ]);

        $telefoneVitima = TbTelefoneVitima::where(
            'idvitima',
            $id
        )->first();

        if ($telefoneVitima) {
            $telefoneVitima->update([
                'numerotelefonevitima' =>
                    $validarDados['numeroTelefone']
                    ?? $telefoneVitima->numerotelefonevitima,
            ]);
        } elseif (!empty($validarDados['numeroTelefone'])) {
            TbTelefoneVitima::create([
                'idVitima' => $id,
                'numeroTelefoneVitima' => $validarDados['numeroTelefone'],
            ]);
        }

        return response()->json($vitima->fresh(), 200);
    }

    public function atualizarImagemAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'imagem' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5048',
            ],
        ]);

        $vitima = TbVitima::findOrFail($id);

        $imagemNova = $request->file('imagem')->store(
            'vitimas',
            'public'
        );

        if (!$imagemNova) {
            return response()->json([
                'message' => 'Não foi possível salvar a nova imagem.',
            ], 500);
        }

        $imagemAntiga = $vitima->imagemVitima;

        try {
            $vitima->update([
                'imagemVitima' => $imagemNova,
            ]);
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($imagemNova);

            throw $e;
        }

        if (
            $imagemAntiga &&
            !filter_var($imagemAntiga, FILTER_VALIDATE_URL)
        ) {
            Storage::disk('public')->delete($imagemAntiga);
        }

        return response()->json([
            'message' => 'Imagem atualizada com sucesso!',
            'imagemVitima' => $vitima->imagemVitima,
            'urlImagem' => asset('storage/' . $vitima->imagemVitima),
        ], 200);
    }

    public function atualizarLocalizacaoAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
        ]);

        $vitima = TbVitima::findOrFail($id);

        $local = TbLocalizacaoVitima::updateOrCreate(
            [
                'idVitima' => $vitima->id,
            ],
            [
                'latitudeLocalizacao' => $validarDados['latitude'],
                'longitudeLocalizacao' => $validarDados['longitude'],
                'dataHoraLocalizacao' => now(),
            ]
        );

        return response()->json([
            'message' => 'Localização atualizada com sucesso!',
            'idVitima' => $vitima->id,
            'latitude' => $local->latitudeLocalizacao,
            'longitude' => $local->longitudeLocalizacao,
            'dataHora' => $local->dataHoraLocalizacao,
        ], 200);
    }
}