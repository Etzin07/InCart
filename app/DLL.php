<?php

function teste_login($sessao) {

    if ($sessao != "ok") {
        header("Location: index.php?erro=1");
        exit;
    }
}


// Função original: mantida para consultas FIXAS, sem nenhum dado vindo do
// usuário dentro do SQL (ex: "SELECT * FROM produtos ORDER BY id").
// NUNCA use esta função colando variáveis de $_GET/$_POST/$_SESSION direto
// dentro da string de consulta — é exatamente isso que causava SQL Injection
// em carrinho.php, vitrine.php, favoritos.php, processa_login.php,
// salvar_login.php e salvar_usuario.php.
function banco($server, $user, $password, $db, $consulta) {

    $banco = new mysqli($server, $user, $password, $db);

    if ($banco->connect_error) {

        echo "Falha de conexão referência: ("
            . $banco->connect_errno
            . ") - "
            . $banco->connect_error;

        exit();
    }

    $resultado = $banco->query($consulta);

    if (!$resultado) {

        echo "Falha na consulta referência: ("
            . $banco->errno
            . ") - "
            . $banco->error;

        exit();
    }

    $banco->close();

    return $resultado;
}

// Abre uma conexão mysqli pronta para uso, já com erro tratado.
function conectarBanco($server, $user, $password, $db) {

    $conexao = new mysqli($server, $user, $password, $db);

    if ($conexao->connect_error) {
        echo "Falha de conexão referência: ("
            . $conexao->connect_errno
            . ") - "
            . $conexao->connect_error;
        exit();
    }

    $conexao->set_charset("utf8mb4");

    return $conexao;
}

// Use esta função para QUALQUER consulta que tenha dado vindo do usuário
// (login, senha, cpf, id de produto, busca, etc). $tipos segue o padrão do
// mysqli bind_param ("s" = string, "i" = inteiro, "d" = decimal).
//
// Exemplo:
//   $resultado = bancoSeguro($server, $user, $password, $db,
//       "SELECT * FROM logins WHERE login = ?", "s", [$login]);
function bancoSeguro($server, $user, $password, $db, $sql, $tipos = "", $parametros = []) {

    $conexao = conectarBanco($server, $user, $password, $db);

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        echo "Erro ao preparar consulta: " . $conexao->error;
        exit();
    }

    if ($tipos !== "" && !empty($parametros)) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    if (!$stmt->execute()) {
        echo "Erro ao executar consulta: " . $stmt->error;
        exit();
    }

    $resultado = $stmt->get_result();

    $stmt->close();
    $conexao->close();

    return $resultado;
}

// Igual a bancoSeguro(), mas para INSERT/UPDATE/DELETE: não devolve linhas,
// devolve se deu certo, o erro (se houver) e o id inserido (quando for INSERT).
// Trata também erro de UNIQUE (ex: CPF ou login repetido) sem quebrar a página.
function executarSeguro($server, $user, $password, $db, $sql, $tipos = "", $parametros = []) {

    $conexao = conectarBanco($server, $user, $password, $db);

    $stmt = $conexao->prepare($sql);

    if (!$stmt) {
        $conexao->close();
        return ["ok" => false, "erro" => "Erro ao preparar consulta.", "duplicado" => false, "id" => null];
    }

    if ($tipos !== "" && !empty($parametros)) {
        $stmt->bind_param($tipos, ...$parametros);
    }

    $ok = $stmt->execute();
    $erro = $ok ? null : $stmt->error;
    $duplicado = (!$ok && $conexao->errno === 1062); // 1062 = violação de UNIQUE no MySQL
    $id = $ok ? $stmt->insert_id : null;

    $stmt->close();
    $conexao->close();

    return ["ok" => $ok, "erro" => $erro, "duplicado" => $duplicado, "id" => $id];
}

?>
