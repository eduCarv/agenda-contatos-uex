<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCpf implements Rule
{
    public function passes($attribute, $value)
    {
        // Remove máscara
        $cpf = preg_replace('/[^0-9]/', '', $value);

        // Valida se tem 11 dígitos
        if (strlen($cpf) != 11) {
            return false;
        }

        // Valida se todos os dígitos são iguais
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Validação dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'O :attribute não é um CPF válido.';
    }
}
