<?php
session_start();
?>

<?php
require_once "app/layout.php";
ui_head('Criar conta', 'auth-body');
?>

<div class="auth">
    <aside class="auth-marca">
        <?= ui_marca(true) ?>
        <div>
            <h1>Comece a comprar em poucos minutos.</h1>
            <p>Criar sua conta leva duas etapas rápidas: seus dados e um login.</p>
        </div>
    </aside>
    <section class="auth-form">
        <div class="auth-caixa">
            <h2>Criar conta</h2>
            <ol class="passos"><li class="ativo">1. Seus dados</li><li class="">2. Login e senha</li></ol>
            <form class="form form-2" action="salvar_usuario.php" method="post">
                <label class="campo largo"><span>Nome completo</span><input type="text" name="nome" autocomplete="name" required></label>
                <label class="campo largo"><span>CPF</span><input type="text" name="cpf" inputmode="numeric" maxlength="14" placeholder="000.000.000-00" required></label>
                <label class="campo largo"><span>Endereço</span><input type="text" name="endereco" autocomplete="street-address" required></label>
                <label class="campo"><span>Bairro</span><input type="text" name="bairro" required></label>
                <label class="campo"><span>Cidade</span><input type="text" name="cidade" autocomplete="address-level2" required></label>
                <label class="campo"><span>Estado</span><input type="text" name="estado" autocomplete="address-level1" required></label>
                <label class="campo"><span>CEP</span><input type="text" name="cep" inputmode="numeric" maxlength="9" autocomplete="postal-code" required></label>
                <button class="btn btn-primario btn-grande btn-bloco largo" type="submit">Continuar</button>
            </form>
            <p class="auth-troca">Já tem conta? <a href="login.php">Entrar</a></p>
        </div>
    </section>
</div>

<?php ui_fim(); ?>
