<?php

// Lê os produtos direto da tabela `produtos`, então o painel admin e a loja
// sempre mostram os mesmos dados. A foto de cada produto vem de
// imagemDoProduto() (app/layout.php), que escolhe a imagem pelo nome.

if (!isset($server)) {
    include_once __DIR__ . "/app/cons.php";
}
if (!function_exists('banco')) {
    require_once __DIR__ . "/app/DLL.php";
}
require_once __DIR__ . "/app/layout.php";

$produtos = [];

$resultadoProdutos = banco($server, $user, $password, $db, "SELECT * FROM produtos ORDER BY id");

while ($linha = $resultadoProdutos->fetch_assoc()) {
    $produtos[(int) $linha['id']] = [
        "nome"   => $linha['nome'],
        "preco"  => (float) $linha['preco'],
        "imagem" => imagemDoProduto($linha['nome']),
    ];
}

?>
