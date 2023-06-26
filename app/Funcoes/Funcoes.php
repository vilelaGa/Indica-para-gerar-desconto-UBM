<?php

namespace App\Funcoes;

class Funcoes
{
    public static function Token()
    {
        $comb = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array();
        $combLen = strlen($comb) - 1;
        for ($i = 0; $i < 12; $i++) {
            $n = rand(0, $combLen);
            $pass[] = $comb[$n];
        }
        return $random = implode($pass);
    }

    public static function validCPF($cpf)
    {

        // Verifica se o número de dígitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return "O CPF deve conter 11 dígitos";
        }
        // Verifica se nenhuma das sequências inválidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        else if (
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return "CPF inválido";
            // Calcula os dígitos verificadores para verificar se o
            // CPF é válido
        } else {

            for ($t = 9; $t < 11; $t++) {

                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                    // return "<strong class='invalido'>CPF Inválido</strong>";
                }
            }

            return true;
            // return "<strong class='valido'>CPF Valido</strong>";
        }
    }
}
