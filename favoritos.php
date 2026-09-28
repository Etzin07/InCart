<?php
session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }

// Estes dois includes não existiam nesta página. Sem eles, $server/$user/
// $password/$db e a função banco() não existiam aqui — a página não tinha
// como funcionar de verdade.
include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

if (isset($_GET['remover'])) {
    $id = (int) $_GET['remover'];

    executarSeguro(
        $server, $user, $password, $db,
        "DELETE FROM favoritos WHERE cpf = ? AND produto_id = ?",
        "si",
        [$cpf, $id]
    );

    header("Location: favoritos.php");
    exit;
}

// Busca os favoritos já com nome/preço atual via JOIN — não depende mais
// de um array $produtos que nunca estava carregado nesta página.
$itensFavoritos = [];

$resultadoFavoritos = bancoSeguro(
    $server, $user, $password, $db,
    "SELECT p.id, p.nome, p.preco
     FROM favoritos f
     JOIN produtos p ON p.id = f.produto_id
     WHERE f.cpf = ?
     ORDER BY p.id",
    "s",
    [$cpf]
);

while ($linha = $resultadoFavoritos->fetch_assoc()) {
    $itensFavoritos[(int) $linha['id']] = [
        "nome" => $linha['nome'],
        "preco" => (float) $linha['preco'],
    ];
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
<a href="logout.php">Sair</a>
</div>
</div>

<?php if (empty($itensFavoritos)): ?>

<p>Nenhum produto favoritado.</p>

<?php else: ?>

<?php foreach ($itensFavoritos as $id => $item): ?>

<div class="produto-card">

<h3><?php echo htmlspecialchars($item['nome']); ?></h3>

<p>
Preço: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
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
