<?php

namespace App\Sessions;

class Sessions
{
    public static function sessionStart($id)
    {
        session_start();
        $_SESSION['id_registro_amigo_aluno'] = $id;

        echo "<script>window.location = 'http://sistema.ubm.br:8090/indiqueumamigo/registrar.php'</script>";
    }
}
