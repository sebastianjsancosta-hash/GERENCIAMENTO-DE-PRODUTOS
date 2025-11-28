<?php
session_start();
require_once 'config.php'; 

require_once 'admin/processa_crud.php';

// Redirecionamento

if (isset($_SESSION['user_id'])) {
    header("Location: admin/index.php");
    exit;
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha']; 
    
    // Busca do usuario
    $query = $pdo->prepare("SELECT id, nome, senha, nivel FROM usuarios WHERE email = ?"); 
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC); 

    // Verificacao da senha
    if ($user && password_verify($senha, $user['senha'])) {
        
        $_SESSION['user_id'] = $user['id']; 
        $_SESSION['nome'] = $user['nome'];
        $_SESSION['nivel'] = $user['nivel'];

        $pdo->prepare("UPDATE usuarios SET ultimo_login = NOW() WHERE id = ?")->execute([$user['id']]);

        salvarLog("SUCESSO LOGIN: Usuário ID {$user['id']} logou."); 
        
        header("Location: admin/index.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos!"; 
    }
}
// Inicio do html
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
<h2>Login</h2>

<?php 
// Status

if (isset($erro)) { 
    echo "<p class='alerta alerta-erro'>$erro</p>"; 
}

$status_cadastro = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_SPECIAL_CHARS);
if ($status_cadastro === 'cadastro_sucesso') {
    echo "<p class='alerta alerta-sucesso'>Cadastro realizado com sucesso! Faça login para entrar.</p>"; 
}

?>

<form method="post">
    <label>E-mail:</label>
    <input type="email" name="email" required>

    <label>Senha:</label>
    <input type="password" name="senha" required>

    <button type="submit">Entrar</button>
</form>

<p>Não tem uma conta? <a href="cadastro_usuario.php">Cadastre-se</a></p>

</div>
</body>
</html>