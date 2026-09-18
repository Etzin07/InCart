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

        <a href="pedidos.php" class="btn-pedidos">
            
        Meus pedidos
        
        </a>

        <?php foreach ($itens as $id => $item): ?>

    <?php

    if (!isset($produtosBanco[$id])) {
        continue;
    }

    $produto = $produtosBanco[$id];

    $nome = $produto['nome'];
    $preco = $produto['preco'];
    $quantidade = $item['quantidade'];

    ?>

    <div class="carrinho-item">

        <h3>
            <?php echo htmlspecialchars($nome); ?>
        </h3>

        <p>
            Quantidade:
            <?php echo $quantidade; ?>
        </p>

        <p>
            Preço:
            R$ <?php echo number_format($preco, 2, ',', '.'); ?>
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