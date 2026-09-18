<?php

session_start();

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";


if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

$cpf = $_SESSION['cpf'];

$consultaCarrinho = "SELECT produto_id, quantidade 
                      FROM carrinho 
                      WHERE cpf = '$cpf'";

$resultadoCarrinho = banco(
    $server,
    $user,
    $password,
    $db,
    $consultaCarrinho
);

while ($linha = $resultadoCarrinho->fetch_assoc()) {

    $_SESSION['carrinho'][$linha['produto_id']] = [
        "quantidade" => $linha['quantidade']
    ];
}

// Remover item
if (isset($_GET['remover'])) {

    $id = (int) $_GET['remover'];

    unset($_SESSION['carrinho'][$id]);

    $cpf = $_SESSION['cpf'];

    $conexao = new mysqli($server, $user, $password, $db);

    if ($conexao->connect_error) {
        die("Erro na conexão com o banco: " . $conexao->connect_error);
    }

    $sql = "DELETE FROM carrinho
            WHERE cpf = '$cpf'
            AND produto_id = $id";

    if (!$conexao->query($sql)) {
        die("Erro ao remover item do carrinho: " . $conexao->error);
    }

    $conexao->close();

    header("Location: carrinho.php");
    exit;
}


// Aumentar quantidade
if (isset($_GET['mais'])) {

    $id = (int) $_GET['mais'];

    if (isset($_SESSION['carrinho'][$id])) {

        $_SESSION['carrinho'][$id]['quantidade']++;

        $cpf = $_SESSION['cpf'];
        $quantidade = $_SESSION['carrinho'][$id]['quantidade'];

        $conexao = new mysqli($server, $user, $password, $db);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco: " . $conexao->connect_error);
        }

        $sql = "UPDATE carrinho
                SET quantidade = $quantidade
                WHERE cpf = '$cpf'
                AND produto_id = $id";

        if (!$conexao->query($sql)) {
            die("Erro ao atualizar carrinho: " . $conexao->error);
        }

        $conexao->close();
    }

    header("Location: carrinho.php");
    exit;
}


// Diminuir quantidade
if (isset($_GET['menos'])) {

    $id = (int) $_GET['menos'];

    if (isset($_SESSION['carrinho'][$id])) {

        $_SESSION['carrinho'][$id]['quantidade']--;

        $cpf = $_SESSION['cpf'];

        $conexao = new mysqli($server, $user, $password, $db);

        if ($conexao->connect_error) {
            die("Erro na conexão com o banco: " . $conexao->connect_error);
        }

        if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {

            unset($_SESSION['carrinho'][$id]);

            $sql = "DELETE FROM carrinho
                    WHERE cpf = '$cpf'
                    AND produto_id = $id";

        } else {

            $quantidade = $_SESSION['carrinho'][$id]['quantidade'];

            $sql = "UPDATE carrinho
                    SET quantidade = $quantidade
                    WHERE cpf = '$cpf'
                    AND produto_id = $id";
        }

        if (!$conexao->query($sql)) {
            die("Erro ao atualizar carrinho: " . $conexao->error);
        }

        $conexao->close();
    }

    header("Location: carrinho.php");
    exit;
}

// Buscar produtos no banco
$produtosBanco = [];

if (!empty($_SESSION['carrinho'])) {

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
}


$total = 0;

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <title>Carrinho - In Cart</title>

    <link rel="stylesheet" href="style.css">

</head>


<body>

<div class="container">


    <div class="header">

        <h1>🛒 Seu Carrinho</h1>

        <div class="nav">

            <a href="vitrine.php">
                Continuar comprando
            </a>

            <a href="index.php">
                Início
            </a>

            <a href="favoritos.php">
                Favoritos
            </a>

        </div>

    </div>


    <?php if (empty($_SESSION['carrinho'])): ?>

        <p>
            Seu carrinho está vazio.
        </p>


    <?php else: ?>


        <?php foreach ($_SESSION['carrinho'] as $id => $item): ?>


            <?php

            // Verifica se o produto existe no banco
            if (!isset($produtosBanco[$id])) {
                continue;
            }

            $produto = $produtosBanco[$id];

            $nome = $produto['nome'];

            $preco = $produto['preco'];

            $quantidade = $item['quantidade'];

            $subtotal = $preco * $quantidade;

            $total += $subtotal;

            ?>


            <div class="carrinho-item">


                <h3>

                    <?php echo $nome; ?>

                </h3>


                <p>

                    Preço:

                    R$

                    <?php

                    echo number_format(
                        $preco,
                        2,
                        ',',
                        '.'
                    );

                    ?>

                    <br>


                    Quantidade:

                    <?php echo $quantidade; ?>


                    <br>


                    Subtotal:

                    R$

                    <?php

                    echo number_format(
                        $subtotal,
                        2,
                        ',',
                        '.'
                    );

                    ?>

                </p>


                <div class="nav">


                    <a href="carrinho.php?menos=<?php echo $id; ?>">
                        -
                    </a>


                    <a href="carrinho.php?mais=<?php echo $id; ?>">
                        +
                    </a>


                    <a
                        class="remover"
                        href="carrinho.php?remover=<?php echo $id; ?>"
                    >
                        Remover
                    </a>


                </div>


            </div>


        <?php endforeach; ?>


        <h2>

            Total:

            R$

            <?php

            echo number_format(
                $total,
                2,
                ',',
                '.'
            );

            ?>

        </h2>


        <a class="finalizar" href="confirma.php">

            Finalizar compra

        </a>
        <a href="pedidos.php">

            Meus pedidos
            
        </a>

    <?php endif; ?>


</div>

</body>

</html>