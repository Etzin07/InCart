<?php
// app/layout.php — peças de HTML usadas por todas as páginas da loja.
// Uso: require_once "app/layout.php"; ui_head('Título'); ui_topo('produtos'); ... ui_rodape();

if (!defined('UI_LAYOUT')) {
define('UI_LAYOUT', true);

function ui_h($t) {
    return htmlspecialchars((string) $t, ENT_QUOTES, 'UTF-8');
}

function ui_preco($v) {
    return 'R$ ' . number_format((float) $v, 2, ',', '.');
}

function ui_icone($nome) {
    static $i = [
        'inicio'    => '<path d="M3 11l9-8 9 8v9a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
        'produtos'  => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>',
        'favoritos' => '<path d="M12 21s-7-4.6-9.3-9.1C1.1 8.7 3 5 6.5 5c2 0 3.5 1 5.5 3 2-2 3.5-3 5.5-3C21 5 22.9 8.7 21.3 11.9 19 16.4 12 21 12 21z"/>',
        'carrinho'  => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.4 12.2a1 1 0 0 0 1 .8h9.2a1 1 0 0 0 1-.8L20 7H6"/>',
        'conta'     => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4.4 3.6-7 8-7s8 2.6 8 7"/>',
        'pedidos'   => '<path d="M21 8l-9-5-9 5 9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/><path d="M12 13v8"/>',
        'busca'     => '<circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/>',
        'lixo'      => '<path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/>',
        'ok'        => '<path d="M5 12.5l4.5 4.5L19 7.5"/>',
        'entrega'   => '<path d="M2 6h11v10H2zM13 10h4l3 3v3h-7"/><circle cx="7" cy="18" r="1.6"/><circle cx="17" cy="18" r="1.6"/>',
        'pix'       => '<rect x="3" y="6" width="18" height="12" rx="2"/><path d="M3 10h18M7 15h3"/>',
        'salvo'     => '<path d="M6 3h12v18l-6-4-6 4z"/>',
    ];
    return '<svg class="ico" viewBox="0 0 24 24" aria-hidden="true">' . ($i[$nome] ?? '') . '</svg>';
}

// Escolhe a foto da pasta imgs/ pelo nome do produto (sem acento, minúsculo).
// Regras mais específicas vêm primeiro.
function imagemDoProduto($nome) {
    $n = mb_strtolower((string) $nome, 'UTF-8');
    $n = strtr($n, [
        'á'=>'a','à'=>'a','ã'=>'a','â'=>'a','é'=>'e','ê'=>'e',
        'í'=>'i','ó'=>'o','ô'=>'o','õ'=>'o','ú'=>'u','ç'=>'c'
    ]);
    $regras = [
        [['feijao', 'preto', 'pronto'], 11],
        [['feijao', 'fradinho'],        9],
        [['feijao', 'pronto'],          10],
        [['feijao', 'preto'],           13],
        [['feijao', 'carioca'],         14],
        [['feijao', 'carica'],          14],
        [['arroz'],                     12],
        [['macarrao'],                  8],
        [['oleo', 'soya'],              6],
        [['oleo', 'liza'],              7],
        [['mascavo'],                   1],
        [['organico'],                  2],
        [['refinado'],                  3],
        [['cristal'],                   4],
    ];
    foreach ($regras as [$palavras, $numero]) {
        $achou = true;
        foreach ($palavras as $p) {
            if (strpos($n, $p) === false) { $achou = false; break; }
        }
        if ($achou) return 'imgs/' . $numero . '.png';
    }
    return null;
}

// Quadrado com a foto do produto, já recortada (as fotos originais têm muita
// margem branca). Sem foto: mostra a inicial do nome.
function ui_thumb($nome) {
    $img = imagemDoProduto($nome);
    if ($img) {
        return '<div class="thumb"><img src="' . ui_h($img) . '" alt="' . ui_h($nome) . '" loading="lazy"></div>';
    }
    $ini = mb_strtoupper(mb_substr(trim((string) $nome), 0, 1, 'UTF-8'), 'UTF-8');
    return '<div class="thumb thumb-vazio" aria-hidden="true"><span>' . ui_h($ini) . '</span></div>';
}

function ui_marca($claro = false) {
    return '<a class="marca' . ($claro ? ' marca-claro' : '') . '" href="index.php" aria-label="InCart, página inicial">'
         . '<span class="marca-logo"><img src="imgs/15.png" alt=""></span><span>InCart</span></a>';
}

function ui_head($titulo, $classe = '') {
    echo '<!DOCTYPE html><html lang="pt-br"><head><meta charset="UTF-8">'
       . '<meta name="viewport" content="width=device-width, initial-scale=1">'
       . '<title>' . ui_h($titulo) . ' · InCart</title>'
       . '<link rel="stylesheet" href="style.css"></head><body class="' . ui_h($classe) . '">';
}

function ui_topo($ativo = '') {
    global $server, $user, $password, $db;

    $logado = isset($_SESSION['logado']) && $_SESSION['logado'] === 'ok';
    $qtd = 0;

    if ($logado && isset($server, $_SESSION['cpf']) && function_exists('bancoSeguro')) {
        $r = bancoSeguro($server, $user, $password, $db,
            "SELECT COALESCE(SUM(quantidade), 0) AS q FROM carrinho WHERE cpf = ?", "s", [$_SESSION['cpf']]);
        $qtd = (int) $r->fetch_assoc()['q'];
    }

    echo '<header class="topbar"><div class="topbar-in">' . ui_marca();

    if ($logado) {
        echo '<form class="busca-topo" action="vitrine.php" method="get" role="search">' . ui_icone('busca')
           . '<input type="search" name="busca" placeholder="Buscar produtos" aria-label="Buscar produtos" value="'
           . ui_h($_GET['busca'] ?? '') . '"></form>';

        $links = [
            ['index.php',     'inicio',    'Início'],
            ['vitrine.php',   'produtos',  'Produtos'],
            ['favoritos.php', 'favoritos', 'Favoritos'],
            ['carrinho.php',  'carrinho',  'Carrinho'],
            ['conta.php',     'conta',     'Conta'],
        ];
        echo '<nav class="nav-principal" aria-label="Principal">';
        foreach ($links as [$href, $chave, $rotulo]) {
            $badge = ($chave === 'carrinho' && $qtd > 0) ? '<b class="badge">' . $qtd . '</b>' : '';
            echo '<a href="' . $href . '" class="' . ($ativo === $chave ? 'ativo' : '') . '"'
               . ($ativo === $chave ? ' aria-current="page"' : '') . '>'
               . ui_icone($chave) . '<span>' . $rotulo . '</span>' . $badge . '</a>';
        }
        echo '</nav><a class="sair" href="logout.php">Sair</a>';
    } else {
        echo '<div class="topbar-acoes"><a class="btn btn-contorno" href="login.php">Entrar</a>'
           . '<a class="btn btn-primario" href="cadastro1.php">Criar conta</a></div>';
    }

    echo '</div></header><main class="pagina">';
}

function ui_topo_admin() {
    echo '<header class="topbar"><div class="topbar-in">' . ui_marca()
       . '<span class="tag-admin">Painel admin</span>'
       . '<nav class="topbar-acoes"><a class="btn btn-contorno btn-pequeno" href="index.php">Ver loja</a>'
       . '<a class="btn btn-suave btn-pequeno" href="admin_logout.php">Sair</a></nav>'
       . '</div></header><main class="pagina">';
}

// Card de produto. $modo: 'loja' (adicionar + favoritar), 'fav' (adicionar + remover), 'anon' (visitante)
function ui_card($id, $nome, $preco, $modo = 'loja') {
    $h  = '<article class="card"><div class="card-media">' . ui_thumb($nome) . '</div>';
    $h .= '<div class="card-corpo"><h3 class="card-nome">' . ui_h($nome) . '</h3>'
        . '<p class="card-preco">' . ui_preco($preco) . '</p></div><div class="card-acoes">';

    if ($modo === 'anon') {
        $h .= '<a class="btn btn-primario btn-bloco" href="login.php">Entrar para comprar</a>';
    } else {
        $h .= '<form method="post" action="vitrine.php"><input type="hidden" name="add" value="' . (int) $id . '">'
            . '<button class="btn btn-primario btn-bloco" type="submit">Adicionar</button></form>';
        if ($modo === 'fav') {
            $h .= '<a class="btn-icone perigo" href="favoritos.php?remover=' . (int) $id
                . '" title="Remover dos favoritos" aria-label="Remover dos favoritos">' . ui_icone('lixo') . '</a>';
        } else {
            $h .= '<form method="post" action="vitrine.php"><input type="hidden" name="favorito" value="' . (int) $id . '">'
                . '<button class="btn-icone" type="submit" title="Favoritar" aria-label="Favoritar">' . ui_icone('favoritos') . '</button></form>';
        }
    }
    return $h . '</div></article>';
}

function ui_rodape() {
    echo '</main><footer class="rodape"><div class="rodape-in"><span>© ' . date('Y') . ' InCart · Mercado online</span>'
       . '<span>Entrega ou retirada · Pix, boleto e cartão</span></div></footer></body></html>';
}

function ui_fim() {
    echo '</body></html>';
}

} // fim UI_LAYOUT
