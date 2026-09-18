<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }
include "app/cons.php";
require_once "app/DLL.php";

$nomeUsuario = $_SESSION['nome'];

require_once('produtos.php');

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

if ($busca != '') {
    foreach ($produtos as $id => $produto) {
        if (stripos($produto['nome'], $busca) === false) {
            unset($produtos[$id]);
        }
    }
}

$consulta = "SELECT * FROM produtos ORDER BY id";

$resultado = banco($server, $user, $password, $db, $consulta);

$produtos = [];

while ($linha = $resultado->fetch_assoc()) {

    $produtos[$linha['id']] = [
        "nome" => $linha['nome'],
        "preco" => $linha['preco']
    ];
}

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

    $cpf = $_SESSION['cpf'];

    $quantidade = $_SESSION['carrinho'][$id]["quantidade"];

    $sqlCarrinho = "INSERT INTO carrinho
        (cpf, produto_id, quantidade)
        VALUES
        ('$cpf', $id, $quantidade)
        ON DUPLICATE KEY UPDATE
        quantidade = $quantidade";

    $conexao = new mysqli($server, $user, $password, $db);

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco: " . $conexao->connect_error);
    }

    if (!$conexao->query($sqlCarrinho)) {
        die("Erro ao salvar carrinho: " . $conexao->error);
    }

    $conexao->close();
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

        $cpf = $_SESSION['cpf'];

        $sqlFavorito = "INSERT INTO favoritos
            (cpf, produto_id)
            VALUES
            ('$cpf', $id)
            ON DUPLICATE KEY UPDATE
            produto_id = $id";

        $conexao = new mysqli($server, $user, $password, $db);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco: " . $conexao->connect_error);
        }

        if (!$conexao->query($sqlFavorito)) {
            die("Erro ao salvar favorito: " . $conexao->error);
        }

        $conexao->close();
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

    <span>
        Olá, <?php echo htmlspecialchars($nomeUsuario); ?>!
    </span>

    <a href="index.php">Início</a>

    <a href="carrinho.php">Carrinho</a>

    <a href="favoritos.php">Favoritos</a>

    <a href="conta.php">Minha conta</a>

    <a href="logout.php">Sair</a>

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