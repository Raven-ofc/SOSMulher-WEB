<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TbMedida;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Lista e detalhes das medidas protetivas.
 * A medida é CRIADA no fim do fluxo unificado (CadastroController, fase 4).
 */
class MedidaController extends Controller
{
    public const STATUS = ['Ativa', 'Encerrada', 'Revogada'];

    public function index(Request $request)
    {
        $dados = $request->validate([
            'busca' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::in(self::STATUS)],
            'risco' => ['nullable', Rule::in(array_keys(TbMedida::RISCOS))],
        ]);

        $query = TbMedida::with(['vitima', 'agressor.tornozeleira']);

        if ($busca = $dados['busca'] ?? null) {
            $query->where(function ($q) use ($busca) {
                $q->where('descricaoMedida', 'like', "%{$busca}%")
                    ->orWhere('numeroProcessoMedida', 'like', "%{$busca}%")
                    ->orWhereHas('vitima', fn ($v) => $v->where('nomeVitima', 'like', "%{$busca}%"))
                    ->orWhereHas('agressor', fn ($a) => $a->where('nomeAgressor', 'like', "%{$busca}%"));
            });
        }

        if ($status = $dados['status'] ?? null) {
            $query->where('statusMedida', $status);
        }

        if ($risco = $dados['risco'] ?? null) {
            $query->where('nivelRiscoMedida', $risco);
        }

        return view('administracao.medida-protetiva.lista', [
            'medidas' => $query->latest('dataInicioMedida')->latest('id')->paginate(15)->withQueryString(),
        ]);
    }

    public function show(int $id)
    {
        $medida = TbMedida::with([
            'vitima',
            'autoridade',
            'agressor.tornozeleira.localizacoes' => fn ($q) => $q->latest('dataHoraLocalizacao')->limit(1),
            'violacoes' => fn ($q) => $q->latest('dataHoraViolacao'),
        ])->findOrFail($id);

        // Ocorrências entre a mesma vítima e o mesmo agressor.
        $ocorrencias = \App\Models\TbOcorrencia::where('idVitima', $medida->idVitima)
            ->where('idAgressor', $medida->idAgressor)
            ->latest('dataHoraOcorrencia')
            ->get();

        return view('administracao.medida-protetiva.detalhes', compact('medida', 'ocorrencias'));
    }

    public function alterarStatus(Request $request, int $id)
    {
        $dados = $request->validate([
            'status' => ['required', Rule::in(self::STATUS)],
        ]);

        DB::transaction(function () use ($id, $dados) {
            $medida = TbMedida::lockForUpdate()->findOrFail($id);
            $medida->forceFill(['statusMedida' => $dados['status']])->save();

            // Ao encerrar/revogar a última medida ativa do agressor, a tornozeleira passa para "Inativa" (aguarda recolhimento).
            if ($dados['status'] !== 'Ativa') {
                $restamAtivas = TbMedida::where('idAgressor', $medida->idAgressor)
                    ->where('statusMedida', 'Ativa')
                    ->exists();

                if (!$restamAtivas && $medida->agressor?->tornozeleira) {
                    $medida->agressor->tornozeleira->forceFill(['statusTornozeleira' => 'Inativa'])->save();
                }
            }
        });

        return back()->with('status', 'Status da medida atualizado.');
    }
}
