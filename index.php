<?php 

session_start();
if (!isset($_SESSION['logado'])) { header('Location: login.php'); exit; }
include "app/cons.php";
require_once "app/DLL.php";

$nomeUsuario = $_SESSION['nome']; 

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>InCart</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="topo">

    <div class="logo">
        <img src="imgs/15.png" alt="logo" style="height: 105px; vertical-align: middle;">
        InCart
    </div>

<form action="vitrine.php" method="GET">
    <input type="text" name="busca" placeholder="Pesquisar produto">
    <button type="submit">Pesquisar</button>
</form>

    <span>
        Olá, <?php echo htmlspecialchars($nomeUsuario); ?>!
    </span>

    <div class="icones">
        <a href="vitrine.php">Produtos</a>
        <a href="favoritos.php">Favoritos</a>
        <a href="carrinho.php">Carrinho</a>
        <a href="conta.php">Minha conta</a>
        <a href="logout.php">Sair</a>
    </div>

</div>

<div class="container">

    <div class="header">

        <h1>Seu mercado favorito a um clique de você!</h1>

        <p>
            Compre produtos direto do conforto  da sua casa!
        </p>

    </div>

</div>

 <main class="produtos">

        <div class="card">

            <img src="imgs/12.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Arroz 5kg</h3>

            <p>R$ 25,90</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/13.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Feijão Preto 1kg</h3>

            <p>R$ 8,50</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/14.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Feijão Carica 1kg</h3>

            <p>R$ 8,50</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/8.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Macarrão</h3>

            <p>R$ 4,99</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/6.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Óleo de Soja Soya</h3>

            <p>R$ 6,99</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

         <div class="card">

            <img src="imgs/7.png" alt="card" style="height: 340px; vertical-align:middle">

            <h3>Óleo de Soja Liza</h3>

            <p>R$ 5,99</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/1.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Açucar Mascavo 1kg</h3>

            <p>R$ 5,49</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

          <div class="card">

            <img src="imgs/2.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Açucar Orgânico 1kg</h3>

            <p>R$ 7,49</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

         <div class="card">

            <img src="imgs/3.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Açucar refinado 1kg</h3>

            <p>R$ 5,99</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

         <div class="card">

            <img src="imgs/4.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Açucar Cristal 1kg</h3>

            <p>R$ 5,49</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

         <div class="card">

            <img src="imgs/9.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Feijão Fradinho pronto</h3>

            <p>R$ 6,39</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

         <div class="card">

            <img src="imgs/11.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Feijão Preto pronto</h3>

            <p>R$ 6,39</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>

        <div class="card">

            <img src="imgs/10.png" alt="card" style="height: 340px; vertical-align:middle ">

            <h3>Feijão pronto</h3>

            <p>R$ 6,39</p>

            <a href="login.php">

                <button>
                    Comprar
                </button>

            </a>

        </div>


 </main>

    <script>
         
        let botao = document.querySelector('.menu-toggle');

        let menu = document.querySelector('.categorias');

        botao.addEventListener('click', () => {

            menu.classList.toggle('mostrar');

        });
    </script>
</body>

</html>