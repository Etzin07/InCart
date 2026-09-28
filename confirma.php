<?php
session_start();

if (!isset($_SESSION['logado'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['carrinho']) || count($_SESSION['carrinho']) == 0) {
    echo "Carrinho vazio!";
    exit;
}

$itens = $_SESSION['carrinho'];

$total = 0;

include "app/cons.php";
require_once "app/DLL.php";

$produtosBanco = [];

$consulta = "SELECT * FROM produtos";

$resultado = banco(
    $server,
    $user,
    $password,
    $db,
    $consulta
);

while ($linha = $resultado->fetch_assoc()) {

    $produtosBanco[$linha['id']] = [
        "nome" => $linha['nome'],
        "preco" => $linha['preco']
    ];
}

foreach ($itens as $id => $item) {

    if (!isset($produtosBanco[$id])) {
        continue;
    }

    $preco = $produtosBanco[$id]['preco'];

    $total += $preco * $item['quantidade'];
}

if (!isset($_POST['confirmar'])) {
?>

<?php
require_once "app/layout.php";
ui_head('Finalizar compra');
ui_topo('carrinho');
?>

<a class="link-voltar" href="carrinho.php">Voltar ao carrinho</a>

<section class="pagina-titulo">
    <div>
        <h1>Finalizar compra</h1>
        <p class="sub">Escolha como pagar e como quer receber.</p>
    </div>
</section>

<form method="post" class="duas-colunas">
    <div class="painel">
        <h2 class="bloco-titulo">Como você quer pagar?</h2>
        <div class="opcoes">
            <label class="opcao"><input type="radio" name="pagamento" value="Pix" required>Pix</label>
            <label class="opcao"><input type="radio" name="pagamento" value="Boleto">Boleto</label>
            <label class="opcao"><input type="radio" name="pagamento" value="Cartao Debito">Cartão de débito</label>
            <label class="opcao"><input type="radio" name="pagamento" value="Cartao Credito">Cartão de crédito</label>
            <label class="opcao"><input type="radio" name="pagamento" value="Fisico">Pagamento físico</label>
        </div>

        <h2 class="bloco-titulo">Como quer receber?</h2>
        <div class="opcoes">
            <label class="opcao"><input type="radio" name="entrega" value="entrega" required><span>Entrega<small>Receba no seu endereço</small></span></label>
            <label class="opcao"><input type="radio" name="entrega" value="retirada"><span>Retirada<small>Você busca o pedido</small></span></label>
        </div>
    </div>

    <aside class="resumo">
        <h2>Resumo do pedido</h2>
        <ul class="resumo-itens">
            <?php foreach ($itens as $id => $item): ?>
                <?php if (!isset($produtosBanco[$id])) continue; ?>
                <li>
                    <span><?= (int) $item['quantidade'] ?>× <?= ui_h($produtosBanco[$id]['nome']) ?></span>
                    <strong><?= ui_preco($produtosBanco[$id]['preco'] * $item['quantidade']) ?></strong>
                </li>
            <?php endforeach; ?>
        </ul>
        <dl><div class="total"><dt>Total</dt><dd><?= ui_preco($total) ?></dd></div></dl>
        <button class="btn btn-primario btn-bloco btn-grande" type="submit" name="confirmar" value="1">Confirmar compra</button>
    </aside>
</form>

<?php ui_rodape(); ?>
<?php
exit;
}

$cpf = $_SESSION['cpf'];

$entrega = $_POST['entrega'];
$pagamento = $_POST['pagamento'];


include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

$dataPedido = date("Y-m-d H:i:s");

$sqlPedido = "INSERT INTO pedidos
    (cpf, data_pedido, total, pagamento, entrega)
    VALUES
    ('$cpf', '$dataPedido', $total, '$pagamento', '$entrega')";

$conexao = new mysqli($server, $user, $password, $db);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

if (!$conexao->query($sqlPedido)) {
    die("Erro ao criar pedido: " . $conexao->error);
}

$pedidoId = $conexao->insert_id;

foreach ($itens as $id => $item) {

    if (!isset($produtosBanco[$id])) {
        continue;
    }

    $preco = $produtosBanco[$id]['preco'];
    $quantidade = $item['quantidade'];

    $sqlItem = "INSERT INTO itens_pedido
        (pedido_id, produto_id, quantidade, preco)
        VALUES
        ($pedidoId, $id, $quantidade, $preco)";

    if (!$conexao->query($sqlItem)) {
        die("Erro ao salvar item do pedido: " . $conexao->error);
    }
}

$conexao->close();

$cpf = $_SESSION['cpf'];

$conexao = new mysqli($server, $user, $password, $db);

if ($conexao->connect_error) {
    die("Erro na conexão com o banco: " . $conexao->connect_error);
}

$sqlLimparCarrinho = "DELETE FROM carrinho
                      WHERE cpf = '$cpf'";

if (!$conexao->query($sqlLimparCarrinho)) {
    die("Erro ao limpar carrinho: " . $conexao->error);
}

$conexao->close();

unset($_SESSION['carrinho']);
?>

<?php
require_once "app/layout.php";
ui_head('Pedido confirmado');
ui_topo('carrinho');
?>

<div class="painel painel-estreito">
    <div class="selo-ok"><?= ui_icone('ok') ?></div>
    <h1>Pedido confirmado!</h1>
    <p class="sub">Pedido #<?= (int) $pedidoId ?> registrado com sucesso.</p>

    <ul class="resumo-itens" style="margin-top:22px">
        <?php foreach ($itens as $id => $item): ?>
            <?php if (!isset($produtosBanco[$id])) continue; ?>
            <li>
                <span><?= (int) $item['quantidade'] ?>× <?= ui_h($produtosBanco[$id]['nome']) ?></span>
                <strong><?= ui_preco($produtosBanco[$id]['preco'] * $item['quantidade']) ?></strong>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="resumo resumo-plano">
        <dl>
            <div class="total"><dt>Total</dt><dd><?= ui_preco($total) ?></dd></div>
            <div><dt>Recebimento</dt><dd><?= ui_h(ucfirst($entrega)) ?></dd></div>
            <div><dt>Pagamento</dt><dd><?= ui_h($pagamento) ?></dd></div>
        </dl>
    </div>

    <div class="form-acoes" style="margin-top:22px">
        <a class="btn btn-primario" href="pedidos.php">Meus pedidos</a>
        <a class="btn btn-contorno" href="vitrine.php">Continuar comprando</a>
    </div>
</div>

<?php ui_rodape(); ?>
