<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\TbVitima;
use App\Mail\CodigoVerificacaoMail;
use Illuminate\Support\Facades\Log;

class CodigoVerificacaoController extends Controller
{
    // ENVIAR CÓDIGO
    public function enviarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = strtolower(trim($request->email));

        // Verifica se a vítima existe
        $vitima = TbVitima::where('emailVitima', $email)->first();

        if (!$vitima) {
            return response()->json([
                'message' => 'E-mail não encontrado.'
            ], 404);
        }

        // Gera código aleatório de 6 dígitos
        $codigo = (string) random_int(100000, 999999);

        // Chave temporária do Cache
        $chave = 'codigo_verificacao_' . hash('sha256', $email);

        // Armazena o código protegido por hash durante 10 minutos
        Cache::put($chave, [
            'codigo' => Hash::make($codigo),
            'tentativas' => 0,
            'expira_em' => now()->addMinutes(10)->timestamp
        ], now()->addMinutes(10));

        try {

            // Envia o código por e-mail
            Mail::to($email)->send(
                new CodigoVerificacaoMail($codigo)
            );
        } catch (\Throwable $erro) {

            Cache::forget($chave);

            Log::error('Erro ao enviar código de verificação:', [
                'email' => $email,
                'erro' => $erro->getMessage()
            ]);

            return response()->json([
                'message' => 'Não foi possível enviar o e-mail.'
            ], 500);
        }
        return response()->json([
            'message' => 'Código enviado com sucesso!'
        ]);
    }


    // VERIFICAR CÓDIGO
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'codigo' => 'required|digits:6'
        ]);

        $email = strtolower(trim($request->email));

        $chave = 'codigo_verificacao_' . hash('sha256', $email);

        $dados = Cache::get($chave);

        if (!$dados) {
            return response()->json([
                'erro' => 'Código expirado ou inexistente.'
            ], 422);
        }

        if (now()->timestamp > $dados['expira_em']) {
            Cache::forget($chave);

            return response()->json([
                'erro' => 'Código expirado.'
            ], 422);
        }

        if ($dados['tentativas'] >= 5) {
            Cache::forget($chave);

            return response()->json([
                'erro' => 'Limite de tentativas atingido. Solicite outro código.'
            ], 429);
        }

        // Compara o código digitado com o hash armazenado
        if (!Hash::check($request->codigo, $dados['codigo'])) {

            $dados['tentativas']++;

            $tempoRestante = max(
                1,
                $dados['expira_em'] - now()->timestamp
            );

            Cache::put($chave, $dados, $tempoRestante);

            return response()->json([
                'erro' => 'Código incorreto.'
            ], 422);
        }

        // Código correto: remove o código utilizado
        Cache::forget($chave);

        // Libera temporariamente a próxima etapa da recuperação
        $chaveAutorizacao = 'recuperacao_autorizada_' .
            hash('sha256', $email);

        Cache::put(
            $chaveAutorizacao,
            true,
            now()->addMinutes(10)
        );

        return response()->json([
            'mensagem' => 'E-mail verificado com sucesso!'
        ]);
    }
}
