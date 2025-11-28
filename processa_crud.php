<?php

// 1. INCLUSÕES E CONFIGURAÇÃO

if (!defined('BASE_PATH')) {
    require_once(__DIR__ . '/../config.php');
}


function listarProdutos($pdo) {
    $sql = "SELECT 
                p.id_produto,
                p.nome,
                p.descricao,
                p.preco,
                p.estoque,
                p.id_categoria,
                c.nome AS categoria
            FROM produtos AS p
            INNER JOIN categorias AS c 
                ON p.id_categoria = c.id_categoria
            ORDER BY p.id_produto DESC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar produtos: " . $e->getMessage());
        return false;
    }
}

function listarCategorias($pdo) {
    $sql = "SELECT id_categoria AS id, nome FROM categorias ORDER BY nome ASC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao listar categorias: " . $e->getMessage());
        return [];
    }
}

function buscarProdutoPorId($pdo, $id) {
    $sql = "SELECT * FROM produtos WHERE id_produto = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Erro ao buscar produto: " . $e->getMessage());
        return false;
    }
}

function lerDetalhesProduto(PDO $pdo, int $id_produto): array|false {
    try {
        $sql = "SELECT 
                    p.*, 
                    c.nome AS categoria
                FROM produtos p
                JOIN categorias c ON p.id_categoria = c.id_categoria
                WHERE p.id_produto = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id_produto, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        error_log("Erro ao buscar detalhes do produto: " . $e->getMessage());
        return false;
    }
}

// Log

function salvarLog(string $mensagem) {
    $arquivo = __DIR__ . '/../log_eventos.txt';
    $data = date('Y-m-d H:i:s');
    $linha = "[{$data}] - {$mensagem}\n";

    if ($handle = @fopen($arquivo, 'a')) {
        fwrite($handle, $linha);
        fclose($handle);
    }
}
// Crud


if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['acao'])) {

    $acao = filter_input(INPUT_GET, 'acao', FILTER_SANITIZE_SPECIAL_CHARS);

    function validarDados() {
        $nome        = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $descricao   = filter_input(INPUT_POST, 'descricao', FILTER_SANITIZE_SPECIAL_CHARS);
        $preco       = filter_input(INPUT_POST, 'preco', FILTER_VALIDATE_FLOAT);
        $estoque     = filter_input(INPUT_POST, 'estoque', FILTER_VALIDATE_INT);
        $id_categoria = filter_input(INPUT_POST, 'id_categoria', FILTER_VALIDATE_INT);
        $id_produto   = filter_input(INPUT_POST, 'id_produto', FILTER_VALIDATE_INT);

        if (!$nome || !$descricao || $preco === false || $estoque === false || $id_categoria === false) {
            return false;
        }

        return compact('nome', 'descricao', 'preco', 'estoque', 'id_categoria', 'id_produto');
    }

    // Cadastro-edit
    if ($acao === 'cadastrar' || $acao === 'editar') {

        global $pdo;
        $dados = validarDados();

        if ($dados === false) {
            header("Location: cadastro_produto.php?status=erro_validacao");
            exit;
        }

        try {
            if ($acao === 'cadastrar') {
                $sql = "INSERT INTO produtos (nome, descricao, preco, estoque, id_categoria)
                        VALUES (:nome, :descricao, :preco, :estoque, :id_categoria)";
                $stmt = $pdo->prepare($sql);

            } else {
                $sql = "UPDATE produtos SET 
                            nome = :nome,
                            descricao = :descricao,
                            preco = :preco,
                            estoque = :estoque,
                            id_categoria = :id_categoria
                        WHERE id_produto = :id_produto";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id_produto', $dados['id_produto'], PDO::PARAM_INT);
            }

            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':descricao', $dados['descricao']);
            $stmt->bindValue(':preco', $dados['preco']);
            $stmt->bindValue(':estoque', $dados['estoque'], PDO::PARAM_INT);
            $stmt->bindValue(':id_categoria', $dados['id_categoria'], PDO::PARAM_INT);
            $stmt->execute();

            header("Location: index.php?status=sucesso");
            exit;

        } catch (PDOException $e) {
            error_log("Erro SQL: " . $e->getMessage());
            header("Location: index.php?status=erro_sql");
            exit;
        }
    }

    // Exclusao
    if ($acao === 'excluir' || $acao === 'excluir_produto') {
        global $pdo;

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id) {
            try {
                $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = :id");
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                header("Location: index.php?status=exclusao_sucesso");
                exit;

            } catch (PDOException $e) {
                error_log("Erro ao excluir produto: " . $e->getMessage());
                header("Location: index.php?status=erro_sql");
                exit;
            }
        }
    }
}
?>
