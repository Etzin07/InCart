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

    <title>Editar Conta - In Cart</title>

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

        <h2>Editar informações</h2>

        <form action="salvar_edicao.php" method="POST">

            <input type="text"
                   name="nome"
                   value="<?php echo htmlspecialchars($usuario['nome']); ?>"
                   placeholder="Nome completo"
                   required>

            <input type="text"
                   name="endereco"
                   value="<?php echo htmlspecialchars($usuario['endereco']); ?>"
                   placeholder="Endereço"
                   required>

            <input type="text"
                   name="bairro"
                   value="<?php echo htmlspecialchars($usuario['bairro']); ?>"
                   placeholder="Bairro"
                   required>

            <input type="text"
                   name="cidade"
                   value="<?php echo htmlspecialchars($usuario['cidade']); ?>"
                   placeholder="Cidade"
                   required>

            <input type="text"
                   name="estado"
                   value="<?php echo htmlspecialchars($usuario['estado']); ?>"
                   placeholder="Estado"
                   required>

            <input type="text"
                   name="cep"
                   value="<?php echo htmlspecialchars($usuario['cep']); ?>"
                   placeholder="CEP"
                   required>

            <button type="submit">
                Salvar alterações
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
