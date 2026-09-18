<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trocar Senha - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="container">

    <div class="header">

        <h1>🛒 In Cart</h1>

        <div class="nav">
            <a href="vitrine.php">Vitrine</a>
            <a href="conta.php">Minha conta</a>
            <a href="logout.php">Sair</a>
        </div>

    </div>

    <div class="cadastro-box">

        <h2>Trocar senha</h2>

        <form action="salvar_senha.php" method="POST">

            <input type="password"
                   name="senha_atual"
                   placeholder="Senha atual"
                   required>

            <input type="password"
                   name="nova_senha"
                   placeholder="Nova senha"
                   required>

            <input type="password"
                   name="confirma_senha"
                   placeholder="Confirme a nova senha"
                   required>

            <button type="submit">
                Alterar senha
            </button>

        </form>

        <br>

        <a href="conta.php">
            Voltar para minha conta
        </a>

    </div>

</div>

</body>

</html>
