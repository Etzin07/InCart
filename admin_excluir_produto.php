<?php
session_start();
if (!isset($_SESSION['admin_logado'])) { header('Location: admin_login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$id = (int) ($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: admin.php");
    exit;
}

// Carrinho e favoritos são dados efêmeros, então limpamos as referências
// antes de excluir o produto (senão o banco bloquearia a exclusão por causa
// das chaves estrangeiras).
executarSeguro($server, $user, $password, $db, "DELETE FROM carrinho WHERE produto_id = ?", "i", [$id]);
executarSeguro($server, $user, $password, $db, "DELETE FROM favoritos WHERE produto_id = ?", "i", [$id]);

$resultado = executarSeguro($server, $user, $password, $db, "DELETE FROM produtos WHERE id = ?", "i", [$id]);

if (!$resultado['ok']) {
    // Isso acontece se o produto já apareceu em algum pedido antigo
    // (itens_pedido também referencia produtos) — mantemos o histórico
    // de pedidos intacto e só avisamos o admin.
    echo "<script>alert('Não é possível excluir: este produto já faz parte de pedidos anteriores.'); window.location='admin.php';</script>";
    exit;
}

header("Location: admin.php");
exit;
?>
