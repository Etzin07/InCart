<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];

$consulta = "UPDATE usuarios SET
    nome = '$nome',
    endereco = '$endereco',
    bairro = '$bairro',
    cidade = '$cidade',
    estado = '$estado',
    cep = '$cep'
    WHERE cpf = '$cpf'";

banco($server, $user, $password, $db, $consulta);

$_SESSION['nome'] = $nome;

echo "

<script>

    alert('Informações atualizadas com sucesso!');

    window.location='conta.php';

</script>

";

?>
