<?php
// Inclusoes
require_once(__DIR__ . '/../config.php'); 
require_once(__DIR__ . '/processa_categorias.php'); 
require_once(__DIR__ . '/processa_crud.php'); 
require_once(__DIR__ . '/../includes/header.php'); 
$produto = null;
$titulo = "Novo Produto";
$acao = "cadastrar";
$categorias = listarCategorias($pdo);
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id_produto = (int) $_GET['id'];

    $produto = buscarProdutoPorId($pdo, $id_produto);

    if ($produto) {
        $titulo = "Editar Produto: " . htmlspecialchars($produto['nome']);
        $acao = "editar";
    } else {
        header("Location: index.php?status=erro_produto_nao_encontrado");
        exit;
    }
}
?>

<main class="container">
    <h2><?= $titulo ?></h2>

    <form action="processa_crud.php?acao=<?= $acao ?>" method="POST" class="form-crud">

        <?php if ($acao === 'editar'): ?>
            <input type="hidden" name="id_produto"
                value="<?= htmlspecialchars($produto['id_produto'] ?? $produto['id']) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($produto['nome'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?= htmlspecialchars($produto['descricao'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" required
                value="<?= htmlspecialchars($produto['preco'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="estoque">Estoque:</label>
            <input type="number" id="estoque" name="estoque" required
                value="<?= htmlspecialchars($produto['estoque'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="id_categoria">Categoria:</label>
            <select id="id_categoria" name="id_categoria" required>
                <option value="">-- Selecione uma Categoria --</option>

                <?php foreach ($categorias as $categoria):

                    $selected = ($produto && $produto['id_categoria'] == $categoria['id']) ? 'selected' : '';
                    ?>
                    <option value="<?= htmlspecialchars($categoria['id']) ?>" <?= $selected ?>>
                        <?= htmlspecialchars($categoria['nome'] ?? '') ?> 
                    </option>
                <?php endforeach; ?>

            </select>
        </div>
        <div class="form-actions"> 
            <button type="submit" class="btn btn-success">Salvar <?= ($acao === 'cadastrar' ? 'Produto' : 'Alterações') ?></button>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div> 
        </form>
</main>

<?php
// Layout footer
require_once(__DIR__ . '/../includes/footer.php');
?>