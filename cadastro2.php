<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro de Login - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="cadastro-container">

        <form action="salvar_login.php"
              method="POST"
              class="cadastro-box">

            <h1>
                🛒 In Cart
            </h1>

            <h2>
                Criar Login
            </h2>

            <input type="text"
                   name="login"
                   placeholder="Crie um login"
                   required>

            <input type="password"
                   name="senha"
                   placeholder="Crie uma senha"
                   required>

            <button type="submit">

                Finalizar Cadastro

            </button>

        </form>

    </div>

</body>

</html>