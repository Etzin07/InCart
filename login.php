<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="login-container">

        <form action="processa_login.php" method="POST" class="login-box">

            <h1>
                🛒 In Cart
            </h1>

            <h2>
                Login
            </h2>

            <input type="text" 
                   name="login" 
                   placeholder="Digite seu login"
                   required>

            <input type="password" 
                   name="senha" 
                   placeholder="Digite sua senha"
                   required>

            <button type="submit">
                Entrar
            </button>

            <a href="cadastro1.php" class="cadastro-link">

                Cadastrar novo usuário

            </a>

        </form>

    </div>

</body>

</html>