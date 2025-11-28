<?php
// 1. Inclusoes
session_start();
require_once(__DIR__ . '/../config.php'); 
require_once(__DIR__ . '/processa_categorias.php'); 
require_once(__DIR__ . '/../includes/header.php'); 

$titulo = "Nova Categoria";
$acao = "cadastrar";
$categoria = null;
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

// Lóg de status p/ exibicao de mensagem
if ($status === 'erro_validacao') {
    $mensagem = 'Erro: O nome da categoria é obrigatório.';
} elseif ($status === 'erro_sql') {
    $mensagem = 'Erro interno: Não foi possível salvar a categoria no banco de dados.';
} else {
    $mensagem = '';
}

?>

<main class="container">
    <h2><?= $titulo ?></h2>

    <?php if ($mensagem): ?>
        <p class="alerta-erro" style="color:red; font-weight:bold;"><?= $mensagem ?></p>
    <?php endif; ?>

    <form action="processa_categorias.php?acao=<?= $acao ?>" method="POST" class="form-crud">
        
        <div class="form-group">
            <label for="nome">Nome da Categoria:</label>
            <input type="text" id="nome" name="nome" required 
                value="<?= htmlspecialchars($categoria['nome'] ?? '') ?>">
        </div>

        <div class="form-actions"> 
            <button type="submit" class="btn btn-primary">Salvar Categoria</button>
            <a href="index.php" class="btn btn-secondary">Voltar</a>
        </div> 
    </form>
</main>

<?php
require_once(__DIR__ . '/../includes/footer.php');
?>