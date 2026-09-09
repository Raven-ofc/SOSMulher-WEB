<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TornozeleiraController;
use App\Http\Controllers\Api\LocalizacaoTornozeleiraController;

Route::get('/tornozeleiras', [TornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{id}', [TornozeleiraController::class, 'show']);

// Recebe a localização enviada pelo esp
Route::post('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'store']);

// Histórico completo de localizações de uma tornozeleira
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'index']);

// Última posição conhecida (útil pro mapa em tempo real)
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes/ultima', [LocalizacaoTornozeleiraController::class, 'ultima']);

?>