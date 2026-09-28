<?php
// Página de uso único: cria o PRIMEIRO admin do painel. Depois que existir
// pelo menos um admin cadastrado, ela se bloqueia sozinha (por segurança,
// já que qualquer pessoa que soubesse a URL poderia criar um admin novo).
session_start();

include "app/cons.php";
require_once "app/DLL.php";

$resultadoContagem = banco($server, $user, $password, $db, "SELECT COUNT(*) AS total FROM admins");
$totalAdmins = $resultadoContagem->fetch_assoc()['total'];

$erro = "";

if ($totalAdmins > 0) {
    // Já existe pelo menos um admin: bloqueia o acesso a esta página.
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Configuração já concluída</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <div class="login-box">
        <h1>🛒 In Cart</h1>
        <p>O admin inicial já foi criado. Por segurança, esta página não pode mais ser usada.</p>
        <p>Se precisar de outro admin, peça pra um admin já existente cadastrar
        (funcionalidade de cadastrar outros admins pode ser adicionada depois, se quiser).</p>
        <a href="admin_login.php" class="cadastro-link">Ir para o login do admin</a>
    </div>
</div>
</body>
</html>
<?php
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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar admin - In Cart</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-container">
    <form action="admin_criar.php" method="POST" class="login-box">

        <h1>🛒 In Cart</h1>
        <h2>Criar o primeiro admin</h2>

        <?php if ($erro !== ""): ?>
            <p style="color:red;"><?php echo htmlspecialchars($erro); ?></p>
        <?php endif; ?>

        <input type="text" name="usuario" placeholder="Usuário admin" required>
        <input type="password" name="senha" placeholder="Senha" required>

        <button type="submit">Criar admin</button>

    </form>
</div>

</body>
</html>
