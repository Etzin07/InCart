<?php

session_start();

include "app/cons.php";
require_once "app/DLL.php";

$login = $_POST['login'];
$senha = $_POST['senha'];


// Procura o login no banco
$consulta = "SELECT * FROM logins WHERE login = '$login'";

$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado->num_rows > 0) {

    $dadosLogin = $resultado->fetch_assoc();

    $cpf = $dadosLogin['cpf'];
    $loginSalvo = $dadosLogin['login'];
    $senhaSalva = $dadosLogin['senha'];


    // Verifica a senha
    if (password_verify($senha, $senhaSalva)) {

        // Procura os dados do usuário pelo CPF
        $consultaUsuario = "SELECT * FROM usuarios WHERE cpf = '$cpf'";

        $resultadoUsuario = banco(
            $server,
            $user,
            $password,
            $db,
            $consultaUsuario
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

            echo "

            <script>

                alert('Usuário não encontrado!');

                window.location='login.php';

            </script>

            ";

        }

    } else {

        echo "

        <script>

            alert('Senha incorreta!');

            window.location='login.php';

        </script>

        ";

    }

} else {

    echo "

    <script>

        alert('Login não encontrado!');

        window.location='login.php';

    </script>

    ";

}

?>
