<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

// Remover item
if (isset($_GET['remover'])) {
    $id = (int) $_GET['remover'];

    // Antes: "DELETE FROM carrinho WHERE cpf = '$cpf' AND produto_id = $id"
    // montado por concatenação de string = SQL Injection. Agora com
    // prepared statement, $cpf e $id nunca fazem parte do texto do SQL.
    executarSeguro(
        $server, $user, $password, $db,
        "DELETE FROM carrinho WHERE cpf = ? AND produto_id = ?",
        "si",
        [$cpf, $id]
    );

    header("Location: carrinho.php");
    exit;
}

// Aumentar quantidade
if (isset($_GET['mais'])) {
    $id = (int) $_GET['mais'];

    executarSeguro(
        $server, $user, $password, $db,
        "UPDATE carrinho SET quantidade = quantidade + 1 WHERE cpf = ? AND produto_id = ?",
        "si",
        [$cpf, $id]
    );

    header("Location: carrinho.php");
    exit;
}

// Diminuir quantidade
if (isset($_GET['menos'])) {
    $id = (int) $_GET['menos'];

    executarSeguro(
        $server, $user, $password, $db,
        "UPDATE carrinho SET quantidade = quantidade - 1 WHERE cpf = ? AND produto_id = ?",
        "si",
        [$cpf, $id]
    );

    // Se a quantidade zerou ou ficou negativa, remove a linha
    executarSeguro(
        $server, $user, $password, $db,
        "DELETE FROM carrinho WHERE cpf = ? AND quantidade <= 0",
        "s",
        [$cpf]
    );

    header("Location: carrinho.php");
    exit;
}

// Busca os itens do carrinho já com nome/preço atual do produto (JOIN),
// então se um produto for apagado no admin, ele some do carrinho sozinho
// em vez de gerar erro.
$itensCarrinho = [];

$resultadoCarrinho = bancoSeguro(
    $server, $user, $password, $db,
    "SELECT c.produto_id, c.quantidade, p.nome, p.preco
     FROM carrinho c
     JOIN produtos p ON p.id = c.produto_id
     WHERE c.cpf = ?
     ORDER BY c.produto_id",
    "s",
    [$cpf]
);

while ($linha = $resultadoCarrinho->fetch_assoc()) {
    $itensCarrinho[(int) $linha['produto_id']] = [
        "nome" => $linha['nome'],
        "preco" => (float) $linha['preco'],
        "quantidade" => (int) $linha['quantidade'],
    ];
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
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <?php if (empty($itensCarrinho)): ?>
        <p>Seu carrinho está vazio.</p>
    <?php else: ?>

        <?php foreach ($itensCarrinho as $id => $item): ?>

            <?php $subtotal = $item['preco'] * $item['quantidade']; ?>
            <?php $total += $subtotal; ?>

            <div class="carrinho-item">

                <h3><?php echo htmlspecialchars($item['nome']); ?></h3>

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

        <a class="finalizar" href="confirma.php">Finalizar compra</a>
        <a href="pedidos.php">Meus pedidos</a>

    <?php endif; ?>

</div>

</body>
</html>
