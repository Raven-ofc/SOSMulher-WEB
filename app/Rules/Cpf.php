<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Cpf implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match('/^\d{11}$/', $value) || preg_match('/^(\d)\1{10}$/', $value)) {
            $fail('Informe um CPF válido.');

            return;
        }
        for ($length = 9; $length < 11; $length++) {
            $sum = 0;
            for ($i = 0; $i < $length; $i++) {
                $sum += (int) $value[$i] * ($length + 1 - $i);
            }
            $digit = 11 - ($sum % 11);
            if ($digit >= 10) {
                $digit = 0;
            }
            if ($digit !== (int) $value[$length]) {
                $fail('Informe um CPF válido.');

                return;
            }
        }
    }
}
