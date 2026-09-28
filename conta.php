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
$inicial = mb_strtoupper(mb_substr(trim($usuario['nome']), 0, 1, 'UTF-8'), 'UTF-8');
ui_head('Minha conta');
ui_topo('conta');
?>

<div class="painel painel-estreito">
    <div class="perfil">
        <div class="avatar" aria-hidden="true"><?= ui_h($inicial) ?></div>
        <div>
            <h1><?= ui_h($usuario['nome']) ?></h1>
            <p class="sub">Login: <?= ui_h($_SESSION['login'] ?? '') ?></p>
        </div>
    </div>

    <dl class="dados">
        <div><dt>CPF</dt><dd><?= ui_h($usuario['cpf']) ?></dd></div>
        <div><dt>CEP</dt><dd><?= ui_h($usuario['cep']) ?></dd></div>
        <div class="largo"><dt>Endereço</dt><dd><?= ui_h($usuario['endereco']) ?></dd></div>
        <div><dt>Bairro</dt><dd><?= ui_h($usuario['bairro']) ?></dd></div>
        <div><dt>Cidade / Estado</dt><dd><?= ui_h($usuario['cidade']) ?> / <?= ui_h($usuario['estado']) ?></dd></div>
    </dl>

    <div class="atalhos">
        <a class="btn btn-primario" href="pedidos.php">Meus pedidos</a>
        <a class="btn btn-contorno" href="editar_conta.php">Editar informações</a>
        <a class="btn btn-contorno" href="trocar_senha.php">Trocar senha</a>
        <a class="btn btn-perigo" href="logout.php">Sair da conta</a>
    </div>
</div>

<?php ui_rodape(); ?>
