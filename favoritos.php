<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }

if (!isset($_SESSION['favoritos'])) {
    $_SESSION['favoritos'] = [];
}

$cpf = $_SESSION['cpf'];

$consultaFavoritos = "SELECT produto_id
                       FROM favoritos
                       WHERE cpf = '$cpf'";

$resultadoFavoritos = banco(
    $server,
    $user,
    $password,
    $db,
    $consultaFavoritos
);

while ($linha = $resultadoFavoritos->fetch_assoc()) {

    $id = $linha['produto_id'];

    if (isset($produtos[$id])) {

        $_SESSION['favoritos'][$id] = [
            "nome" => $produtos[$id]["nome"],
            "preco" => $produtos[$id]["preco"]
        ];
    }
}

if (isset($_GET['remover'])) {

    $id = (int) $_GET['remover'];

    unset($_SESSION['favoritos'][$id]);

    $cpf = $_SESSION['cpf'];

    $conexao = new mysqli($server, $user, $password, $db);

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco: " . $conexao->connect_error);
    }

    $sql = "DELETE FROM favoritos
            WHERE cpf = '$cpf'
            AND produto_id = $id";

    if (!$conexao->query($sql)) {
        die("Erro ao remover favorito: " . $conexao->error);
    }

    $conexao->close();

    header("Location: favoritos.php");

    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Favoritos</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

<div class="header">
<h1>⭐ Favoritos</h1>

<div class="nav">
<a href="vitrine.php">Vitrine</a>
<a href="carrinho.php">Carrinho</a>
</div>
</div>

<?php if(empty($_SESSION['favoritos'])): ?>

<p>Nenhum produto favoritado.</p>

<?php else: ?>

<?php foreach($_SESSION['favoritos'] as $id => $item): ?>

<div class="produto-card">

<h3><?php echo $item['nome']; ?></h3>

<p>
Preço: R$ <?php echo number_format($item['preco'],2,',','.'); ?>
</p>

<a href="favoritos.php?remover=<?php echo $id; ?>">
Remover
</a>

</div>

<?php endforeach; ?>

<?php endif; ?>

</div>

</body>
</html>
