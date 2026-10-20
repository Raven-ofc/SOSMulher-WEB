<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Descobre a região (zona) e o ponto central de um bairro/CEP usando config/regioes.php.
 */
class Regiao
{
    public static function normalizar(?string $texto): string
    {
        return Str::lower(Str::ascii(trim((string) $texto)));
    }

    public static function dadosDoBairro(?string $bairro): ?array
    {
        $alvo = self::normalizar($bairro);
        if ($alvo === '') {
            return null;
        }

        foreach (config('regioes.bairros', []) as $nome => $dados) {
            if (self::normalizar($nome) === $alvo) {
                return $dados;
            }
        }

        return null;
    }

    public static function deLocal(?string $bairro, ?string $cep = null): string
    {
        if ($dados = self::dadosDoBairro($bairro)) {
            return $dados['zona'];
        }

        $digitos = preg_replace('/\D/', '', (string) $cep);
        if (strlen($digitos) >= 3) {
            $porCep = config('regioes.prefixos_cep', [])[substr($digitos, 0, 3)] ?? null;
            if ($porCep) {
                return $porCep;
            }
        }

        return config('regioes.padrao', 'Outras regiões');
    }

    /** @return array{0: float|null, 1: float|null} */
    public static function centroDoBairro(?string $bairro): array
    {
        $dados = self::dadosDoBairro($bairro);

        return $dados ? [(float) $dados['lat'], (float) $dados['lng']] : [null, null];
    }

    public static function todas(): array
    {
        return array_merge(config('regioes.zonas', []), [config('regioes.padrao', 'Outras regiões')]);
    }
}
