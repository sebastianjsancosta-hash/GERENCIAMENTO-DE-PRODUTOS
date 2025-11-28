<?php

if (!defined('BASE_PATH')) {
    require_once(__DIR__ . '/../config.php'); 
}

function cadastrarCategoria($pdo, $nome) {
    $sql = "INSERT INTO categorias (nome) VALUES (:nome)";
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (\PDOException $e) {
        error_log("Erro ao cadastrar categoria: " . $e->getMessage());
        return false;
    }
}

function editarCategoria($pdo, $id, $nome) {
    $sql = "UPDATE categorias SET nome = :nome WHERE id_categoria = :id"; 
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (\PDOException $e) {
        error_log("Erro ao editar categoria: " . $e->getMessage());
        return false;
    }
}

function temProdutosVinculados($pdo, $id) {
    $sql_check = "SELECT COUNT(*) FROM produtos WHERE id_categoria = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt_check->execute();
    return $stmt_check->fetchColumn() > 0;
}

function excluirCategoria($pdo, $id) {
    if (temProdutosVinculados($pdo, $id)) {
        return 'vinculo'; 
    }
    
    $sql = "DELETE FROM categorias WHERE id_categoria = :id"; 
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (\PDOException $e) {
        error_log("Erro ao excluir categoria: " . $e->getMessage());
        return false; 
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['acao'])) {
    
    $acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_SPECIAL_CHARS);
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    
    $status = 'erro'; 

    if ($acao === 'cadastrar' && $nome) {
        $sucesso = cadastrarCategoria($pdo, $nome);
        $status = $sucesso ? 'sucesso' : 'erro_sql';
        
    } elseif ($acao === 'editar' && $id && $nome) {
        $sucesso = editarCategoria($pdo, $id, $nome);
        $status = $sucesso ? 'sucesso' : 'erro_sql';
        
    } elseif ($acao === 'excluir') {
        $id_excluir = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id_excluir) {
            $resultado_exclusao = excluirCategoria($pdo, $id_excluir);
            
            if ($resultado_exclusao === 'vinculo') {
                $status = 'erro_vinculo'; // Novo status específico
            } elseif ($resultado_exclusao === true) {
                $status = 'exclusao_sucesso';
            } else {
                $status = 'erro_sql'; 
            }
        } else {
            $status = 'erro_validacao';
        }
    }
    
    header("Location: categorias.php?status=$status");
    exit;
}
?>