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

<?php
require_once "app/layout.php";

$totalGeral = 0;
$qtdTotal = 0;
foreach ($itensCarrinho as $it) {
    $totalGeral += $it['preco'] * $it['quantidade'];
    $qtdTotal += $it['quantidade'];
}

ui_head('Carrinho');
ui_topo('carrinho');
?>

<section class="pagina-titulo">
    <div>
        <h1>Seu carrinho</h1>
        <?php if ($qtdTotal > 0): ?>
            <p class="sub"><?= $qtdTotal ?> <?= $qtdTotal === 1 ? 'item' : 'itens' ?></p>
        <?php endif; ?>
    </div>
</section>

<?php if (empty($itensCarrinho)): ?>
    <div class="vazio">
        <?= ui_icone('carrinho') ?>
        <h2>Seu carrinho está vazio</h2>
        <p>Adicione produtos para começar sua compra.</p>
        <a class="btn btn-primario" href="vitrine.php">Ver produtos</a>
    </div>
<?php else: ?>
    <div class="duas-colunas">
        <section class="lista-itens" aria-label="Itens do carrinho">
            <?php foreach ($itensCarrinho as $id => $item): ?>
                <article class="item-linha">
                    <?= ui_thumb($item['nome']) ?>
                    <div class="item-info">
                        <h3><?= ui_h($item['nome']) ?></h3>
                        <p class="unit"><?= ui_preco($item['preco']) ?> cada</p>
                        <div class="qtd">
                            <a href="carrinho.php?menos=<?= (int) $id ?>" aria-label="Diminuir quantidade">−</a>
                            <span><?= (int) $item['quantidade'] ?></span>
                            <a href="carrinho.php?mais=<?= (int) $id ?>" aria-label="Aumentar quantidade">+</a>
                        </div>
                    </div>
                    <div class="item-total">
                        <strong><?= ui_preco($item['preco'] * $item['quantidade']) ?></strong>
                        <a class="link-perigo" href="carrinho.php?remover=<?= (int) $id ?>">Remover</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <aside class="resumo">
            <h2>Resumo</h2>
            <dl>
                <div><dt>Itens</dt><dd><?= $qtdTotal ?></dd></div>
                <div class="total"><dt>Total</dt><dd><?= ui_preco($totalGeral) ?></dd></div>
            </dl>
            <a class="btn btn-primario btn-bloco btn-grande" href="confirma.php">Finalizar compra</a>
            <a class="btn btn-contorno btn-bloco" href="vitrine.php">Continuar comprando</a>
        </aside>
    </div>
<?php endif; ?>

<?php ui_rodape(); ?>
