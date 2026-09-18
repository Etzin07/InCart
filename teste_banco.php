<?php

include "app/cons.php";

$banco = new mysqli($server, $user, $password, $db);

if ($banco->connect_error) {
    die("Erro na conexão: " . $banco->connect_error);
}

echo "Conexão com o banco funcionando!";

$banco->close();

?>
