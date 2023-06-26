<?php

session_start();

require_once '../vendor/autoload.php';

use App\DbSistemas\DbSistemas;
use App\Funcoes\Funcoes;
use App\EnviarEmail\EnviarEmail;

$REGISTRO_amal = $_SESSION['id_registro_amigo_aluno'];

date_default_timezone_set("America/Sao_Paulo");

switch ($_POST['tipo']) {
    case (1):
        // Graduação

        $nomes = $_POST['nomes'];
        $emails = $_POST['emails'];


        // echo $nomes[$i];

        // die();

        if (empty($nomes) || empty($emails)) {
            $_SESSION['error'] = true;
            header("Location: ../registrar.php");
            die();
        }

        if (filter_var($_POST['emails'], FILTER_VALIDATE_EMAIL)) {
            $emails = $_POST['emails'];
        } else {
            $_SESSION['error_email'] = true;
            header("Location: ../registrar.php");
            die();
        }

        $InsertAmgio = new DbSistemas("AMIGO_INDICADO");

        $idInd = $InsertAmgio->insert([
            'REGISTRO_amal' => $REGISTRO_amal,
            'NOME' => $nomes,
            'EMAIL' => $emails,
            'TOKEN' => Funcoes::Token(),
            'VALIDACAO' => 0,
            'ENVIADO' => 1,
            'DATACADASTRO' => date('Y-m-d H:i:s')
        ]);

        // echo 'deu certo';

        $gerarLink = (new DbSistemas('AMIGO_INDICADO'))->select("REGISTRO_amin = $idInd");

        $ger = $gerarLink->fetch(PDO::FETCH_ASSOC);

        EnviarEmail::Enviar($emails, $nomes, $ger['TOKEN']);


        // echo 'DEU CERTO';
        session_start();
        $_SESSION['envio'] = true;
        header('Location: ../registrar.php');

        break;
    case (2):
        // Colegio

        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $nomes = $_POST['nomes'];
        $emails = $_POST['emails'];


        if (empty($nome) || empty($email) || empty($telefone) || empty($nomes) || empty($emails)) {
            $_SESSION['error'] = true;
            header("Location: ../registrar.php");
            die();
        } else {

            if (filter_var($_POST['emails'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $emails = $_POST['emails'];
                $email = $_POST['email'];
            } else {
                $_SESSION['error_email'] = true;
                header("Location: ../registrar.php");
                die();
            }

            $update = (new DbSistemas('AMIGO_ALUNO'))->update("REGISTRO = $REGISTRO_amal", [
                'NOME' => $nome,
                'EMAIL' => $email,
                'TELEFONE' => $telefone
            ]);


            $InsertAmgioColegio = new DbSistemas("AMIGO_INDICADO");

            $idIndCol = $InsertAmgioColegio->insert([
                'REGISTRO_amal' => $REGISTRO_amal,
                'NOME' => $nomes,
                'EMAIL' => $emails,
                'TOKEN' => Funcoes::Token(),
                'VALIDACAO' => 0,
                'ENVIADO' => 1,
                'DATACADASTRO' => date('Y-m-d H:i:s')
            ]);

            // echo 'deu certo';

            $gerarLink = (new DbSistemas('AMIGO_INDICADO'))->select("REGISTRO_amin = $idIndCol");

            $ger = $gerarLink->fetch(PDO::FETCH_ASSOC);

            EnviarEmail::Enviar($emails, $nomes, $ger['TOKEN']);
            // echo 'DEU CERTO';

            session_start();
            $_SESSION['envio'] = true;
            header('Location: ../registrar.php');
        }
        break;
}
