<?php

namespace App\Http\Controllers;

use App\Models\tbtelefoneVitima as ModelsTbtelefoneVitima;
use App\Models\tbvitima;
use App\Models\tbtelefonevitima;
use App\Models\tbenderecoVitima;
use App\Models\tblocalizacaoVitima;
use App\Rules\Cpf;
use App\Support\AdminAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psy\Command\WhereamiCommand;
use Illuminate\Support\Facades\Storage;

class VitimaController extends Controller
{
    //API

    public function indexAPIs(string $email, string $senha)
    {
        $vitima = tbvitima::leftjoin('tbtelefonevitima', 'tbtelefonevitima.idvitima', '=', 'tbvitima.id')
            ->select('tbvitima.*', 'tbtelefonevitima.idvitima as idvitima', 'tbtelefonevitima.numerotelefonevitima as numFoneVitima')
            ->where('tbvitima.emailVitima', $email)
            ->first();


        if (!$vitima || !Hash::check($senha, $vitima->senhaVitima)) {
            return response()->json([
                'message' => 'E-mail ou senha incorretos'
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
    public function enderecoAPI(string $id){
        $endereco = tbenderecoVitima::where('tbEnderecoVitima.idVitima', $id)->first();

        return $endereco;
    }
    //Listar o id de um tbvitima específico
    public function atualizarAPI(Request $request, string $id)
    {
        //validação
        $validarDados = $request->validate([
            'nome' => ['min:3', 'sometimes'],
            'email' => ['sometimes', 'max:200'],
            'numeroTelefone' => ['sometimes', 'max:15'],
            'imagem' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);

        //email/nome
        $vitima = tbvitima::findOrFail($id);

        $vitima->update([
            'nomeVitima' => $validarDados['nome'] ?? $vitima->nomeVitima,
            'emailVitima' => $validarDados['email'] ?? $vitima->emailVitima
        ]);

        //telefone
        $telefoneVitima = tbtelefonevitima::where('idvitima', '=', $id)->first();

        if ($telefoneVitima) {

            $telefoneVitima->update([
                'numerotelefonevitima' => $validarDados['numeroTelefone'] ?? $telefoneVitima->numerotelefonevitima
            ]);
        } else if (!empty($validarDados['numeroTelefone'])) {
            tbtelefonevitima::create([
                'idVitima' => $id,
                'numeroTelefoneVitima' => $validarDados['numeroTelefone']
            ]);
        }

        return response()->json($vitima, 201);
    }
    public function atualizarImagemAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'imagem' => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp'],
        ]);
        $vitima = tbvitima::findOrFail($id);

        //imagem

        if ($vitima->imagemVitima) {
            storage::disk('public')->delete($vitima->imagemVitima);
        }

        //nova imagem

        $imagemNova = $request->file('imagem')->store(
            'imagens/vitimas',
            'public'
        );

        $vitima->update([
            'imagemVitima' => $imagemNova
        ]);

        return response()->json([
            'message' => 'Imagem Atualizada com sucesso!',
            'imagemVitima' => $vitima->imagemVitima
        ],200);
    }
     public function atualizarLocalizacaoAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'longitude' => ['required', 'numeric'],
            'latitude' => ['required', 'numeric'],
        ]);
        $vitima = tbvitima::findOrFail($id);

        $local = tblocalizacaoVitima::updateOrCreate([
            'idVitima' => $vitima->id
        ],
        [
            'latitudeLocalizacao' => $validarDados['latitude'],
            'longitudeLocalizacao' =>$validarDados['longitude'],
            'dataHoraLocalizacao' => now(),
        ]);
        return response()->json ([
            'message' => 'Localização atualizada com sucesso!',
            'idVitima' => $vitima-> id,
            'latitude' => $local -> latitudeLocalizacao,
            'longitude' =>$local->longitudeLocalizacao,
            'dataHora' => $local -> dataHoraLocalizacao,
        ], 200);
    }
}
