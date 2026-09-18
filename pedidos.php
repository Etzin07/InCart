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

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Meus pedidos</title>
</head>

<body>

    <h1>Meus pedidos</h1>

    <?php while ($pedido = $resultado->fetch_assoc()): ?>

        <div>

            <h2>
                Pedido #<?php echo $pedido['id']; ?>
            </h2>

            <p>
                Data:
                <?php echo $pedido['data_pedido']; ?>
            </p>

            <p>
                Total:
                R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?>
            </p>

            <p>
                Pagamento:
                <?php echo htmlspecialchars($pedido['pagamento']); ?>
            </p>

            <p>
    Entrega:
    <?php echo htmlspecialchars($pedido['entrega']); ?>
</p>

<h3>Itens do pedido:</h3>

<?php

$pedidoId = $pedido['id'];

$consultaItens = "SELECT itens_pedido.*, produtos.nome
                  FROM itens_pedido
                  INNER JOIN produtos
                  ON itens_pedido.produto_id = produtos.id
                  WHERE itens_pedido.pedido_id = $pedidoId";

$resultadoItens = banco(
    $server,
    $user,
    $password,
    $db,
    $consultaItens
);

?>

<?php while ($item = $resultadoItens->fetch_assoc()): ?>

    <div>

        <p>
            Produto:
            <?php echo htmlspecialchars($item['nome']); ?>
        </p>

        <p>
            Quantidade:
            <?php echo $item['quantidade']; ?>
        </p>

        <p>
            Preço:
            R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?>
        </p>

    </div>

<?php endwhile; ?>

<hr>

        </div>

    <?php endwhile; ?>

</body>

</html>