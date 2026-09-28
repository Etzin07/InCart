<?php
session_start();

include "app/cons.php";
require_once "app/DLL.php";

$usuario = trim($_POST['usuario']);
$senha = $_POST['senha'];

$resultado = bancoSeguro(
    $server, $user, $password, $db,
    "SELECT * FROM admins WHERE usuario = ?",
    "s",
    [$usuario]
);

if ($resultado->num_rows > 0) {

    $dados = $resultado->fetch_assoc();

    if (password_verify($senha, $dados['senha'])) {
        $_SESSION['admin_logado'] = "ok";
        $_SESSION['admin_usuario'] = $dados['usuario'];
        $_SESSION['admin_id'] = $dados['id'];

        header("Location: admin.php");
        exit;
    }
}

echo "<script>alert('Usuário ou senha inválidos.'); window.location='admin_login.php';</script>";
?>
