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

foreach ($itens as $item) {
    $total += $item['preco'] * $item['quantidade'];
}

if (!isset($_POST['confirmar'])) {
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="confirmacao-box">

        <h1>Finalizar Compra</h1>

        <p>
            Total da compra:
            <strong>
                R$ <?php echo number_format($total, 2, ',', '.'); ?>
            </strong>
        </p>

        <form method="post">

        <h3>Método de Pagamento</h3>

<select name="pagamento" class="campo-pagamento" required>
    <option value="">Selecione...</option>
    <option value="Pix">💸 Pix</option>
    <option value="Boleto">📄 Boleto</option>
    <option value="Cartao Debito">💳 Cartão de Débito</option>
    <option value="Cartao Credito">🏦 Cartão de Crédito</option>
    <option value="Fisico">🏪 Pagamento Físico</option>
</select>
            <br><br>

            <h3>Recebimento</h3>
<div class="opcoes-recebimento">

    <label class="opcao-recebimento">
        <input type="radio" name="entrega" value="entrega" required>
        🚚 Entrega
    </label>

    <label class="opcao-recebimento">
        <input type="radio" name="entrega" value="retirada">
        📦 Retirada
    </label>

</div>


            <br><br>

            <button type="submit" name="confirmar">
                Confirmar Compra
            </button>

        </form>

    </div>

</div>

</body>

</html>

<?php
exit;
}

$entrega = $_POST['entrega'];
$pagamento = $_POST['pagamento'];

$pedido = [
    "data" => date("d/m/Y H:i:s"),
    "itens" => $itens,
    "total" => $total,
    "entrega" => $entrega,
    "pagamento" => $pagamento
];

$arquivo = "pedidos.dat";

$pedidos = [];

if (file_exists($arquivo) && filesize($arquivo) > 0) {

    $abrir = fopen($arquivo, "r");

    $conteudo = fread($abrir, filesize($arquivo));

    fclose($abrir);

    $pedidos = unserialize($conteudo);
}

$pedidos[] = $pedido;

file_put_contents($arquivo, serialize($pedidos));

unset($_SESSION['carrinho'], $_SESSION['favoritos']);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <div class="confirmacao-box">

        <h1>Pedido Confirmado!</h1>

        <p>
            Seu pedido foi registrado com sucesso.
        </p>

        <br>

        <h3>Resumo do Pedido</h3>

        <br>

        <?php foreach ($itens as $item): ?>

            <div class="carrinho-item">

                <h3>
                    <?php echo $item['nome']; ?>
                </h3>

                <p>
                    Quantidade:
                    <?php echo $item['quantidade']; ?>
                </p>

                <p>
                    Preço:
                    R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
                </p>

            </div>

        <?php endforeach; ?>

        <br>

        <p>
            <strong>Total:</strong>
            R$ <?php echo number_format($total, 2, ',', '.'); ?>
        </p>

        <p>
            <strong>Recebimento:</strong>
            <?php echo ucfirst($entrega); ?>
        </p>

        <p>
            <strong>Pagamento:</strong>
            <?php echo $pagamento; ?>
        </p>

        <br>

        <a class="btn-voltar" href="index.php">
            Voltar à Loja
        </a>

    </div>

</div>

</body>

</html>