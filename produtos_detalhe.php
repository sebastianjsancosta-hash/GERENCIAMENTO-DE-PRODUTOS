<?php
session_start();
require_once('config.php'); 
require_once('admin/processa_crud.php'); 

// ID do produto
$id_produto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Validação do id
if ($id_produto) {
    $produto = lerDetalhesProduto($pdo, $id_produto);
} else {
    $produto = false;
}

require_once('includes/header.php');
?>

<main class="container">
    <?php if ($produto): ?>
        <h2><?= htmlspecialchars($produto['nome']) ?></h2>
        
        <div class="detalhes-produto">
            <p><strong>Categoria:</strong> <?= htmlspecialchars($produto['categoria']) ?></p>
            <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao']) ?></p>
            <p class="preco-detalhe">
                <strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
            </p>
            
            <?php if ($produto['estoque'] > 0): ?>
                <p class="status-detalhe disponivel">Disponível em Estoque: <?= $produto['estoque'] ?></p>
                <button class="btn-comprar">Comprar</button>
            <?php else: ?>
                <p class="status-detalhe esgotado">Produto Esgotado</p>
            <?php endif; ?>
        </div>
        
        <p><a href="produtos.php" class="btn-voltar">← Voltar para o Catálogo</a></p>

    <?php else: ?>
        <p class="alerta alerta-erro">Produto não encontrado ou ID inválido.</p>
        <p><a href="produtos.php" class="btn-voltar">← Voltar para o Catálogo</a></p>
    <?php endif; ?>
</main>

<?php
require_once('includes/footer.php'); 
?>