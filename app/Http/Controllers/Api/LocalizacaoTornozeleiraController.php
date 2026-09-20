<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TbTornozeleira;
use Illuminate\Http\Request;

class LocalizacaoTornozeleiraController extends Controller
{
    public function store(Request $request, $idTornozeleira)
    {
        $tornozeleira = TbTornozeleira::findOrFail($idTornozeleira);

        $validated = $request->validate([
            'latitudeLocalizacao'  => ['required', 'numeric', 'between:-90,90'],
            'longitudeLocalizacao' => ['required', 'numeric', 'between:-180,180'],
            'dataHoraLocalizacao'  => ['nullable', 'date'],
        ]);

        $localizacao = $tornozeleira->localizacoes()->create([
            'latitudeLocalizacao'  => $validated['latitudeLocalizacao'],
            'longitudeLocalizacao' => $validated['longitudeLocalizacao'],
            'dataHoraLocalizacao'  => $validated['dataHoraLocalizacao'] ?? now(),
        ]);

        return response()->json($localizacao, 201);
    }

    public function index($idTornozeleira)
    {
        $tornozeleira = TbTornozeleira::findOrFail($idTornozeleira);

        $localizacoes = $tornozeleira->localizacoes()
            ->orderByDesc('dataHoraLocalizacao')
            ->get();

        return response()->json($localizacoes);
    }

    public function ultima($idTornozeleira)
    {
        $tornozeleira = TbTornozeleira::findOrFail($idTornozeleira);

        $ultima = $tornozeleira->localizacoes()
            ->orderByDesc('dataHoraLocalizacao')
            ->firstOrFail();

        return response()->json($ultima);
    }
}