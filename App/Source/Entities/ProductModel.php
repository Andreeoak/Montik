<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../Core/Produto.php';
require_once __DIR__ . '/../Core/config.php';

class ProductModel {
    private ?int $id;
    private string $name;
    private float $price;
    private ?string $variation;
    private int $stock;

    private Produto $produtoDAO;

    public function __construct(
        ?int $id = null,
        string $name = '',
        float $price = 0.0,
        ?string $variation = null,
        int $stock = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->variation = $variation;
        $this->stock = $stock;

        $this->produtoDAO = new Produto();
    }

    // Getters e Setters
    public function getId(): ?int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getPrice(): float { return $this->price; }
    public function getVariation(): ?string { return $this->variation; }
    public function getStock(): int { return $this->stock; }

    public function setId(?int $id): void { $this->id = $id; }
    public function setName(string $name): void { $this->name = $name; }
    public function setPrice(float $price): void { $this->price = $price; }
    public function setVariation(?string $variation): void { $this->variation = $variation; }
    public function setStock(int $stock): void { $this->stock = $stock; }

    // Métodos CRUD

    public function save(): bool {
        if ($this->id === null) {
            // Criar produto no banco
            // Aproveitando o método create da classe Produto:
            try {
                $idCriado = $this->produtoDAO->create($this->name, $this->price, $this->variation ? [$this->variation] : [], $this->stock);
                if ($idCriado) {
                    $this->id = (int)$idCriado;
                    return true;
                }
                return false;
            } catch (PDOException $e) {
                echo "Erro ao salvar produto: " . $e->getMessage();
            }
            return false;
        
        } else {
            // Atualizar produto existente
            try {
                return $this->produtoDAO->update($this->id, $this->name, $this->price, $this->variation, $this->stock);
            } catch (PDOException $e) {
                echo "Erro ao atualizar produto: " . $e->getMessage();
            }
        }
    }

    public function delete(): bool {
        if ($this->id === null) {
            return false;
        }
        $result = $this->produtoDAO->delete($this->id);
        if ($result) {
            $this->id = null;
        }
        return $result;
    }

    public function load(int $id): bool {
        $produtoData = $this->produtoDAO->getById($id);
        if (!$produtoData) return false;

        $this->id = (int)$produtoData['id'];
        $this->name = $produtoData['nome'];
        $this->price = (float)$produtoData['preco'];
        $this->variation = $produtoData['variacao'];
        $this->stock = (int)$produtoData['estoque'];

        return true;
    }

    // Método para buscar todos os produtos como objetos ProductModel
    public function getAll(): array {
        $produtosData = $this->produtoDAO->getAll();
        $produtos = [];

        foreach ($produtosData as $p) {
            $produtos[] = new ProductModel(
                (int)$p['id'],
                $p['nome'],
                (float)$p['preco'],
                $p['variacao'],
                (int)$p['estoque']
            );
        }

        return $produtos;
    }

    public static function findByNameAndVariacao($nome, $variacao)
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE nome = ? AND variacao = ?");
        $stmt->execute([$nome, $variacao]);
        $dados = $stmt->fetch();

        if ($dados) {
            return new ProductModel(
                $dados['id'],
                $dados['nome'],
                $dados['preco'],
                $dados['variacao'],
                $dados['quantidade'] ?? 0
            );
        }

        return null;
    }

    public function update()
    {
        $pdo = Database::connect();

        // Atualiza produto
        $stmt = $pdo->prepare("UPDATE produtos SET preco = ? WHERE id = ?");
        $stmt->execute([$this->price, $this->id]);

        // Atualiza estoque
        $stmtEstoque = $pdo->prepare("UPDATE estoque SET quantidade = ? WHERE produto_id = ?");
        $stmtEstoque->execute([$this->stock, $this->id]);
    }
}