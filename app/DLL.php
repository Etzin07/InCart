<?php

function teste_login($sessao) {

    if ($sessao != "ok") {
        header("Location: index.php?erro=1");
        exit;
    }
}


function banco($server, $user, $password, $db, $consulta) {

    $banco = new mysqli($server, $user, $password, $db);

    if ($banco->connect_error) {

        echo "Falha de conexão referência: ("
            . $banco->connect_errno
            . ") - "
            . $banco->connect_error;

        exit();
    }


    $resultado = $banco->query($consulta);

    if (!$resultado) {

        echo "Falha na consulta referência: ("
            . $banco->errno
            . ") - "
            . $banco->error;

        exit();
    }


    $banco->close();

    return $resultado;
}

?>