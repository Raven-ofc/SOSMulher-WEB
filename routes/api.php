<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TornozeleiraController;
use App\Http\Controllers\Api\LocalizacaoTornozeleiraController;

Route::get('/tornozeleiras', [TornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{id}', [TornozeleiraController::class, 'show']);
Route::post('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'store']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes/ultima', [LocalizacaoTornozeleiraController::class, 'ultima']);

?>