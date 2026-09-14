<?php

session_start();

$nome = $_POST['nome'];
$cpf = $_POST['cpf'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];

$_SESSION['nome'] = $nome;
$_SESSION['cpf'] = $cpf;

if(!is_dir("usuarios")){

    mkdir("usuarios");

}

$arquivo = "usuarios/".$cpf.".dat";

$dados = $nome."\n";
$dados .= $cpf."\n";
$dados .= $endereco."\n";
$dados .= $bairro."\n";
$dados .= $cidade."\n";
$dados .= $estado."\n";
$dados .= $cep."\n";

$abrir = fopen($arquivo , "w");

fwrite($abrir , $dados);

fclose($abrir);

header("Location: cadastro2.php");

?>