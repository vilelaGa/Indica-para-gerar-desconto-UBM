<?php

require_once '../vendor/autoload.php';

use App\DbSistemas\DbSistemas;
use App\Db\Db;
use App\Validations\Validations;
use App\Funcoes\Funcoes;

date_default_timezone_set("America/Sao_Paulo");

$cpf = $_POST['cpf'];
$telefone = $_POST['telefone'];
$token = $_POST['token'];

$tipo = (new DbSistemas('AMIGO_INDICADO'))->select("TOKEN = '$token'");
$lll = $tipo->fetch(PDO::FETCH_ASSOC);

$RESTIPO = $lll['REGISTRO_amal'];

$pegaTipo = (new DbSistemas('AMIGO_ALUNO'))->select("REGISTRO = $RESTIPO");

$RESTUDO = $pegaTipo->fetch(PDO::FETCH_ASSOC);

$TYPE = $RESTUDO['TIPO'];

// die();

// ===================================

$telefoneReplace = str_replace(array('(', ')', ' ', '-'), '', $telefone);

$replace = str_replace(array('.', '-'), '', $cpf);

$replace = strlen($replace) < 11 ? die("<strong class='invalido'>CPF Inválido</strong>") : $replace;


if (Funcoes::validCPF($replace) === true) {
    // echo "<strong class='valido'>CPF Valido</strong>";
} else {
    die("<strong class='invalido'>CPF Inválido</strong>");
}


switch ($TYPE) {
    case (1):
        if (!empty($replace) || !empty($telefoneReplace)) {
            $var = (new DbSistemas('AMIGO_INDICADO'))->select("CPF = '$replace'");

            if ($var->rowCount() != 0) {
                die("<strong class='invalido'>CPF já foi cadastrado</strong>
        <a href='https://ubm.br'>Voltar</a>
        ");
            } else {
                // echo 'deu certo 1';

                $lin = (new Db())->selectValidarUserAnos($replace);

                if ($lin->rowCount() != 0) {
                    // die('ERRO 2');
                    // echo 'if 1';
                    $tok = (new DbSistemas('AMIGO_INDICADO'))->select("TOKEN = '$token'");

                    $linhaa = $tok->fetch(PDO::FETCH_ASSOC);

                    $id_amin = $linhaa['REGISTRO_amin'];

                    $update = (new DbSistemas("AMIGO_INDICADO"))->update("REGISTRO_amin = $id_amin AND DATAACEITE IS NULL", [
                        'CPF' => $replace,
                        'TELEFONE' => $telefoneReplace,
                        'VALIDACAO' => 1,
                        'DATAACEITE' => date('Y-m-d H:i:s')
                    ]);
                    die("<strong class='invalido'>Indicação não válida para a promoção</strong>
            <a href='https://ubm.br'>Voltar</a>
            ");
                } else {
                    // echo 'else';

                    $tok = (new DbSistemas("AMIGO_INDICADO"))->select("TOKEN = '$token'");

                    $linhaa = $tok->fetch(PDO::FETCH_ASSOC);

                    $id_amin = $linhaa['REGISTRO_amin'];

                    $update = (new DbSistemas("AMIGO_INDICADO"))->update("REGISTRO_amin = $id_amin AND DATAACEITE IS NULL", [
                        'CPF' => $replace,
                        'TELEFONE' => $telefoneReplace,
                        'VALIDACAO' => 0,
                        'DATAACEITE' => date('Y-m-d H:i:s')
                    ]);

                    // var_dump($update);
                    echo "<strong class='valido'>Parabéns por participar da promoção. Faça sua inscrição no vestibular. <b>Após 4 segundo será redirecionado</b></strong>";
                    die("<script>
            setTimeout(function() {
                window.location = 'https://www.ubm.br/vestibular/';
              }, 4000);
            </script>");
                }
            }
        } else {
            die("<strong class='invalido'>Telefone obrigatório</strong>");
        }
        break;
    case (2):
        // echo 'colegio';

        if (!empty($replace) || !empty($telefoneReplace)) {
            $var = (new DbSistemas('AMIGO_INDICADO'))->select("CPF = '$replace'");

            if ($var->rowCount() != 0) {
                die("<strong class='invalido'>CPF já foi cadastrado</strong>
        <a href='https://ubm.br'>Voltar</a>
        ");
            } else {
                $tok = (new DbSistemas("AMIGO_INDICADO"))->select("TOKEN = '$token'");

                $linhaa = $tok->fetch(PDO::FETCH_ASSOC);

                $id_amin = $linhaa['REGISTRO_amin'];

                $update = (new DbSistemas("AMIGO_INDICADO"))->update("REGISTRO_amin = $id_amin AND DATAACEITE IS NULL", [
                    'CPF' => $replace,
                    'TELEFONE' => $telefoneReplace,
                    'VALIDACAO' => 0,
                    'DATAACEITE' => date('Y-m-d H:i:s')
                ]);

                echo "<strong class='valido'>Parabéns por participar da promoção. <b>Após 4 segundo será redirecionado para www.colegioubm.com.br</b></strong>";
                die("<script>
            setTimeout(function() {
                window.location = 'http://www.colegioubm.com.br/';
              }, 4000);
            </script>");
            }
        } else {
            die("<strong class='invalido'>Telefone obrigatório</strong>");
        }
        break;
}
