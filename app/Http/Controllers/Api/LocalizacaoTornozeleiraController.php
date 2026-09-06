<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\tbtornozeleira;
use Illuminate\Http\Request;

class LocalizacaoTornozeleiraController extends Controller
{
    // Rota POST /api/tornozeleiras/{idTornozeleira}/localizacoes
     
    public function store(Request $request, $idTornozeleira)
    {
        $tornozeleira = tbtornozeleira::findOrFail($idTornozeleira);

        $validated = $request->validate([
            'latitudeLocalizacao'  => ['required', 'numeric', 'between:-90,90'],
            'longitudeLocalizacao' => ['required', 'numeric', 'between:-180,180'],
            'dataHoraLocalizacao'  => ['nullable', 'date'],
        ]);

        $localizacao = $tornozeleira->localizacoes()->create([
            'latitudeLocalizacao'  => $validated['latitudeLocalizacao'],
            'longitudeLocalizacao' => $validated['longitudeLocalizacao'],
            // se o ESP32 não mandar o horário, usamos o horário do servidor
            'dataHoraLocalizacao'  => $validated['dataHoraLocalizacao'] ?? now(),
        ]);

        return response()->json($localizacao, 201);
    }


     // Rota GET /api/tornozeleiras/{idTornozeleira}/localizacoes
    public function index($idTornozeleira)
    {
        $tornozeleira = tbtornozeleira::findOrFail($idTornozeleira);

        $localizacoes = $tornozeleira->localizacoes()
            ->orderByDesc('dataHoraLocalizacao')
            ->get();

        return response()->json($localizacoes);
    }

    public function ultima($idTornozeleira)
    {
        $tornozeleira = tbtornozeleira::findOrFail($idTornozeleira);

        $ultima = $tornozeleira->localizacoes()
            ->orderByDesc('dataHoraLocalizacao')
            ->firstOrFail();

        return response()->json($ultima);
    }
}
