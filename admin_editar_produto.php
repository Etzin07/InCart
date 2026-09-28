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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - In Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="cadastro-container">
    <form action="admin_salvar_produto.php" method="POST" class="cadastro-box">

        <h1>🛠️ Editar Produto</h1>

        <input type="hidden" name="id" value="<?php echo (int) $produto['id']; ?>">

        <input type="text" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>

        <input type="text" name="preco"
               value="<?php echo htmlspecialchars(number_format((float) $produto['preco'], 2, ',', '')); ?>"
               required>

        <button type="submit">Salvar alterações</button>

        <a href="admin.php" class="cadastro-link">Cancelar</a>

    </form>
</div>

</body>
</html>
