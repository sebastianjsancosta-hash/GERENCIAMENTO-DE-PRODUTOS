<?php

$logado = isset($_SESSION['user_id']); 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Produtos</title>

    <link rel="stylesheet" href="<?= $baseUrl ?>css/style.css">

</head>
<body>
     <header>
        <div class="logo">Gerenciamento de produtos</div>
        <nav>
     <ul>
        <li><a href="<?= $baseUrl ?>index.php">Home</a></li>
        <li><a href="<?= $baseUrl ?>produtos.php">Catálogo</a></li>
        
        <?php if ($logado): ?>
                        <li><a href="<?= $baseUrl ?>admin/index.php">Gerenciar Produtos</a></li>
            <li><a href="<?= $baseUrl ?>logout.php">Sair</a></li>
        <?php else: ?>
            <li><a href="<?= $baseUrl ?>login.php">Login</a></li>
            <li><a href="<?= $baseUrl ?>cadastro_usuario.php">Registrar</a></li>
        <?php endif; ?>

    </ul>
       </nav>
    </header>

    <script src="<?= $baseUrl ?>js/script.js"></script>