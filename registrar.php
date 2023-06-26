<?php

session_start();

require_once './vendor/autoload.php';

use App\DbSistemas\DbSistemas;
use App\Db\Db;

$session = $_SESSION['id_registro_amigo_aluno'];

if (!$session) {
    header("Location: http://sistema.ubm.br:8090/indiqueumamigo/");
    die();
}


$selectUser = (new DbSistemas('AMIGO_ALUNO'))->select("REGISTRO = $session");

$res = $selectUser->fetch(PDO::FETCH_ASSOC);

// echo $session;
$RESCURSO = $res['REGISTRO_INI'];

$pegaCurso = (new DbSistemas('AMIGO_INICIAL'))->select("REGISTRO = $RESCURSO");

$val = $pegaCurso->fetch(PDO::FETCH_ASSOC);

$CPF_ALUNO = $val['CPF'];

$PEGANOMECURSO = (new Db())->selectValidaCpf($CPF_ALUNO);

$pegatudo =  $PEGANOMECURSO->fetch(PDO::FETCH_ASSOC);

// echo '<pre>';
// print_r($res);

// die()

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- css -->
    <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/styles/registrar.css">
    <!-- css -->
    <link rel="shortcut icon" href="./assets/imgs/logo-promo.png" type="image/x-icon">
    <title>Indique um amigo</title>

    <style type="text/css">
        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            background: rgba(0, 0, 0, 0.75) url('./assets/imgs/loading2.gif') no-repeat center center;
            z-index: 10000;
        }
    </style>
</head>

<body>

    <nav class="navbar bg-light">
        <div class="container d-flex justify-content-center">

            <a href="#">
                <img src="assets/imgs/logo-promo.png" alt="" width="180" class="d-inline-block align-text-top">
            </a>

        </div>
    </nav>


    <div class="container">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">

                <div id="resposta">


                </div>

                <div class="mt-4">
                    <!-- <form>
                        <div class="form-floating">
                            <input type="text" class="form-control" id="cpf" name="cpf" placeholder="CPF indicador" value="<?= $session ?>">
                        </div>

                        <div class="form-floating">

                            <label>Email das pessoas indicadas (Enter para adiconar)</label>
                            <input type="text" class="form-control" id="example_email" name="example_email">

                        </div>

                        <div class="form-floating">
                            <button type="button" onclick="enviar()" class="w-100 btn btnColor">Enviar</button>
                        </div>
                    </form> -->

                    <form action="data/registro.php" method="POST">

                        <input type="hidden" name="tipo" value="<?= $res['TIPO'] ?>">



                        <?php
                        //retorno erro login
                        if (isset($_SESSION['error'])) :
                        ?>
                            <div class="form-floating">
                                <div class="alert alert-danger" role="alert">
                                    Favor preencher as informações
                                </div>
                            </div>
                        <?php endif;
                        unset($_SESSION['error'])
                        //retorno erro login
                        ?>

                        <?php
                        //retorno erro login
                        if (isset($_SESSION['error_email'])) :
                        ?>
                            <div class="form-floating">
                                <div class="alert alert-danger" role="alert">
                                    Email inválido
                                </div>
                            </div>
                        <?php endif;
                        unset($_SESSION['error_email'])
                        //retorno erro login
                        ?>


                        <?php
                        //retorno erro login
                        if (isset($_SESSION['envio'])) :
                        ?>
                            <div class="form-floating">
                                <div class="alert alert-success" role="alert">
                                    Seu amigo foi notificado no email. Peça para ele participar da promoção!
                                </div>
                            </div>
                        <?php endif;
                        unset($_SESSION['envio'])
                        //retorno erro login
                        ?>


                        <?php if ($res['TIPO'] == 2) : ?>

                            <div class="form-floating">
                                <h5>Seus dados</h5>
                                <input type="text" value="<?= $res['NOME'] === NULL ? '' : $res['NOME'] ?>" <?= $res['NOME'] === NULL ? '' : 'readonly' ?> placeholder="Seu nome" class="form-control" id="nome" name="nome"><br>
                                <input type="email" value="<?= $res['EMAIL'] === NULL ? '' : $res['EMAIL'] ?>" <?= $res['EMAIL'] === NULL ? '' : 'readonly' ?> class="form-control" id="email" name="email" placeholder="Email"><br>
                                <input type="text" value="<?= $res['TELEFONE'] === NULL ? '' : $res['TELEFONE'] ?>" <?= $res['TELEFONE'] === NULL ? '' : 'readonly' ?> class="form-control" id="telefone" name="telefone" placeholder="Telefone">
                            </div>

                        <?php else : ?>

                            <div class="form-floating">
                                <h5>Seus dados</h4>
                                    <input class="form-control" id="ra" name="ra" value="<?= $res['RA'] ?>" disabled><br>
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?= $res['NOME'] ?>" disabled><br>
                                    <input class="form-control" value="<?= $pegatudo['CURSO'] ?>" disabled><br>
                            </div>

                        <?php endif ?>


                        <div class="campos">
                            <div class="form-floating">
                                <h5>Amigos a indicar</h5>
                                <input class="form-control" placeholder="Nome do indicado" name="nomes">
                                <!-- <br><br> -->
                                <br>
                                <input class="form-control" placeholder="Email do indicado" name="emails">
                            </div>
                        </div>


                        <input style="margin-left: 22px;" onclick="log()" class="w-100 btn btnColor" type="submit" value="Enviar">

                    </form>

                    <!-- <div class="form-floating">
                        <button class="w-100 btn btnColor add_friend">Adicionar amigo</button>
                    </div> -->


                </div>

            </div>
            <div class="col-md-3"></div>
        </div>
    </div>

    <div id="loader"></div>

    <!-- Mask -->

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src='./assets/js/multiemail.js'></script>

    <script src="./assets/js/current.js"></script>

    <script>
        function log() {
            var spinner = $('#loader');
            $(function() {
                spinner.show();
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#telefone').mask('(00) 00000-0000')
        });
    </script>

    <script>
        $('.add_friend').on('click', function() {
            var campos = $('.campos').eq(0).clone(); // copiar só um destes elementos, escolhi copiar o primeiro que é o unico que tenho a certeza que vai sempre existir
            campos.find('input').val(''); // por o valor dos inputs dos novos campos (nome/email) vazios para o caso de termos preenchido já nos primeiros inputs ($('.campos').eq(0))
            $('input[type="submit"]').before(campos);
        });
    </script>

    <!-- <script>
        function enviar() {

            var cpf = document.getElementById('cpf').value;
            var example_email = document.getElementById('example_email').value;



            $.ajax({

                type: 'POST',
                dataType: 'html',
                url: 'data/registro.php',

                //Dados para envio
                data: {
                    cpf,
                    example_email

                },

                //função que será executada quando a solicitação for finalizada.
                success: function(msg) {
                    $("#resposta").html(msg);
                }
            });

        }
    </script> -->

</body>

</html>