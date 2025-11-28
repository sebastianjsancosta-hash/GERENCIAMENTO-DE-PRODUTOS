<?php
// Inclusoes
require_once(__DIR__ . '/../config.php'); 
require_once(__DIR__ . '/processa_crud.php'); // Contém listarCategorias
require_once(__DIR__ . '/processa_categorias.php'); // Contém a lógica POST/GET (CRUD)

// Lógica
$categorias = listarCategorias($pdo);
$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

// Lógica de edicao
$categoria_edicao = null;
if (isset($_GET['editar']) && filter_var($_GET['editar'], FILTER_VALIDATE_INT)) {
    $id_edicao = (int)$_GET['editar'];
    
    foreach($categorias as $cat) {
        if ($cat['id'] == $id_edicao) {
            $categoria_edicao = $cat;
            break;
        }
    }
}
?>

<?php 
require_once(__DIR__ . '/../includes/header.php'); 
?>


<main class="container">
    <h1>Gerenciamento de Categorias</h1>
    <a href="index.php" class="btn btn-secondary">← Voltar para Produtos</a>
    
    <?php
    $mensagem = ''; $classe_alerta = 'alerta';
    if($status) {
        switch ($status) {
            case 'sucesso': $mensagem = 'Operação realizada com sucesso!'; $classe_alerta .= ' sucesso'; break;
            case 'exclusao_sucesso': $mensagem = 'Categoria excluída com sucesso!'; $classe_alerta .= ' sucesso'; break;
            case 'erro_validacao': $mensagem = 'O nome da categoria não pode ser vazio.'; $classe_alerta .= ' aviso'; break;
            
            case 'erro_vinculo': 
                $mensagem = 'Não é possível excluir a categoria: existem produtos vinculados a ela.'; 
                $classe_alerta .= ' aviso'; 
                break;

            case 'erro_sql': $mensagem = 'Erro no banco de dados. Tente novamente ou verifique a conexão.'; $classe_alerta .= ' erro'; break;
        }
        echo "<div class='$classe_alerta'>$mensagem</div>";
    }
    ?>

    <section class="form-cadastro-categoria">
        <h2><?= $categoria_edicao ? 'Editar Categoria' : 'Cadastrar Nova Categoria' ?></h2>
        
        <form action="processa_categorias.php?acao=<?= $categoria_edicao ? 'editar' : 'cadastrar' ?>" method="POST">
            
            <?php if ($categoria_edicao): ?>
                <input type="hidden" name="id" value="<?= htmlspecialchars($categoria_edicao['id']) ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nome">Nome da Categoria:</label>
                <input type="text" id="nome" name="nome" required 
                        value="<?= htmlspecialchars($categoria_edicao['nome'] ?? '') ?>">
            </div>
            
            <button type="submit" class="btn btn-success">
                <?= $categoria_edicao ? 'Salvar Alterações' : 'Cadastrar' ?>
            </button>
            <?php if ($categoria_edicao): ?>
                <a href="categorias.php" class="btn btn-secondary">Cancelar Edição</a>
            <?php endif; ?>
        </form>
    </section>

    <hr>
    
    <section class="lista-categorias">
        <h2>Categorias Atuais</h2>
        
        <?php if ($categorias): ?>
            <ul class="lista-crud">
                <?php foreach ($categorias as $categoria): ?>
                    <li>
                        <span><?= htmlspecialchars($categoria['nome']) ?></span>
                        <div class="acoes">
                            <a href="categorias.php?editar=<?= $categoria['id'] ?>" class="btn btn-warning btn-pequeno">Editar</a>
                            <a href="processa_categorias.php?acao=excluir&id=<?= $categoria['id'] ?>" 
                               onclick="return confirm('Tem certeza que deseja excluir esta categoria? Isso pode causar erros se houver produtos vinculados.');" 
                               class="btn btn-danger btn-pequeno">Excluir</a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alerta aviso">Nenhuma categoria cadastrada.</div>
        <?php endif; ?>
    </section>
</main>

<?php 
require_once(__DIR__ . '/../includes/footer.php'); 
?>