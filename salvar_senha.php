<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";

$senhaAtual = $_POST['senha_atual'];
$novaSenha = $_POST['nova_senha'];
$confirmaSenha = $_POST['confirma_senha'];

$login = $_SESSION['login'];


// Confere se as duas novas senhas são iguais
if ($novaSenha != $confirmaSenha) {

    echo "

    <script>

        alert('As novas senhas não são iguais!');

        window.location='trocar_senha.php';

    </script>

    ";

    exit;
}


// Busca a senha atual no banco
$consulta = "SELECT senha FROM logins WHERE login = '$login'";

$resultado = banco(
    $server,
    $user,
    $password,
    $db,
    $consulta
);


if ($resultado->num_rows == 0) {

    echo "

    <script>

        alert('Login não encontrado!');

        window.location='login.php';

    </script>

    ";

    exit;
}


$dados = $resultado->fetch_assoc();

$senhaSalva = $dados['senha'];


// Confere a senha atual
if (!password_verify($senhaAtual, $senhaSalva)) {

    echo "

    <script>

        alert('A senha atual está incorreta!');

        window.location='trocar_senha.php';

    </script>

    ";

    exit;
}


// Criptografa a nova senha
$novaSenhaCriptografada = password_hash(
    $novaSenha,
    PASSWORD_DEFAULT
);


// Atualiza a senha no banco
$consulta = "UPDATE logins SET
    senha = '$novaSenhaCriptografada'
    WHERE login = '$login'";

banco(
    $server,
    $user,
    $password,
    $db,
    $consulta
);


echo "

<script>

    alert('Senha alterada com sucesso!');

    window.location='conta.php';

</script>

";

?>
