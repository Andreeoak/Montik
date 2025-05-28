<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once 'Database.php';

class Produto {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    // Create - Inserir produto com variações e estoque inicial
    public function create(string $nome, float $preco, array $variacoes, int $estoque): int {
        // Começa transação
        $this->pdo->beginTransaction();

        try {
            // Inserir produto sem variação (ou pode inserir variação na tabela produtos)
            $sql = "INSERT INTO produtos (nome, preco, variacao) VALUES (?, ?, ?)";
            
            // Para simplificar: vamos salvar cada variação como um produto separado.
            // Se variacoes estiver vazia, insere um produto sem variação
            $produtoId = null;
            if (count($variacoes) > 0 && !empty($variacoes[0])) {
                foreach ($variacoes as $variacao) {
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$nome, $preco, $variacao]);
                    $produtoId = $this->pdo->lastInsertId();

                    // Inserir estoque para essa variação
                    $sqlEstoque = "INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)";
                    $stmtEstoque = $this->pdo->prepare($sqlEstoque);
                    $stmtEstoque->execute([$produtoId, $estoque]);
                }
            } else {
                // Sem variações
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$nome, $preco, null]);
                $produtoId = $this->pdo->lastInsertId();

                $sqlEstoque = "INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)";
                $stmtEstoque = $this->pdo->prepare($sqlEstoque);
                $stmtEstoque->execute([$produtoId, $estoque]);
            }

            $this->pdo->commit();
            return $produtoId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    // Read - Buscar todos os produtos com estoque
    public function getAll(): array {
        $sql = "SELECT p.id, p.nome, p.preco, p.variacao, e.quantidade as estoque
                FROM produtos p
                LEFT JOIN estoque e ON p.id = e.produto_id
                ORDER BY p.nome";

        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read - Buscar produto por ID com estoque
    public function getById(int $id): ?array {
        $sql = "SELECT p.id, p.nome, p.preco, p.variacao, e.quantidade as estoque
                FROM produtos p
                LEFT JOIN estoque e ON p.id = e.produto_id
                WHERE p.id = ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        return $produto ?: null;
    }

    // Update - Atualizar dados do produto e estoque
    public function update(int $id, string $nome, float $preco, ?string $variacao, int $estoque): bool {
        $this->pdo->beginTransaction();

        try {
            $sqlProduto = "UPDATE produtos SET nome = ?, preco = ?, variacao = ? WHERE id = ?";
            $stmtProduto = $this->pdo->prepare($sqlProduto);
            $stmtProduto->execute([$nome, $preco, $variacao, $id]);

            $sqlEstoque = "UPDATE estoque SET quantidade = ? WHERE produto_id = ?";
            $stmtEstoque = $this->pdo->prepare($sqlEstoque);
            $stmtEstoque->execute([$estoque, $id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    // Delete - Apagar produto e seu estoque
    public function delete(int $id): bool {
        $this->pdo->beginTransaction();

        try {
            $sqlEstoque = "DELETE FROM estoque WHERE produto_id = ?";
            $stmtEstoque = $this->pdo->prepare($sqlEstoque);
            $stmtEstoque->execute([$id]);

            $sqlProduto = "DELETE FROM produtos WHERE id = ?";
            $stmtProduto = $this->pdo->prepare($sqlProduto);
            $stmtProduto->execute([$id]);

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
}