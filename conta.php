<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

$consulta = "SELECT * FROM usuarios WHERE cpf = '$cpf'";

$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit;
}

$usuario = $resultado->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Minha Conta - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>🛒 In Cart</h1>

        <div class="nav">

            <a href="vitrine.php">Vitrine</a>

            <a href="carrinho.php">Carrinho</a>

            <a href="favoritos.php">Favoritos</a>

            <a href="logout.php">Sair</a>

        </div>

    </div>


    <div class="cadastro-box">

        <h2>Minha Conta</h2>

        <p><strong>Nome:</strong> <?php echo htmlspecialchars($usuario['nome']); ?></p>

        <p><strong>CPF:</strong> <?php echo htmlspecialchars($usuario['cpf']); ?></p>

        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($usuario['endereco']); ?></p>

        <p><strong>Bairro:</strong> <?php echo htmlspecialchars($usuario['bairro']); ?></p>

        <p><strong>Cidade:</strong> <?php echo htmlspecialchars($usuario['cidade']); ?></p>

        <p><strong>Estado:</strong> <?php echo htmlspecialchars($usuario['estado']); ?></p>

        <p><strong>CEP:</strong> <?php echo htmlspecialchars($usuario['cep']); ?></p>


        <br>

        <a href="editar_conta.php">
            <button type="button">Editar informações</button>
        </a>
        
        <br><br>

        <a href="trocar_senha.php">
          <button type="button">Trocar senha</button>
        </a>
    </div>

</div>

</body>

</html>
