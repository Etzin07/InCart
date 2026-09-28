<?php
session_start();
if (!isset($_SESSION['admin_logado'])) { header('Location: admin_login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$erro = "";

// Adicionar produto novo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['novo_nome'])) {

    $nome = trim($_POST['novo_nome']);
    $preco = str_replace(',', '.', trim($_POST['novo_preco']));

    if ($nome === '' || !is_numeric($preco) || (float) $preco <= 0) {
        $erro = "Preencha um nome e um preço válido (maior que zero).";
    } else {
        executarSeguro(
            $server, $user, $password, $db,
            "INSERT INTO produtos (nome, preco) VALUES (?, ?)",
            "sd",
            [$nome, (float) $preco]
        );
        header("Location: admin.php");
        exit;
    }
}

$resultadoProdutos = banco($server, $user, $password, $db, "SELECT * FROM produtos ORDER BY id");
?>

<?php
require_once "app/layout.php";
ui_head('Produtos · Admin');
ui_topo_admin();
?>

<section class="pagina-titulo">
    <div>
        <h1>Produtos</h1>
        <p class="sub">Olá, <?= ui_h($_SESSION['admin_usuario']) ?>. Gerencie o catálogo da loja.</p>
    </div>
</section>

<?php if ($erro !== ""): ?>
    <div class="alerta alerta-erro"><?= ui_h($erro) ?></div>
<?php endif; ?>

<div class="painel">
    <h2>Novo produto</h2>
    <form class="form form-linha" method="post">
        <label class="campo"><span>Nome</span><input type="text" name="novo_nome" placeholder="Ex: Arroz 5kg" required></label>
        <label class="campo"><span>Preço</span><input type="text" name="novo_preco" inputmode="decimal" placeholder="9,90" required></label>
        <button class="btn btn-primario" type="submit">Adicionar</button>
    </form>
</div>

<div class="tabela-wrap">
    <table class="tabela">
        <thead>
            <tr><th>Produto</th><th>Preço</th><th></th></tr>
        </thead>
        <tbody>
        <?php while ($p = $resultadoProdutos->fetch_assoc()): ?>
            <tr>
                <td><div class="celula-prod"><?= ui_thumb($p['nome']) ?><span><?= ui_h($p['nome']) ?></span></div></td>
                <td><?= ui_preco($p['preco']) ?></td>
                <td>
                    <div class="acoes">
                        <a class="btn btn-suave btn-pequeno" href="admin_editar_produto.php?id=<?= (int) $p['id'] ?>">Editar</a>
                        <a class="btn btn-perigo btn-pequeno" href="admin_excluir_produto.php?id=<?= (int) $p['id'] ?>"
                           onclick="return confirm('Tem certeza que quer apagar este produto?');">Excluir</a>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php ui_rodape(); ?>
