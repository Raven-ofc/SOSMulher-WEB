<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TornozeleiraController;
use App\Http\Controllers\Api\LocalizacaoTornozeleiraController;
use App\Http\Controllers\Api\CodigoVerificacaoController;


//TORNOZELEIRA
Route::get('/tornozeleiras', [TornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{id}', [TornozeleiraController::class, 'show']);
Route::post('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'store']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes', [LocalizacaoTornozeleiraController::class, 'index']);
Route::get('/tornozeleiras/{idTornozeleira}/localizacoes/ultima', [LocalizacaoTornozeleiraController::class, 'ultima']);


//VITIMA

Route::get('/vitima/{email}/{senha}', 'App\Http\Controllers\VitimaController@indexAPIs');
Route::get('/endereco/{id}','App\Http\Controllers\VitimaController@enderecoAPI');
Route::get('/localAgressor/{id}','App\Http\Controllers\VitimaController@localAgressorVitimaAPI');
Route::put('/atualizarVitima/{id}', 'App\Http\Controllers\VitimaController@AtualizarAPI');
Route::post('/Enviar-Violacao/{idmedida}', 'App\Http\Controllers\VitimaController@registrarAlertaLimiteAPI');
Route::post('/registrarViolacao/{idmedida}', 'App\Http\Controllers\VitimaController@registrarAlertaPanicoAPI');
Route::post('/atualizarImagem/{id}', 'App\Http\Controllers\VitimaController@atualizarImagemAPI');
Route::post('/recuperarSenha', 'App\Http\Controllers\Admin\VitimaController@recuperarSenhaAPI');
Route::put('/atualizarLocalizacao/{id}', 'App\Http\Controllers\VitimaController@atualizarLocalizacaoAPI');
Route::post('/enviarCodigo', 'App\Http\Controllers\Api\CodigoVerificacaoController@enviarCodigo');
Route::post('/verificarCodigo', 'App\Http\Controllers\Api\CodigoVerificacaoController@verificarCodigo');

//DELEGACIAS

Route::get('delegacias/{id}', 'App\Http\Controllers\DelegaciaController@buscarDelegaciasAPI');
?>