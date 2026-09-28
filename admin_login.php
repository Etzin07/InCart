<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - In Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <form action="admin_processa_login.php" method="POST" class="login-box">

        <h1>🛒 In Cart</h1>
        <h2>Painel Administrativo</h2>

        <input type="text" name="usuario" placeholder="Usuário admin" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <button type="submit">Entrar</button>

    </form>
</div>

</body>
</html>
