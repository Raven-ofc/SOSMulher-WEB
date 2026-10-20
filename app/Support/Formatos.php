<?php

namespace App\Support;

/**
 * Normalização de CPF, telefone e CEP para o formato gravado no banco.
 * Todos os cadastros (vítima, agressor etc.) usam estes métodos para gravar sempre igual.
 */
class Formatos
{
    public static function digitos(?string $valor): string
    {
        return preg_replace('/\D/', '', (string) $valor);
    }

    public static function cpf(?string $valor): ?string
    {
        $d = self::digitos($valor);
        if (strlen($d) !== 11) {
            return $valor === null || $valor === '' ? null : $valor;
        }

        return substr($d, 0, 3) . '.' . substr($d, 3, 3) . '.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
    }

    public static function telefone(?string $valor): ?string
    {
        $d = self::digitos($valor);

        if (strlen($d) === 11) {
            return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 5) . '-' . substr($d, 7, 4);
        }
        if (strlen($d) === 10) {
            return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 4) . '-' . substr($d, 6, 4);
        }

        return $valor === null || $valor === '' ? null : $valor;
    }

    public static function cep(?string $valor): ?string
    {
        $d = self::digitos($valor);
        if (strlen($d) !== 8) {
            return $valor === null || $valor === '' ? null : $valor;
        }

        return substr($d, 0, 5) . '-' . substr($d, 5, 3);
    }

    public const UFS = [
        'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA',
        'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
    ];
}
