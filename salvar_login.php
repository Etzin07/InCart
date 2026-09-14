<?php

session_start();

$login = $_POST['login'];

$senha = $_POST['senha'];

$senhaCriptografada = password_hash($senha , PASSWORD_DEFAULT);

$nome = $_SESSION['nome'];

$cpf = $_SESSION['cpf'];

if(!is_dir("login")){

    mkdir("login");

}

$arquivo = "login/".$login.".dat";

$dados = $nome."\n";
$dados .= $cpf."\n";
$dados .= $login."\n";
$dados .= $senhaCriptografada."\n";

$abrir = fopen($arquivo , "w");

fwrite($abrir , $dados);

fclose($abrir);

echo "

    <script>

        alert('Cadastro realizado com sucesso!');

        window.location='login.php';

    </script>

";

?>