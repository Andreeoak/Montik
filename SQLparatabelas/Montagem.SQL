-- Script para criar o banco de dados e tabelas para uma loja online simples

-- Criar o banco de dados
CREATE DATABASE IF NOT EXISTS loja;
USE loja;

-- Tabela de Produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    descricao TEXT,
    variacao VARCHAR(50), -- Ex: "Tamanho M", "Cor Azul"
    preco DECIMAL(10, 2) NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Estoque
CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id)
);

-- Tabela de Cupons
CREATE TABLE cupons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    desconto_percentual DECIMAL(5, 2), -- Ex: 10.00 = 10%
    desconto_fixo DECIMAL(10, 2),      -- Ex: 20.00 = R$20 de desconto
    validade DATE,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Pedidos
CREATE TABLE pedidos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    data DATETIME DEFAULT CURRENT_TIMESTAMP,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL,
    cupom_id INT,
    total DECIMAL(10, 2) NOT NULL,
    status ENUM('pendente', 'pago', 'enviado', 'cancelado') DEFAULT 'pendente',
    FOREIGN KEY (produto_id) REFERENCES produtos(id),
    FOREIGN KEY (cupom_id) REFERENCES cupons(id)
);

-- Comentários sobre o modelo:
--O campo total em pedidos pode ser calculado com base na quantidade e no preço do produto, menos o desconto (mas é armazenado diretamente para simplificar).
--cupons podem aplicar tanto desconto percentual quanto fixo (um dos dois pode ser nulo).
--estoque é separado para controle de quantidade e atualizações.
--pedidos armazena uma linha por produto pedido; se quiser representar múltiplos produtos por pedido, seria necessário criar uma tabela pedidos e outra itens_pedido.
--Se quiser esse modelo mais avançado (com pedidos contendo múltiplos produtos), posso ajustar.



