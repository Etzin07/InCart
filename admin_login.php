<?php
session_start();
?>

<?php
require_once "app/layout.php";
ui_head('Admin', 'auth-body');
?>

<div class="auth">
    <aside class="auth-marca">
        <?= ui_marca(true) ?>
        <div>
            <h1>Painel administrativo.</h1>
            <p>Área restrita para gerenciar os produtos da loja.</p>
        </div>
    </aside>
    <section class="auth-form">
        <div class="auth-caixa">
            <h2>Entrar no painel</h2>
            <form class="form" action="admin_processa_login.php" method="post">
                <label class="campo"><span>Usuário</span><input type="text" name="usuario" autocomplete="username" required autofocus></label>
                <label class="campo"><span>Senha</span><input type="password" name="senha" autocomplete="current-password" required></label>
                <button class="btn btn-primario btn-grande btn-bloco" type="submit">Entrar</button>
            </form>
        </div>
    </section>
</div>

<?php ui_fim(); ?>
