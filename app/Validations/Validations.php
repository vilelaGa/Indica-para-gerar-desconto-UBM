<?php

namespace App\Validations;

use App\Db\Db;

use PDO;


class Validations
{

    public static function ValidaCPF($cpf, $tipo)
    {
        $replace = str_replace(array('.', '-'), '', $cpf);

        $replace = strlen($replace) < 11 ? die("<strong class='invalido'>CPF Inválido</strong>") : $replace;

        if (($tipo != 'graduacao') && ($tipo != 'colegio')) {
            die("<strong class='invalido'>Tipo Inválido</strong>");
        }

        return $replace;
    }
}
