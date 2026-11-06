<?php

namespace App\Http\Controllers;

use App\Models\tbvitima;
use App\Models\tbtelefonevitima;
use App\Models\tbenderecoVitima;
use App\Models\tbAgressor;
use App\Models\tbTornozeleira;
use App\Models\tbMedida;
use App\Models\tbViolacao;
use App\Models\tbAlerta;
use App\Models\tbLocalizacaoTornozeleira;
use App\Models\tblocalizacaoVitima;
use App\Models\TbMedida as ModelsTbMedida;
use App\Models\TbVitima as ModelsTbVitima;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
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
            'maeVitima' => $vitima->nomeMaeVitima,
            'paiVitima' => $vitima->nomePaiVitima,
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

    public function localAgressorVitimaAPI(string $id){
        $vitima = tbvitima::with([
            'medidas.agressor.tornozeleira.localizacoes'
        ])->findOrFail($id);

        $localizacoes = $vitima->medidas->map(function ($medida) {
            $agressor = $medida->agressor;
            $tornozeleira = $agressor?->tornozeleira;
            if(!$tornozeleira){
                return null;
            }
            return [
                'idAgressor' => $agressor->id,
                'idMedida' => $medida->id,
                'idAutoridade'=> $medida->idAutoridade,
                'nomeAgressor' => $agressor->nomeAgressor,
                'distanciaMedida' => $medida->distanciaMedida,
                'raioAlertaMedida' => $medida->raioAlertaMedida,
                'latitude'=> $tornozeleira->localizacoes->first()?->latitudeLocalizacao,
                'longitude'=> $tornozeleira->localizacoes->first()?->longitudeLocalizacao,
                'dataHora'=> $tornozeleira->localizacoes->first()?->dataHoraLocalizacao,
            ];
        })->filter()->values();

        return response()->json([
            'idVitima'=> $vitima->id,
            'localizacoesAgressor'=>$localizacoes,
        ], 200);
    }

    public function registrarAlertaLimiteAPI(Request $request, string $idmedida){
        return DB::transaction(function () use ($request, $idmedida) {
            $medida = tbMedida::findOrfail($idmedida);

            $violacao = new tbViolacao();

            $violacao->tipoViolacao = 'Invasão de zona de exclusão';
            $violacao->descricaoViolacao = $request->descricaoViolacao;
            $violacao->statusViolacao = 'Em análise';
            $violacao->latitudeViolacao= $request->latitude;
            $violacao->longitudeViolacao= $request->longitude;
            $violacao->dataHoraViolacao= now();
            $violacao->idmedida= $medida->id;
            
            $violacao->save();

            $alerta = new tbAlerta();

            $alerta->tipoAlerta = 'Proximidade crítica';
            $alerta->descricaoAlerta = 'Agressor ultrapassou o limite da medida protetiva';
            $alerta->dataHoraAlerta = $violacao->dataHoraViolacao;
            $alerta->statusAlerta = 'Em andamento';
            $alerta->idViolacao = $violacao->id;
            $alerta->idAutoridade = $medida->idAutoridade;

            $alerta->save();

            return response()->json([
                'mensagem' => 'Violação e alerta enviados à autoridade responsável',
                'violacao' => $violacao,
                'alerta'=> $alerta
            ],201);
        });
    }

    public function registrarAlertaPanicoAPI(Request $request, string $idmedida){
        return DB::transaction(function () use ($request, $idmedida) {
            $medida = tbMedida::findOrfail($idmedida);

            $violacao = new tbViolacao();

            $violacao->tipoViolacao = $request->tipoViolacao;
            $violacao->descricaoViolacao = $request->descricaoViolacao;
            $violacao->statusViolacao = 'Em análise';
            $violacao->latitudeViolacao= $request->latitudeViolacao;
            $violacao->longitudeViolacao= $request->longitudeViolacao;
            $violacao->dataHoraViolacao= now();
            $violacao->idmedida= $medida->id;
            
            $violacao->save();

            $alerta = new tbAlerta();

            $alerta->tipoAlerta = 'Botão do pânico acionado!';
            $alerta->descricaoAlerta = 'A vítima apertou o botão do pânico';
            $alerta->dataHoraAlerta = $violacao->dataHoraViolacao;
            $alerta->statusAlerta = 'Em andamento';
            $alerta->idViolacao = $violacao->id;
            $alerta->idAutoridade = $medida->idAutoridade;

            $alerta->save();

            return response()->json([
                'mensagem' => 'Violação e alerta enviados à autoridade responsável',
                'violacao' => $violacao,
                'alerta'=> $alerta
            ],201);
        });
    }


    public function atualizarAPI(Request $request, string $id)
    {
        $validarDados = $request->validate([
            'email' => ['sometimes', 'required', 'email', 'max:200'],
            'numeroTelefone' => ['sometimes', 'required', 'string', 'max:15'],
        ]);

        $vitima = TbVitima::findOrFail($id);

        $vitima->update([
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