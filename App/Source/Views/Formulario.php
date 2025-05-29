<?php
@session_start();
require_once __DIR__ . '/../Core/config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <title>Cadastro de Produto</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet" />
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
    <!-- Ícone do carrinho que abre a modal -->
    <a href="#" class="text-decoration-none position-relative d-inline-block" data-bs-toggle="modal" data-bs-target="#modalCarrinho">
      <i class="bi bi-cart4 fs-1 text-primary"></i>
      <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?= isset($_SESSION['produtos']) ? count($_SESSION['produtos']) : 0 ?>
        <span class="visually-hidden">itens no carrinho</span>
      </span>
    </a>
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
          <input type="text" class="form-control" id="nome" name="nome" required />
        </div>

        <div class="mb-3">
          <label for="preco" class="form-label">Preço (R$)</label>
          <input type="number" step="0.01" class="form-control" id="preco" name="preco" required />
        </div>

        <div class="mb-3">
          <label for="variacoes" class="form-label">Variações (ex: P, M, G)</label>
          <input type="text" class="form-control" id="variacoes" name="variacoes" />
        </div>

        <div class="mb-4">
          <label for="estoque" class="form-label">Estoque Inicial</label>
          <input type="number" class="form-control" id="estoque" name="estoque" required />
        </div>

        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-box-seam"></i> Cadastrar Produto
        </button>
      </form>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCarrinho" tabindex="-1" aria-labelledby="modalCarrinhoLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="modalCarrinhoLabel">Carrinho de Compras</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <?php if (empty($_SESSION['produtos'])): ?>
          <p>Seu carrinho está vazio.</p>
        <?php else: ?>

        <table class="table table-striped">
          <thead>
            <tr>
              <th>Produto</th>
              <th>Quantidade</th>
              <th>Preço Unitário</th>
              <th>Subtotal</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $total = 0;
            $produtosContados = [];

            foreach ($_SESSION['produtos'] as $produto) {
              $key = $produto['nome'] . '_' . $produto['variacao'];
              if (!isset($produtosContados[$key])) {
                $produtosContados[$key] = [
                  'produto' => $produto,
                  'quantidade' => 1
                ];
              } else {
                $produtosContados[$key]['quantidade']++;
              }
            }

            foreach ($produtosContados as $item):
              $subtotal = $item['produto']['preco'] * $item['quantidade'];
              $total += $subtotal;
          ?>
            <tr>
              <td><?= htmlspecialchars($item['produto']['nome']) ?> (<?= htmlspecialchars($item['produto']['variacao']) ?>)</td>
              <td><?= $item['quantidade'] ?></td>
              <td>R$ <?= number_format($item['produto']['preco'], 2, ',', '.') ?></td>
              <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
            </tr>
          <?php endforeach; ?>
          </tbody>
          <tfoot>
          <?php
            // Regra de frete
            if ($total >= 52 && $total <= 166.59) {
              $frete = 15.00;
            } elseif ($total > 200) {
              $frete = 0.00;
            } else {
              $frete = 20.00;
            }

            $totalComFrete = $total + $frete;
          ?>
            <tr>
              <th colspan="3" class="text-end">Subtotal</th>
              <th>R$ <?= number_format($total, 2, ',', '.') ?></th>
            </tr>
          </tfoot>
        </table>

        <!-- Área estilizada para frete e total atualizado -->
        <div class="p-3 my-3 border rounded shadow-sm bg-light">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fw-semibold fs-5">Frete:</span>
            <span class="fs-5 <?= $frete == 0 ? 'text-success' : 'text-dark' ?>">
              <?= $frete == 0 ? 'Grátis' : 'R$ ' . number_format($frete, 2, ',', '.') ?>
            </span>
          </div>
          <div class="d-flex justify-content-between align-items-center border-top pt-2">
            <span class="fw-bold fs-4">Total Atualizado:</span>
            <span class="fw-bold fs-4">R$ <?= number_format($totalComFrete, 2, ',', '.') ?></span>
          </div>
        </div>

        <!-- Formulário de CEP -->
        <div class="mt-4">
          <label for="cepInput" class="form-label fw-semibold">Verifique o endereço pelo CEP:</label>
          <div class="input-group mb-3">
            <input type="text" id="cepInput" class="form-control" placeholder="Digite seu CEP (somente números)" maxlength="8" pattern="\d{8}">
            <button class="btn btn-primary" type="button" id="btnConsultarCep">Consultar</button>
          </div>
          <div id="resultadoCep" class="small fst-italic text-secondary"></div>
        </div>

        <?php endif; ?>
      </div>

      <div class="modal-footer">
        <!-- Botão Confirme CEP, desabilitado inicialmente -->
        <button type="button" class="btn btn-success" id="btnConfirmarCep" disabled>Confirme CEP</button>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const btnConsultarCep = document.getElementById('btnConsultarCep');
  const cepInput = document.getElementById('cepInput');
  const resultadoCep = document.getElementById('resultadoCep');
  const btnConfirmarCep = document.getElementById('btnConfirmarCep');

  function limparMensagem() {
    resultadoCep.textContent = '';
    resultadoCep.classList.remove('text-danger', 'text-success');
    btnConfirmarCep.disabled = true;
    btnConfirmarCep.textContent = 'Confirme CEP';
  }

  btnConsultarCep.addEventListener('click', function() {
    const cep = cepInput.value.trim();

    // Validar CEP: 8 dígitos numéricos
    if (!/^\d{8}$/.test(cep)) {
      resultadoCep.textContent = 'CEP inválido! Informe 8 números.';
      resultadoCep.classList.add('text-danger');
      btnConfirmarCep.disabled = true;
      return;
    }

    resultadoCep.textContent = 'Consultando...';
    resultadoCep.classList.remove('text-danger', 'text-success');
    btnConfirmarCep.disabled = true;

    // Chamada à API ViaCEP
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(response => response.json())
      .then(data => {
        if (data.erro) {
          resultadoCep.textContent = 'CEP não encontrado.';
          resultadoCep.classList.add('text-danger');
          btnConfirmarCep.disabled = true;
          btnConfirmarCep.textContent = 'Confirme CEP';
        } else {
          // Exibe endereço formatado
          resultadoCep.textContent = `${data.logradouro}, ${data.bairro}, ${data.localidade} - ${data.uf}`;
          resultadoCep.classList.add('text-success');
          btnConfirmarCep.disabled = false;
          btnConfirmarCep.textContent = 'Pronto para envio';
        }
      })
      .catch(() => {
        resultadoCep.textContent = 'Erro ao consultar CEP. Tente novamente.';
        resultadoCep.classList.add('text-danger');
        btnConfirmarCep.disabled = true;
        btnConfirmarCep.textContent = 'Confirme CEP';
      });
  });

  // Se o usuário alterar o CEP, desabilita botão e limpa mensagem
  cepInput.addEventListener('input', limparMensagem);

  // ação ao clicar no botão "Pronto para envio"
  btnConfirmarCep.addEventListener('click', function() {
    if (!btnConfirmarCep.disabled) {
      alert('CEP confirmado e pronto para envio!');
      const modalElement = document.getElementById('modalCarrinho');
      const modal = bootstrap.Modal.getInstance(modalElement);
      if(modal) modal.hide();
    }
  });
});
</script>

</body>
</html>
