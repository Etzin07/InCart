<?php
session_start();
if (!isset($_SESSION['admin_logado'])) { header('Location: admin_login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$erro = "";

// Adicionar produto novo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_nome'])) {

    $nome = trim($_POST['novo_nome']);
    $preco = str_replace(',', '.', trim($_POST['novo_preco']));

    if ($nome === '' || !is_numeric($preco) || (float) $preco <= 0) {
        $erro = "Preencha um nome e um preço válido (maior que zero).";
    } else {
        executarSeguro(
            $server, $user, $password, $db,
            "INSERT INTO produtos (nome, preco) VALUES (?, ?)",
            "sd",
            [$nome, (float) $preco]
        );
        header("Location: admin.php");
        exit;
    }
}

$resultadoProdutos = banco($server, $user, $password, $db, "SELECT * FROM produtos ORDER BY id");
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin - In Cart</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table.admin-tabela { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.admin-tabela th, table.admin-tabela td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        .admin-form-novo { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; align-items: center; }
        .admin-form-novo input { padding: 8px; }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>🛠️ Painel Admin - Produtos</h1>

        <div class="nav">
            <span>Olá, <?php echo htmlspecialchars($_SESSION['admin_usuario']); ?>!</span>
            <a href="vitrine.php">Ver loja</a>
            <a href="admin_logout.php">Sair</a>
        </div>
    </div>

    <?php if ($erro !== ""): ?>
        <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
    <?php endif; ?>

    <h3>Adicionar novo produto</h3>

    <form method="POST" class="admin-form-novo">
        <input type="text" name="novo_nome" placeholder="Nome do produto" required>
        <input type="text" name="novo_preco" placeholder="Preço (ex: 9,90)" required>
        <button type="submit">Adicionar</button>
    </form>

    <table class="admin-tabela">
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>

        <?php while ($p = $resultadoProdutos->fetch_assoc()): ?>
            <tr>
                <td><?php echo (int) $p['id']; ?></td>
                <td><?php echo htmlspecialchars($p['nome']); ?></td>
                <td>R$ <?php echo number_format((float) $p['preco'], 2, ',', '.'); ?></td>
                <td>
                    <a href="admin_editar_produto.php?id=<?php echo (int) $p['id']; ?>">Editar</a>
                    &nbsp;|&nbsp;
                    <a href="admin_excluir_produto.php?id=<?php echo (int) $p['id']; ?>"
                       onclick="return confirm('Tem certeza que quer apagar este produto?');"
                       style="color:red;">Excluir</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

</div>

</body>
</html>
