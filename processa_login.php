<?php
session_start();

include "app/cons.php";
require_once "app/DLL.php";

$login = $_POST['login'];
$senha = $_POST['senha'];

// Antes: "SELECT * FROM logins WHERE login = '$login'" — SQL Injection.
// Agora: prepared statement, $login nunca vira parte do texto do comando SQL.
$resultado = bancoSeguro(
    $server, $user, $password, $db,
    "SELECT * FROM logins WHERE login = ?",
    "s",
    [$login]
);

if ($resultado->num_rows > 0) {

    $dadosLogin = $resultado->fetch_assoc();
    $cpf = $dadosLogin['cpf'];
    $loginSalvo = $dadosLogin['login'];
    $senhaSalva = $dadosLogin['senha'];

    if (password_verify($senha, $senhaSalva)) {

        $resultadoUsuario = bancoSeguro(
            $server, $user, $password, $db,
            "SELECT * FROM usuarios WHERE cpf = ?",
            "s",
            [$cpf]
        );

        if ($resultadoUsuario->num_rows > 0) {

            $dadosUsuario = $resultadoUsuario->fetch_assoc();
            $nome = $dadosUsuario['nome'];

            $_SESSION['logado'] = "ok";
            $_SESSION['nome'] = $nome;
            $_SESSION['cpf'] = $cpf;
            $_SESSION['login'] = $loginSalvo;

            header("Location: vitrine.php");
            exit;

        } else {
            echo "<script>alert('Usuário não encontrado!'); window.location='login.php';</script>";
        }

    } else {
        echo "<script>alert('Senha incorreta!'); window.location='login.php';</script>";
    }

} else {
    echo "<script>alert('Login não encontrado!'); window.location='login.php';</script>";
}

?>
