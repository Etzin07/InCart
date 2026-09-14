<?php

session_start();

$login = $_POST['login'];

$senha = $_POST['senha'];

$arquivo = "login/".$login.".dat";

if(file_exists($arquivo)){

    $abrir = fopen($arquivo , "r");

    $dados = file($arquivo);

    $nome = trim($dados[0]);

    $cpf = trim($dados[1]);

    $loginSalvo = trim($dados[2]);

    $senhaSalva = trim($dados[3]);

    fclose($abrir);

    if(password_verify($senha , $senhaSalva)){

        $_SESSION['logado'] = "ok";

        $_SESSION['nome'] = $nome;

        $_SESSION['cpf'] = $cpf;

        $_SESSION['login'] = $loginSalvo;

        header("Location: vitrine.php");

    }

    else{

        echo "

        <script>

            alert('Senha incorreta!');

            window.location='login.php';

        </script>

        ";

    }

}

else{

    echo "

    <script>

        alert('Login não encontrado!');

        window.location='login.php';

    </script>

    ";

}

?>