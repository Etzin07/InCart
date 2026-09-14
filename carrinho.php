<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }

if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Remover item
if (isset($_GET['remover'])) {
    $id = (int) $_GET['remover'];
    unset($_SESSION['carrinho'][$id]);
    header("Location: carrinho.php");
    exit;
}

// Aumentar quantidade
if (isset($_GET['mais'])) {
    $id = (int) $_GET['mais'];
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade']++;
    }
    header("Location: carrinho.php");
    exit;
}

// Diminuir quantidade
if (isset($_GET['menos'])) {
    $id = (int) $_GET['menos'];
    if (isset($_SESSION['carrinho'][$id])) {
        $_SESSION['carrinho'][$id]['quantidade']--;

        if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
            unset($_SESSION['carrinho'][$id]);
        }
    }
    header("Location: carrinho.php");
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho - In Cart</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🛒 Seu Carrinho</h1>

        <div class="nav">
            <a href="vitrine.php">Continuar comprando</a>
            <a href="index.php">Início</a>
            <a href="favoritos.php">Favoritos</a>
        </div>
    </div>

    <?php if (empty($_SESSION['carrinho'])): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>

        <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>

            <?php $subtotal = $item['preco'] * $item['quantidade']; ?>
            <?php $total += $subtotal; ?>

            <div class="carrinho-item">

                <h3><?php echo $item['nome']; ?></h3>

                <p>
                    Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?><br>
                    Quantidade: <?php echo $item['quantidade']; ?><br>
                    Subtotal: R$ <?php echo number_format($subtotal, 2, ',', '.'); ?>
                </p>

                <div class="nav">
                    <a href="carrinho.php?menos=<?php echo $id; ?>">-</a>
                    <a href="carrinho.php?mais=<?php echo $id; ?>">+</a>
                    <a class="remover" href="carrinho.php?remover=<?php echo $id; ?>">Remover</a>
                </div>

            </div>

        <?php endforeach; ?>

        <h2>Total: R$ <?php echo number_format($total, 2, ',', '.'); ?></h2>

        <a class="finalizar" href="confirma.php">
            Finalizar compra
        </a>

    <?php endif; ?>

</div>

</body>
</html>