<?php 

session_start();
include "app/cons.php";
require_once "app/DLL.php";


?>

<?php
require_once "app/layout.php";
require_once "produtos.php";

$logado = isset($_SESSION['logado']) && $_SESSION['logado'] === 'ok';
$primeiroNome = $logado ? explode(' ', trim($_SESSION['nome']))[0] : '';
$destaques = array_slice($produtos, 0, 8, true);

ui_head('Início');
ui_topo('inicio');
?>

<section class="hero">
    <div class="hero-texto">
        <?php if ($logado): ?>
            <p class="hero-oi">Olá, <?= ui_h($primeiroNome) ?>!</p>
        <?php endif; ?>
        <h1>Seu mercado favorito a um clique de você.</h1>
        <p class="hero-sub">Arroz, feijão, óleo, açúcar e o básico da despensa. Escolha, pague do seu jeito e receba em casa ou retire.</p>
        <div class="hero-cta">
            <a class="btn btn-primario btn-grande" href="<?= $logado ? 'vitrine.php' : 'login.php' ?>">Ver produtos</a>
            <?php if ($logado): ?>
                <a class="btn btn-contorno btn-grande" href="carrinho.php">Ir para o carrinho</a>
            <?php else: ?>
                <a class="btn btn-contorno btn-grande" href="cadastro1.php">Criar conta</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-arte" aria-hidden="true">
        <div class="hero-prod"><?= ui_thumb('Arroz') ?></div>
        <div class="hero-prod"><?= ui_thumb('Feijão Preto 1kg') ?></div>
        <div class="hero-prod"><?= ui_thumb('Óleo de Soja Soya') ?></div>
    </div>
</section>

<section class="vantagens">
    <div class="vantagem">
        <span class="vantagem-ico"><?= ui_icone('entrega') ?></span>
        <div><h3>Entrega ou retirada</h3><p>Receba no seu endereço ou passe para buscar.</p></div>
    </div>
    <div class="vantagem">
        <span class="vantagem-ico"><?= ui_icone('pix') ?></span>
        <div><h3>Pague do seu jeito</h3><p>Pix, boleto, cartão de débito ou crédito.</p></div>
    </div>
    <div class="vantagem">
        <span class="vantagem-ico"><?= ui_icone('salvo') ?></span>
        <div><h3>Carrinho salvo</h3><p>Seus itens e favoritos ficam guardados na sua conta.</p></div>
    </div>
</section>

<section>
    <div class="secao-topo">
        <h2>Para começar</h2>
        <a class="link-mais" href="<?= $logado ? 'vitrine.php' : 'login.php' ?>">Ver todos os produtos</a>
    </div>

    <?php if (empty($destaques)): ?>
        <div class="vazio"><h2>Ainda não há produtos</h2><p>Volte em breve.</p></div>
    <?php else: ?>
        <div class="grade">
            <?php foreach ($destaques as $id => $p): ?>
                <?= ui_card($id, $p['nome'], $p['preco'], $logado ? 'loja' : 'anon') ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php ui_rodape(); ?>
