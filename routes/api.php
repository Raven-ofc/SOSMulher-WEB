<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TornozeleiraController;
use App\Http\Controllers\Api\LocalizacaoTornozeleiraController;


//TORNOZELEIRA
Route::get('/tornozeleiras', [TornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{id}', [TornozeleiraController::class, 'show']);
Route::post('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'store']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes/ultima', [LocalizacaoTornozeleiraController::class, 'ultima']);


//VITIMA

Route::get('/vitima/{email}/{senha}', 'App\Http\Controllers\VitimaController@indexAPIs');
Route::get('/endereco/{id}','App\Http\Controllers\VitimaController@enderecoAPI');
Route::put('/atualizarVitima/{id}', 'App\Http\Controllers\VitimaController@AtualizarAPI');
Route::post('/atualizarImagem/{id}', 'App\Http\Controllers\VitimaController@atualizarImagemAPI');
Route::put('/atualizarLocalizacao/{id}', 'App\Http\Controllers\VitimaController@atualizarLocalizacaoAPI')
?>