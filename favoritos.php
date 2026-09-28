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

<?php
require_once "app/layout.php";
ui_head('Favoritos');
ui_topo('favoritos');
?>

<section class="pagina-titulo">
    <div>
        <h1>Favoritos</h1>
        <p class="sub">Os produtos que você salvou.</p>
    </div>
</section>

<?php if (empty($itensFavoritos)): ?>
    <div class="vazio">
        <?= ui_icone('favoritos') ?>
        <h2>Nenhum favorito ainda</h2>
        <p>Toque no coração de um produto para guardá-lo aqui.</p>
        <a class="btn btn-primario" href="vitrine.php">Ver produtos</a>
    </div>
<?php else: ?>
    <div class="grade">
        <?php foreach ($itensFavoritos as $id => $item): ?>
            <?= ui_card($id, $item['nome'], $item['preco'], 'fav') ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php ui_rodape(); ?>
