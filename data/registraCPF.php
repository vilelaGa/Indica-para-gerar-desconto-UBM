<?php

require_once '../vendor/autoload.php';

use App\Validations\Validations;
use App\Db\Db;
use App\DbSistemas\DbSistemas;
use App\Sessions\Sessions;
use App\Funcoes\Funcoes;

date_default_timezone_set("America/Sao_Paulo");

$cpf = filter_var($_POST['cpf'], FILTER_SANITIZE_ADD_SLASHES);
$tipo = filter_var($_POST['tipo'], FILTER_SANITIZE_ADD_SLASHES);

$cpfValidado = Validations::ValidaCPF($cpf, $tipo);

if (Funcoes::validCPF($cpfValidado) === true) {
    // echo "<strong class='valido'>CPF Valido</strong>";
} else {
    die("<strong class='invalido'>CPF Inválido</strong>");
}

$consulta = (new Db())->selectValidaCpf($cpfValidado);

if ($consulta->rowCount() != 0) {

    // echo 'ele e aluno';
    // echo $cpfValidado;
    // die();

    $verificaExitenciaCPF = (new DbSistemas('AMIGO_INICIAL'))->select("CPF = '$cpfValidado'");

    if ($verificaExitenciaCPF->rowCount() != 0) {

        // echo ('Exite no banco <br>');

        $var = $verificaExitenciaCPF->fetch(PDO::FETCH_ASSOC);

        $registro = $var['REGISTRO'];

        $verificaExitenciaAluno = (new DbSistemas('AMIGO_ALUNO'))->select("REGISTRO_INI = $registro");

        // var_dump($verificaExitenciaAluno);

        // die();

        if ($verificaExitenciaAluno->rowCount() != 0) {
            $va = $verificaExitenciaAluno->fetchAll(PDO::FETCH_ASSOC);

            // echo 'Tipo = ' . $va['TIPO'] . '<br>';

            $tipoVerifica = $tipo == 'graduacao' ? 1 : 2;

            foreach ($va as $linha) {
                $arr[] = $linha['TIPO'];
            }

            if (in_array($tipoVerifica, $arr)) {
                // echo 'redirect';

                $redirecTCon = (new DbSistemas('AMIGO_INICIAL'))->select("CPF = '$cpfValidado'");
                $valoresRed = $redirecTCon->fetch(PDO::FETCH_ASSOC);


                $registroRedi = $valoresRed['REGISTRO'];

                $resRed = (new DbSistemas('AMIGO_ALUNO'))->select("REGISTRO_INI = $registroRedi AND TIPO = $tipoVerifica");

                $varRedi = $resRed->fetch(PDO::FETCH_ASSOC);

                Sessions::sessionStart($varRedi['REGISTRO']);
            } else {
                // echo 'aqui <br>';
                // echo $tipo;
                // die();

                $row = $consulta->fetch(PDO::FETCH_ASSOC);

                $ra = $row['RA'];
                $nome = $row['NOME'];
                $tipo = $tipo == 'graduacao' ? 1 : 2;
                //1 graduação | 2 colegio

                $InsertAmgioAluno = new DbSistemas("AMIGO_ALUNO");
                $idRs = $InsertAmgioAluno->insert([
                    'REGISTRO_INI' =>  $registro,
                    'RA' => $ra,
                    'NOME' => $nome,
                    'TIPO' => $tipo,
                    'DATACADASTRO' => date('Y-m-d H:i:s')
                ]);

                Sessions::sessionStart($idRs);
            }

            // die();
        }
    } else {
        // echo 'Não existe no banco';

        $DbSistemas = new DbSistemas("AMIGO_INICIAL");

        $id = $DbSistemas->insert([
            'CPF' => $cpfValidado,
            'DATACADASTRO' => date('Y-m-d H:i:s'),
        ]);
        $row = $consulta->fetch(PDO::FETCH_ASSOC);

        $id;
        $ra = $row['RA'];
        $nome = $row['NOME'];
        $tipo = $tipo == 'graduacao' ? 1 : 2;
        //1 graduação | 2 colegio

        $InsertAmgioAluno = new DbSistemas("AMIGO_ALUNO");
        $idNovo = $InsertAmgioAluno->insert([
            'REGISTRO_INI' => $id,
            'RA'           => $ra,
            'NOME'         => $nome,
            'TIPO'         => $tipo,
            'DATACADASTRO' => date('Y-m-d H:i:s')
        ]);

        // session_start();
        // $_SESSION['id_registro_amigo_aluno'] = $id;

        // echo "<script>window.location = 'http://sistema.ubm.br:8090/indiqueumamigo/registrar.php'</script>";
        Sessions::sessionStart($idNovo);
    }

    // =============================================================

} else {
    // echo 'ele e pai';

    $tipo == 'graduacao' ? die("<strong class='invalido'>Somente Alunos matriculados podem participar da promoção</strong>") : $tipo;


    $verifica = (new DbSistemas('AMIGO_INICIAL'))->select("CPF = '$cpfValidado'");

    if ($verifica->rowCount() != 0) {
        $res = $verifica->fetch(PDO::FETCH_ASSOC);

        $id = $res['REGISTRO'];

        $verificaAmigoAluno = (new DbSistemas('AMIGO_ALUNO'))->select("REGISTRO_INI = $id");

        if ($verificaAmigoAluno->rowCount() != 0) {

            $opa = $verificaAmigoAluno->fetch(PDO::FETCH_ASSOC);
            Sessions::sessionStart($opa['REGISTRO']);
        }
    } else {
        $id = $DbSistemas = new DbSistemas("AMIGO_INICIAL");

        $id = $DbSistemas->insert([
            'CPF' => $cpfValidado,
            'DATACADASTRO' => date('Y-m-d H:i:s'),
        ]);

        // =============================================================
        $tipo = $tipo == 'graduacao' ? 1 : 2;
        //1 graduação | 2 colegio

        $InsertAmgioAluno = new DbSistemas("AMIGO_ALUNO");
        $idNovo =  $InsertAmgioAluno->insert([
            'REGISTRO_INI' => $id,
            'TIPO' => $tipo,
            'DATACADASTRO' => date('Y-m-d H:i:s')
        ]);

        Sessions::sessionStart($idNovo);
    }
}
