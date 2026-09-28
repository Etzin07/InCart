<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$nomeUsuario = $_SESSION['nome'];
$cpf = $_SESSION['cpf'];

require_once('produtos.php'); // já vem do banco, com todos os produtos

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

        $quantidade = $_SESSION['carrinho'][$id]["quantidade"];

        // Antes: string SQL montada colando $cpf e $id direto (SQL Injection).
        // Agora: prepared statement, os valores nunca viram parte do comando SQL.
        executarSeguro(
            $server, $user, $password, $db,
            "INSERT INTO carrinho (cpf, produto_id, quantidade)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE quantidade = ?",
            "siii",
            [$cpf, $id, $quantidade, $quantidade]
        );
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

        executarSeguro(
            $server, $user, $password, $db,
            "INSERT INTO favoritos (cpf, produto_id)
             VALUES (?, ?)
             ON DUPLICATE KEY UPDATE produto_id = ?",
            "sii",
            [$cpf, $id, $id]
        );
    }

    header("Location: vitrine.php");
    exit;
}

// Busca (agora aplicada DEPOIS de carregar os produtos do banco — antes o
// filtro rodava antes de $produtos ser sobrescrito pela consulta, então
// nunca tinha efeito nenhum na página).
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca !== '') {
    foreach ($produtos as $id => $produto) {
        if (stripos($produto['nome'], $busca) === false) {
            unset($produtos[$id]);
        }
    }
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
            <span>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>!</span>
            <a href="index.php">Início</a>
            <a href="carrinho.php">Carrinho</a>
            <a href="favoritos.php">Favoritos</a>
            <a href="conta.php">Minha conta</a>
            <a href="logout.php">Sair</a>
        </div>
    </div>

    <form method="GET" class="pesquisa">
        <input type="text" name="busca" placeholder="Pesquisar produtos..." value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit">Buscar</button>
    </form>

    <div class="produtos">

        <?php if (empty($produtos)): ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>

        <?php foreach ($produtos as $id => $p): ?>

            <div class="produto-card">

                <div class="produto-nome">
                    <?php echo htmlspecialchars($p["nome"]); ?>
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
