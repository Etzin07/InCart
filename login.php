<?php
session_start();
?>

<?php
require_once "app/layout.php";
ui_head('Entrar', 'auth-body');
?>

<div class="auth">
    <aside class="auth-marca">
        <?= ui_marca(true) ?>
        <div>
            <h1>Seu mercado favorito, a um clique.</h1>
            <p>Entre para montar seu carrinho, salvar favoritos e acompanhar seus pedidos.</p>
        </div>
    </aside>
    <section class="auth-form">
        <div class="auth-caixa">
            <h2>Entrar</h2>
            <p class="sub">Use o login que você criou no cadastro.</p>
            <form class="form" action="processa_login.php" method="post">
                <label class="campo"><span>Login</span><input type="text" name="login" autocomplete="username" required autofocus></label>
                <label class="campo"><span>Senha</span><input type="password" name="senha" autocomplete="current-password" required></label>
                <button class="btn btn-primario btn-grande btn-bloco" type="submit">Entrar</button>
            </form>
            <p class="auth-troca">Ainda não tem conta? <a href="cadastro1.php">Criar conta</a></p>
        </div>
    </section>
</div>

<?php ui_fim(); ?>
