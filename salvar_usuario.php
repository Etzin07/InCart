<?php
session_start();

include "app/cons.php";
require_once "app/DLL.php";

$nome = trim($_POST['nome']);
$cpf = trim($_POST['cpf']);
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$cidade = $_POST['cidade'];
$estado = $_POST['estado'];
$cep = $_POST['cep'];

// Normaliza o CPF para só dígitos ANTES de salvar. Sem isso,
// "867.208.085-83" e "86720808583" (a mesma pessoa) viram dois cadastros
// diferentes — foi exatamente esse bug que apareceu nos seus dados antigos.
$cpfLimpo = preg_replace('/\D/', '', $cpf);

if (strlen($cpfLimpo) !== 11) {
    echo "<script>alert('CPF inválido. Digite os 11 números do CPF.'); window.location='cadastro1.php';</script>";
    exit;
}

$_SESSION['nome'] = $nome;
$_SESSION['cpf'] = $cpfLimpo;

// Antes: string SQL montada com as variáveis direto — SQL Injection.
// Agora com prepared statement.
$resultado = executarSeguro(
    $server, $user, $password, $db,
    "INSERT INTO usuarios (nome, cpf, endereco, bairro, cidade, estado, cep)
     VALUES (?, ?, ?, ?, ?, ?, ?)",
    "sssssss",
    [$nome, $cpfLimpo, $endereco, $bairro, $cidade, $estado, $cep]
);

if (!$resultado['ok']) {
    if ($resultado['duplicado']) {
    $existe = bancoSeguro($server, $user, $password, $db,
    "SELECT id FROM logins WHERE cpf = ?", "s", [$cpfLimpo]);

if ($existe->num_rows === 0) {
    header("Location: cadastro2.php"); // CPF sem login: deixa terminar
    exit;
}
        echo "<script>alert('Já existe um cadastro com esse CPF. Faça login normalmente.'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar cadastro. Tente novamente.'); window.location='cadastro1.php';</script>";
    }
    exit;
}

header("Location: cadastro2.php");
exit;
?>

