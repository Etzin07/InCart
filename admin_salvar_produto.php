<?php
session_start();
if (!isset($_SESSION['admin_logado'])) { header('Location: admin_login.php'); exit; }

include "app/cons.php";
require_once "app/DLL.php";

$id = (int) ($_POST['id'] ?? 0);
$nome = trim($_POST['nome'] ?? '');
$preco = str_replace(',', '.', trim($_POST['preco'] ?? ''));

if ($id <= 0 || $nome === '' || !is_numeric($preco) || (float) $preco <= 0) {
    echo "<script>alert('Dados inválidos. Verifique o nome e o preço.'); window.location='admin.php';</script>";
    exit;
}

executarSeguro(
    $server, $user, $password, $db,
    "UPDATE produtos SET nome = ?, preco = ? WHERE id = ?",
    "sdi",
    [$nome, (float) $preco, $id]
);

header("Location: admin.php");
exit;
?>
