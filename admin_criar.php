<?php
// Página de uso único: cria o PRIMEIRO admin do painel. Depois que existir
// pelo menos um admin cadastrado, ela se bloqueia sozinha.
session_start();

include "app/cons.php";
require_once "app/DLL.php";
require_once "app/layout.php";

$resultadoContagem = banco($server, $user, $password, $db, "SELECT COUNT(*) AS total FROM admins");
$totalAdmins = $resultadoContagem->fetch_assoc()['total'];

$erro = "";

if ($totalAdmins > 0) {
    ui_head('Configuração concluída', 'auth-body');
    ?>
    <div class="auth">
        <aside class="auth-marca">
            <?= ui_marca(true) ?>
            <div><h1>Configuração concluída.</h1></div>
        </aside>
        <section class="auth-form">
            <div class="auth-caixa">
                <h2>O admin inicial já existe</h2>
                <p class="sub">Por segurança, esta página não pode mais ser usada.</p>
                <div class="form-acoes"><a class="btn btn-primario btn-grande" href="admin_login.php">Ir para o login do admin</a></div>
            </div>
        </section>
    </div>
    <?php
    ui_fim();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $usuario = trim($_POST['usuario']);
    $senha = $_POST['senha'];

    if ($usuario === '' || $senha === '') {
        $erro = "Preencha usuário e senha.";
    } else {

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        $resultado = executarSeguro(
            $server, $user, $password, $db,
            "INSERT INTO admins (usuario, senha) VALUES (?, ?)",
            "ss",
            [$usuario, $senhaCriptografada]
        );

        if ($resultado['ok']) {
            echo "<script>alert('Admin criado com sucesso! Faça login.'); window.location='admin_login.php';</script>";
            exit;
        } else {
            $erro = $resultado['duplicado'] ? "Esse usuário já existe." : "Erro ao criar admin.";
        }
    }
}

ui_head('Criar admin', 'auth-body');
?>
<div class="auth">
    <aside class="auth-marca">
        <?= ui_marca(true) ?>
        <div>
            <h1>Crie o primeiro admin.</h1>
            <p>Esta página só funciona uma vez: depois do primeiro cadastro ela se bloqueia.</p>
        </div>
    </aside>
    <section class="auth-form">
        <div class="auth-caixa">
            <h2>Criar o primeiro admin</h2>
            <?php if ($erro !== ""): ?>
                <div class="alerta alerta-erro" style="margin-top:18px"><?= ui_h($erro) ?></div>
            <?php endif; ?>
            <form class="form" action="admin_criar.php" method="post">
                <label class="campo"><span>Usuário</span><input type="text" name="usuario" autocomplete="username" required></label>
                <label class="campo"><span>Senha</span><input type="password" name="senha" autocomplete="new-password" required></label>
                <button class="btn btn-primario btn-grande btn-bloco" type="submit">Criar admin</button>
            </form>
        </div>
    </section>
</div>
<?php ui_fim(); ?>
