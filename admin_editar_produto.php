<?php
session_start();
if (!isset($_SESSION['admin_logado'])) { header('Location: admin_login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$id = (int) ($_GET['id'] ?? 0);

$resultado = bancoSeguro(
    $server, $user, $password, $db,
    "SELECT * FROM produtos WHERE id = ?",
    "i",
    [$id]
);

if ($resultado->num_rows === 0) {
    echo "<script>alert('Produto não encontrado.'); window.location='admin.php';</script>";
    exit;
}

$produto = $resultado->fetch_assoc();
?>

<?php
require_once "app/layout.php";
ui_head('Editar produto · Admin');
ui_topo_admin();
?>

<div class="painel painel-estreito">
    <a class="link-voltar" href="admin.php">Voltar aos produtos</a>
    <h2>Editar produto</h2>

    <form class="form" action="admin_salvar_produto.php" method="post">
        <input type="hidden" name="id" value="<?= (int) $produto['id'] ?>">
        <label class="campo"><span>Nome</span><input type="text" name="nome" value="<?= ui_h($produto['nome']) ?>" required></label>
        <label class="campo"><span>Preço</span><input type="text" name="preco" inputmode="decimal" value="<?= ui_h(number_format((float) $produto['preco'], 2, ',', '')) ?>" required></label>
        <div class="form-acoes">
            <button class="btn btn-primario btn-grande" type="submit">Salvar alterações</button>
            <a class="btn btn-contorno btn-grande" href="admin.php">Cancelar</a>
        </div>
    </form>
</div>

<?php ui_rodape(); ?>
