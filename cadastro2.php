<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . "/app/layout.php";
ui_head('Criar login', 'auth-body');
?>

<div class="auth">
    <aside class="auth-marca">
        <?= ui_marca(true) ?>
        <div>
            <h1>Falta só o seu login.</h1>
            <p>Escolha um login e uma senha para entrar na loja.</p>
        </div>
    </aside>
    <section class="auth-form">
        <div class="auth-caixa">
            <h2>Criar login</h2>
            <ol class="passos"><li class="">1. Seus dados</li><li class="ativo">2. Login e senha</li></ol>
            <form class="form" action="salvar_login.php" method="post">
                <label class="campo"><span>Login</span><input type="text" name="login" autocomplete="username" required autofocus></label>
                <label class="campo"><span>Senha</span><input type="password" name="senha" autocomplete="new-password" required></label>
                <button class="btn btn-primario btn-grande btn-bloco" type="submit">Finalizar cadastro</button>
            </form>
        </div>
    </section>
</div>

<?php ui_fim(); ?>
