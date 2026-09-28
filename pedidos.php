<?php

session_start();

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

$consulta = "SELECT *
             FROM pedidos
             WHERE cpf = '$cpf'
             ORDER BY data_pedido DESC";

$resultado = banco(
    $server,
    $user,
    $password,
    $db,
    $consulta
);

?>

<?php
require_once "app/layout.php";
ui_head('Meus pedidos');
ui_topo('conta');
?>

<a class="link-voltar" href="conta.php">Voltar para minha conta</a>

<section class="pagina-titulo">
    <div>
        <h1>Meus pedidos</h1>
        <p class="sub">Histórico das suas compras.</p>
    </div>
</section>

<?php if ($resultado->num_rows === 0): ?>
    <div class="vazio">
        <?= ui_icone('pedidos') ?>
        <h2>Você ainda não fez pedidos</h2>
        <p>Quando finalizar uma compra, ela aparece aqui.</p>
        <a class="btn btn-primario" href="vitrine.php">Ver produtos</a>
    </div>
<?php endif; ?>

<?php while ($pedido = $resultado->fetch_assoc()): ?>
    <?php
    $itensPedido = bancoSeguro(
        $server, $user, $password, $db,
        "SELECT ip.quantidade, ip.preco, p.nome
         FROM itens_pedido ip
         JOIN produtos p ON p.id = ip.produto_id
         WHERE ip.pedido_id = ?",
        "i",
        [(int) $pedido['id']]
    );
    ?>
    <article class="pedido">
        <div class="pedido-topo">
            <div>
                <h2>Pedido #<?= (int) $pedido['id'] ?></h2>
                <p class="pedido-data"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
            </div>
            <div class="chips">
                <span class="chip"><?= ui_h($pedido['pagamento']) ?></span>
                <span class="chip"><?= ui_h(ucfirst($pedido['entrega'])) ?></span>
            </div>
            <div class="pedido-total"><?= ui_preco($pedido['total']) ?></div>
        </div>
        <div class="pedido-itens">
            <?php while ($item = $itensPedido->fetch_assoc()): ?>
                <div class="pedido-item">
                    <div><?= ui_h($item['nome']) ?> <span>× <?= (int) $item['quantidade'] ?></span></div>
                    <strong><?= ui_preco($item['preco'] * $item['quantidade']) ?></strong>
                </div>
            <?php endwhile; ?>
        </div>
    </article>
<?php endwhile; ?>

<?php ui_rodape(); ?>
