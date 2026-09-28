<?php

// Antes este arquivo tinha um array fixo de produtos, desatualizado em
// relação ao banco (que carrinho.php e vitrine.php já consultavam). Agora
// ele lê direto da tabela `produtos`, então o painel admin (admin.php) e a
// loja sempre mostram os mesmos dados.

if (!isset($server)) {
    include_once __DIR__ . "/app/cons.php";
}
if (!function_exists('banco')) {
    require_once __DIR__ . "/app/DLL.php";
}

$produtos = [];

$resultadoProdutos = banco($server, $user, $password, $db, "SELECT * FROM produtos ORDER BY id");

while ($linha = $resultadoProdutos->fetch_assoc()) {
    $produtos[(int) $linha['id']] = [
        "nome"  => $linha['nome'],
        "preco" => (float) $linha['preco'],
    ];
}

?>
