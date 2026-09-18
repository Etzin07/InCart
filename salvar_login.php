<?php

session_start();

include "app/cons.php";
require_once "app/DLL.php";

$login = $_POST['login'];
$senha = $_POST['senha'];

$senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

$cpf = $_SESSION['cpf'];

$consulta = "INSERT INTO logins
(cpf, login, senha)
VALUES
('$cpf', '$login', '$senhaCriptografada')";

banco($server, $user, $password, $db, $consulta);

echo "

<script>

    alert('Cadastro realizado com sucesso!');

    window.location='login.php';

</script>

";

?>
