<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }
require_once('produtos.php');

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [];
}

// Adicionar ao carrinho
if (isset($_POST['add'])) {
    $id = (int) $_POST['add'];

    if (isset($produtos[$id])) {

        if (!isset($_SESSION['carrinho'][$id])) {
            $_SESSION['carrinho'][$id] = [
                "nome" => $produtos[$id]["nome"],
                "preco" => $produtos[$id]["preco"],
                "quantidade" => 1
            ];
        } else {
            $_SESSION['carrinho'][$id]["quantidade"]++;
        }
    }

    header("Location: vitrine.php");
    exit;
}


// Adicionar aos favoritos
if (isset($_POST['favorito'])) {
    $id = (int) $_POST['favorito'];

    if (isset($produtos[$id])) {

        $_SESSION['favoritos'][$id] = [
            "nome" => $produtos[$id]["nome"],
            "preco" => $produtos[$id]["preco"]
        ];
    }

    header("Location: vitrine.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Vitrine - In Cart</title>

    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🛒 In Cart</h1>

        <div class="nav">
            <a href="index.php">Início</a>
            <a href="carrinho.php">Carrinho</a>
            <a href="favoritos.php">Favoritos</a>
        </div>
    </div>

    <div class="produtos">

        <?php foreach ($produtos as $id => $p): ?>
            
            <div class="produto-card">

                <div class="produto-nome">
                    <?php echo $p["nome"]; ?>
                </div>

                <div class="produto-preco">
                    R$ <?php echo number_format($p["preco"], 2, ',', '.'); ?>
                </div>

                <form method="POST"><input type="hidden" name="add" value="<?php echo $id; ?>"><button type="submit">Adicionar ao carrinho</button></form>

                <br><br>

                <form method="POST"><input type="hidden" name="favorito" value="<?php echo $id; ?>"><button type="submit">Favoritar</button></form>

            </div>

        <?php endforeach; ?>

    </div>

</div>

</body>
</html>