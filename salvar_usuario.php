<?php

session_start();

include "app/cons.php";
require_once "app/DLL.php";

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];

$_SESSION['nome'] = $nome;
$_SESSION['cpf'] = $cpf;

$consulta = "INSERT INTO usuarios
(nome, cpf, endereco, bairro, cidade, estado, cep)
VALUES
('$nome', '$cpf', '$endereco', '$bairro', '$cidade', '$estado', '$cep')";

banco($server, $user, $password, $db, $consulta);

header("Location: cadastro2.php");
exit;

?>
