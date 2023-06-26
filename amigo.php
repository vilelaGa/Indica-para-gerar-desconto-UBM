<?php

require_once './vendor/autoload.php';

use App\DbSistemas\DbSistemas;

@$token = $_GET['token'];

$query = (new DbSistemas("AMIGO_INDICADO"))->select("TOKEN = '$token' AND DATAACEITE IS NULL");

if ($query->rowCount() != 0) {
} else {
    header('Location: index.php');
}

// echo '<pre>';
// print_r($query->fetch(PDO::FETCH_ASSOC));

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="assets/styles/app.css">
    <!-- CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/imgs/logo-promo.png" type="image/x-icon">
    <title>Indique um amigo</title>
</head>

<body>



    <div class="container">
        <div class="row">
            <div class="DisplayImg">
                <span class="nomeLogo"><img src="assets/imgs/logo-promo.png" class="imgLogo" alt="logo"></span>
            </div>
            <div class="col-md-4"></div>
            <div class="col-md-4 inputCodigo">
                <form>
                    <span id="resposta">
                    </span>


                    <?php

                    $va = $query->fetch(PDO::FETCH_ASSOC);
                    $nome = $va['NOME'];

                    echo "<h6 class='mb-3'>Seja bem vindo: <b>$nome</b></h6>";

                    ?>

                    <div class="form-floating">


                        <input type="hidden" id="token" value="<?= $token ?>">
                        <input type="text" id="cpf" class="form-control" name='cpf' id="floatingInput" value="" placeholder="name@example.com" required="required">
                        <label for="floatingInput">Insira seu CPF</label>


                    </div>
                    <div class="form-floating">
                        <input type="text" id="telefone" class="form-control" name='telefone' value="" placeholder="name@example.com" required="required">
                        <label for="telefone">Insira seu telefone</label>
                        <button type="button" onclick="enviar()" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>



    <script src="assets/js/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    <script>
        function enviar() {

            var cpf = document.getElementById('cpf').value;
            var telefone = document.getElementById('telefone').value;
            var token = document.getElementById('token').value;



            $.ajax({

                type: 'POST',
                dataType: 'html',
                url: 'data/salvar-amigo.php',

                //Dados para envio
                data: {
                    cpf,
                    telefone,
                    token

                },

                //função que será executada quando a solicitação for finalizada.
                success: function(msg) {
                    $("#resposta").html(msg);
                }
            });

        }


        $(document).ready(function() {
            $('#cpf').mask('000.000.000-00')
            $('#telefone').mask('(00) 000000-000')
        });
    </script>

</body>

</html>