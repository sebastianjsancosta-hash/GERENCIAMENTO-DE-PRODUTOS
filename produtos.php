<?php
session_start();
require_once('config.php'); 
require_once('admin/processa_crud.php'); 

// Busca da lista de produtos
$produtos = listarProdutos($pdo);

require_once('includes/header.php'); 
?>


<main class="container">
    
    <div class="conteudo-catalogo"> 
        
        <h2 class="titulo-catalogo">Catálogo Completo de Produtos</h2>
        <p class="introducao">Confira todos os produtos disponíveis em nossa loja.</p>

        <?php if ($produtos): ?>
            <?php foreach ($produtos as $produto): ?>
                
                <div class="produto-item">
                    <h3>
                        <a href="produtos_detalhe.php?id=<?= htmlspecialchars($produto['id_produto']) ?>">
                            <?= htmlspecialchars($produto['nome']) ?>
                        </a>
                    </h3>

                    <p class="categoria">
                        Categoria: <?= htmlspecialchars($produto['categoria']) ?>
                    </p>
                    <p class="preco">
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </p>
                    <p class="estoque">
                        Disponível: <?= htmlspecialchars($produto['estoque']) ?> em estoque 
                        <a href="produtos_detalhe.php?id=<?= htmlspecialchars($produto['id_produto']) ?>" class="link-detalhes">
                            Ver Detalhes
                        </a>
                    </p>
                    <hr class="separador-catalogo">
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <p class="alerta alerta-info">Nenhum produto encontrado no catálogo.</p>
        <?php endif; ?>
        
    </div> </main>

<?php
require_once('includes/footer.php'); 
?>