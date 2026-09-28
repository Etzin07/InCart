<?php
session_start();

include "app/cons.php";
require_once "app/DLL.php";

$login = trim($_POST['login']);
$senha = $_POST['senha'];

if (!isset($_SESSION['cpf'])) {
    echo "<script>alert('Sessão expirada, refaça o cadastro.'); window.location='cadastro1.php';</script>";
    exit;
}

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);
$cpf = $_SESSION['cpf'];

// Antes: string SQL montada com $cpf/$login/$senhaCriptografada direto —
// SQL Injection. Agora com prepared statement.
$resultado = executarSeguro(
    $server, $user, $password, $db,
    "INSERT INTO logins (cpf, login, senha) VALUES (?, ?, ?)",
    "sss",
    [$cpf, $login, $senhaCriptografada]
);

// Antes, se o login já existisse (coluna é UNIQUE), o banco recusava o
// INSERT e ninguém avisava o usuário — a página seguia como se tivesse
// dado certo. Agora a gente detecta e avisa.
if (!$resultado['ok']) {
    if ($resultado['duplicado']) {
        echo "<script>alert('Esse login já existe. Escolha outro.'); window.location='cadastro2.php';</script>";
    } else {
        echo "<script>alert('Erro ao salvar login. Tente novamente.'); window.location='cadastro2.php';</script>";
    }
    exit;
}

echo "<script>alert('Cadastro realizado com sucesso!'); window.location='login.php';</script>";
?>
