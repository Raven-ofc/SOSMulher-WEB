<?php

namespace App\Http\Controllers;
use App\Models\tbVitima;
use App\Models\TbLocalizacaoVitima;
use App\Models\tbDelegacia;

use Illuminate\Http\Request;

class DelegaciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function buscarDelegaciasAPI(Request $request, string $id)
    {
        $vitima = TbLocalizacaoVitima::where('idVitima', '=', $id)->firstOrFail();

        $latitude = $vitima->latitudeLocalizacao;
        $longitude = $vitima-> longitudeLocalizacao;

        if($latitude === null || $longitude=== null){
            return response()->json([
                'mensagem'=> 'Localização da vítima não encontrada.'
            ], 422);
        }

        $delegacias = tbDelegacia::select('*')
        ->whereNotNull('latitudeDelegacia')
        ->whereNotNull('longitudeDelegacia')
        ->selectRaw(
            '(6371*ACOS(
                LEAST(1, GREATEST(-1,
                    COS(RADIANS(?)) *
                    COS(RADIANS(latitudeDelegacia)) *
                    COS(RADIANS(longitudeDelegacia) - RADIANS(?)) +
                    SIN(RADIANS(?)) *
                    SIN(RADIANS(latitudeDelegacia))
                ))
            )) AS distanciaKm',
             [$latitude, $longitude, $latitude]
        )->orderBy('distanciaKm')
        ->limit(10)
        ->get();

        return response()->json([
            'delegacias'=> $delegacias
        ]);
    }
}
