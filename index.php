<?php

require_once (__DIR__ . '/../config.php');

require_once('processa_crud.php'); 

session_start();

// Verificacao do login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}
// Var

$produtos = listarProdutos($pdo);

$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);

// Layout
require_once(__DIR__ . '/../includes/header.php'); 
?>

<<main class="container">
 <h2>Gerenciamento de Produtos e Categorias</h2>
 
 <?php if ($status === 'sucesso' || $status === 'exclusao_sucesso'): ?>
 <p class="alerta alerta-sucesso">Operação realizada com sucesso!</p>
 <?php elseif ($status === 'erro_sql' || $status === 'erro_validacao' || $status === 'erro'): ?>
 <p class="alerta alerta-erro">Ocorreu um erro na operação.</p>
 <?php endif; ?>

 <p>
 <a href="cadastro_produto.php" class="btn btn-primary">Novo Produto</a>
 <a href="categorias.php" class="btn btn-secondary">Gerenciar Categorias</a>
</p>

 <?php if ($produtos): ?>
<div class="tabela-responsiva">
 <table>
<thead>
<tr>
 <th>ID</th>
 <th>Nome</th>
 <th>Preço</th>
 <th>Estoque</th>
 <th>Categoria</th>
 <th>Ações</th>
 </tr>
 </thead>
 <tbody>
  <?php foreach ($produtos as $produto): ?>
 <tr>
 <td><?= htmlspecialchars($produto['id_produto']) ?></td>
 <td><?= htmlspecialchars($produto['nome']) ?></td>
 <td class="coluna-estoque">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
 <td class="coluna-estoque"><?= htmlspecialchars($produto['estoque']) ?></td>
 <td><?= htmlspecialchars($produto['categoria']) ?></td>
 <td>
 <a href="cadastro_produto.php?id=<?= $produto['id_produto'] ?>" class="btn btn-acao editar">
 Editar
 </a>
 <a href="processa_crud.php?acao=excluir&id=<?= $produto['id_produto'] ?>" 
 class="btn btn-acao excluir"
  onclick="return confirmarExclusao(event)">
 Excluir
 </a>
</td>
 </tr>
 <?php endforeach; ?>
 </tbody>
 </table>
</div>
<?php else: ?>
 <p class="alerta alerta-erro">Nenhum produto cadastrado para gerenciamento.</p>
 <?php endif; ?>
</main>

<?php
require_once(__DIR__ . '/../includes/footer.php');
?>