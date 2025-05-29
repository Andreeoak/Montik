<?php
require_once __DIR__ . '/../Core/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Cadastro de Produto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #e0f7fa, #e8f5e9);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .form-container {
      max-width: 500px;
      width: 100%;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <div class="text-center mb-3">
      <div class="form-container">
        <div class="text-center mb-3">
            <a href="carrinhoRota.php?action=show" class="text-decoration-none position-relative d-inline-block">
            <i class="bi bi-cart4 fs-1 text-primary"></i>
            <!-- Badge para quantidade de itens no carrinho -->
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                <span class="visually-hidden">itens no carrinho</span>
            </span>
            </a>
        </div>
      <h2 class="mt-2">Cadastro de Produto</h2>
    </div>

    <div class="card shadow-lg">
      <div class="card-body p-4">
        <?php if (!empty($mensagem)) : ?>
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $mensagem ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endif; ?>

        <form method="POST" action="../Controllers/ProductController.php">
          <div class="mb-3">
            <label for="nome" class="form-label">Nome do Produto</label>
            <input type="text" class="form-control" id="nome" name="nome" required>
          </div>

          <div class="mb-3">
            <label for="preco" class="form-label">Preço (R$)</label>
            <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
          </div>

          <div class="mb-3">
            <label for="variacoes" class="form-label">Variações (ex: P, M, G)</label>
            <input type="text" class="form-control" id="variacoes" name="variacoes">
          </div>

          <div class="mb-4">
            <label for="estoque" class="form-label">Estoque Inicial</label>
            <input type="number" class="form-control" id="estoque" name="estoque" required>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-box-seam"></i> Cadastrar Produto
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php

// Simulando valores fixos do produto para teste.
// Idealmente você pega do formulário, mas para link simples fica assim:
$id = 1;
$nome = "Produto Teste";
$preco = 99.90;
$estoque = 10;
$qtd = 1;
?>

<!-- Link para adicionar ao carrinho -->
<a href="carrinhoRota.php?action=add&id=<?= $id ?>&nome=<?= urlencode($nome) ?>&preco=<?= $preco ?>&qtd=<?= $qtd ?>&estoque=<?= $estoque ?>"
   class="btn btn-success mt-3">
   <i class="bi bi-cart-plus"></i> Adicionar Produto Teste ao Carrinho
</a>