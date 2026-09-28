<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

?>

<?php
require_once "app/layout.php";
ui_head('Trocar senha');
ui_topo('conta');
?>

<div class="painel painel-estreito">
    <a class="link-voltar" href="conta.php">Voltar para minha conta</a>
    <h2>Trocar senha</h2>
    <p class="sub">Digite a senha atual e escolha uma nova.</p>

    <form class="form" action="salvar_senha.php" method="post">
        <label class="campo"><span>Senha atual</span><input type="password" name="senha_atual" autocomplete="current-password" required></label>
        <label class="campo"><span>Nova senha</span><input type="password" name="nova_senha" autocomplete="new-password" required></label>
        <label class="campo"><span>Confirme a nova senha</span><input type="password" name="confirma_senha" autocomplete="new-password" required></label>
        <div class="form-acoes">
            <button class="btn btn-primario btn-grande" type="submit">Alterar senha</button>
            <a class="btn btn-contorno btn-grande" href="conta.php">Cancelar</a>
        </div>
    </form>
</div>

<?php ui_rodape(); ?>
