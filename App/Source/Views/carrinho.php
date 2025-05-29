<?php
require_once __DIR__ . '/../Core/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Carrinho de Compras</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Carrinho de Compras</h2>

    <?php if (!empty($_SESSION['cart_error'])): ?>
      <div class="alert alert-danger"><?= $_SESSION['cart_error']; ?></div>
    <?php endif; ?>

    <?php if (empty($cart)): ?>
      <div class="alert alert-info">Seu carrinho está vazio.</div>
    <?php else: ?>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>Produto</th>
            <th>Preço Unitário</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart as $id => $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['nome']) ?></td>
              <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
              <td><?= $item['quantidade'] ?></td>
              <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
              <td>
                <a href="carrinhoRota.php?action=remove&id=<?= $id ?>" class="btn btn-danger btn-sm" onclick="return confirm('Remover produto?')">
                  <i class="bi bi-trash"></i> Remover
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <h4>Total: R$ <?= number_format($total, 2, ',', '.') ?></h4>

      <a href="Formulario.php" class="btn btn-primary">Continuar Comprando</a>
      <button class="btn btn-success">Finalizar Compra</button>
    <?php endif; ?>
  </div>
</body>
</html>