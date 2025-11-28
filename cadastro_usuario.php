<?php
session_start();
require_once 'config.php'; 

$status = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
$mensagem = '';
$class_alerta = '';

if ($status === 'erro_validacao') {
    $mensagem = 'Erro: Todos os campos são obrigatórios.';
    $class_alerta = 'alerta-erro';
} elseif ($status === 'erro_email_existente') {
    $mensagem = 'Erro: Este E-mail já está cadastrado.';
    $class_alerta = 'alerta-erro';
} elseif ($status === 'erro_sql') {
    $mensagem = 'Erro interno: Não foi possível realizar o cadastro.';
    $class_alerta = 'alerta-erro';
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="css/style.css"> 
</head>
<body>
    <div class="container">
        <h2>Novo Cadastro</h2>

        <?php if ($mensagem): ?>
            <p class="<?php echo $class_alerta; ?>" style="color: red; font-weight: bold;"><?php echo $mensagem; ?></p>
        <?php endif; ?>
        <form action="processa_cadastro_usuario.php" method="POST">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>

            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
        <p>Já tem conta? <a href="login.php">Fazer Login</a></p>
    </div>
</body>
</html>