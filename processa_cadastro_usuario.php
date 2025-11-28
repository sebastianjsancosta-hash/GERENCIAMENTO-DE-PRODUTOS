<?php
require_once 'config.php';
require_once 'admin/processa_crud.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = filter_input(INPUT_POST, 'senha', FILTER_DEFAULT); 

    // Validação
    if (!$nome || !$email || !$senha) {
        header("Location: cadastro_usuario.php?status=erro_validacao");
        exit();
    }

    // Encriptar a senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Nível padrão
    $nivel = 'usuario'; 

    try {
        $stmt_check = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
        $stmt_check->execute([$email]);
        if ($stmt_check->fetchColumn() > 0) {
            header("Location: cadastro_usuario.php?status=erro_email_existente");
            exit();
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, nivel) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nome, $email, $senha_hash, $nivel])) {
            header("Location: login.php?status=cadastro_sucesso");
        } else {
            header("Location: cadastro_usuario.php?status=erro_sql");
        }
    } catch (PDOException $e) {
        header("Location: cadastro_usuario.php?status=erro_sql");
    }

} else {
    // Acesso p/ pág de cadastro
    header("Location: cadastro_usuario.php");
}
exit();
?>