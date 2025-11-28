<?php

require_once '/config.php'; 
require_once 'admin/processa_crud.php'; 

$produto = null;
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Verifica se o id é válido
if ($id_produto) {
    $produto = buscarProdutoPorId($pdo, $id_produto);
}

if (!$produto) {
    http_response_code(404);
    $titulo_pagina = "Produto Não Encontrado";
} else {
    $titulo_pagina = "Detalhes do Produto: " . htmlspecialchars($produto['nome']);
}

// Header
require_once 'includes/header.php'; 
?>

    <link rel="stylesheet" href="css/style.css">

<main class="container">
    <h1><?= $titulo_pagina ?></h1>
    
    <?php if ($produto): ?>
        <div class="detalhe-produto">
            <section class="info-principal">
                <h2><?= htmlspecialchars($produto['nome']) ?></h2>
                
                <p class="preco-detalhe">
                    Preço:R$ <?= number_format($produto['preco'], 2, ',', '.') ?>**
                </p>

                <p class="descricao-detalhe">
                    Descrição:<br>
                    <?= nl2br(htmlspecialchars($produto['descricao'] ?? 'Descrição não fornecida.')) ?>
                </p>
                
                <p class="categoria-detalhe">
                    Categoria ID: <?= htmlspecialchars($produto['id_categoria']) ?>
                    </p>
            </section>

            <section class="info-estoque">
                <?php if ($produto['estoque'] > 0): ?>
                    <div class="status-estoque disponivel">
                        Disponível em Estoque! (Unidades: <?= htmlspecialchars($produto['estoque']) ?>)
                        <button class="btn btn-primary btn-comprar">Comprar Agora</button>
                    </div>
                <?php else: ?>
                    <div class="status-estoque esgotado">
                        Produto Esgotado.
                    </div>
                <?php endif; ?>
            </section>
        </div>
        <div class="voltar-catalogo">
            <a href="<?= $baseUrl ?>/produtos.php" class="btn btn-secondary">← Voltar ao Catálogo</a>
        </div>
        
    <?php else: ?>
        <div class="alerta erro">
            Desculpe, o produto solicitado não existe ou o ID é inválido.
        </div>
        <div class="voltar-catalogo">
            <a href="<?= $baseUrl ?>/produtos.php" class="btn btn-secondary">← Ver Catálogo</a>
        </div>
    <?php endif; ?>
</main>

<?php 
// Footer
require_once 'includes/footer.php'; 
?>