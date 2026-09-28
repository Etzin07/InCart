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

<?php
require_once "app/layout.php";
ui_head('Produtos');
ui_topo('produtos');
$qtdProdutos = count($produtos);
?>

<section class="pagina-titulo">
    <div>
        <h1>Produtos</h1>
        <p class="sub">
            <?= $qtdProdutos ?> <?= $qtdProdutos === 1 ? 'item' : 'itens' ?>
            <?php if ($busca !== ''): ?> para “<?= ui_h($busca) ?>”<?php endif; ?>
        </p>
    </div>
</section>

<form class="busca" method="get" role="search">
    <?= ui_icone('busca') ?>
    <input type="search" name="busca" placeholder="Buscar produtos" aria-label="Buscar produtos" value="<?= ui_h($busca) ?>">
</form>

<?php if (empty($produtos)): ?>
    <div class="vazio">
        <?= ui_icone('busca') ?>
        <h2>Nenhum produto encontrado</h2>
        <p>Tente outra palavra ou veja todos os produtos.</p>
        <a class="btn btn-primario" href="vitrine.php">Ver todos</a>
    </div>
<?php else: ?>
    <div class="grade">
        <?php foreach ($produtos as $id => $p): ?>
            <?= ui_card($id, $p['nome'], $p['preco']) ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php ui_rodape(); ?>
