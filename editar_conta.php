<?php

session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] != "ok") {
    header("Location: login.php");
    exit;
}

include "app/cons.php";
require_once "app/DLL.php";

$cpf = $_SESSION['cpf'];

$consulta = "SELECT * FROM usuarios WHERE cpf = '$cpf'";

$resultado = banco($server, $user, $password, $db, $consulta);

if ($resultado->num_rows == 0) {
    echo "Usuário não encontrado.";
    exit;
}

$usuario = $resultado->fetch_assoc();

?>

<?php
require_once "app/layout.php";
ui_head('Editar informações');
ui_topo('conta');
?>

<div class="painel painel-estreito">
    <a class="link-voltar" href="conta.php">Voltar para minha conta</a>
    <h2>Editar informações</h2>
    <p class="sub">Atualize seus dados de entrega.</p>

    <form class="form form-2" action="salvar_edicao.php" method="post">
        <label class="campo largo"><span>Nome completo</span><input type="text" name="nome" value="<?= ui_h($usuario['nome']) ?>" autocomplete="name" required></label>
        <label class="campo largo"><span>Endereço</span><input type="text" name="endereco" value="<?= ui_h($usuario['endereco']) ?>" autocomplete="street-address" required></label>
        <label class="campo"><span>Bairro</span><input type="text" name="bairro" value="<?= ui_h($usuario['bairro']) ?>"  required></label>
        <label class="campo"><span>Cidade</span><input type="text" name="cidade" value="<?= ui_h($usuario['cidade']) ?>"  required></label>
        <label class="campo"><span>Estado</span><input type="text" name="estado" value="<?= ui_h($usuario['estado']) ?>"  required></label>
        <label class="campo"><span>CEP</span><input type="text" name="cep" value="<?= ui_h($usuario['cep']) ?>" inputmode="numeric" required></label>
        <div class="form-acoes largo">
            <button class="btn btn-primario btn-grande" type="submit">Salvar alterações</button>
            <a class="btn btn-contorno btn-grande" href="conta.php">Cancelar</a>
        </div>
    </form>
</div>

<?php ui_rodape(); ?>
