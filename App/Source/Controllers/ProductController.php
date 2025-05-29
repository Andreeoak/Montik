<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // <--- Importante! Iniciar a sessão

require_once __DIR__ . '/../Entities/ProductModel.php';
require_once __DIR__ . '/../Core/config.php';

class ProductController
{
    public function cadastrar()
    {
        $mensagem = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $nome = $_POST['nome'] ?? '';
            $preco = floatval($_POST['preco'] ?? 0);
            $estoque = intval($_POST['estoque'] ?? 0);
            $variacoesString = $_POST['variacoes'] ?? '';

            // Transformar string em array, removendo espaços e vazios
            $variacoes = array_filter(array_map('trim', explode(',', $variacoesString)));

            // Inicializa sessão de produtos, se ainda não estiver
            if (!isset($_SESSION['produtos'])) {
                $_SESSION['produtos'] = [];
            }

            foreach ($variacoes as $variacao) {
                $produto = ProductModel::findByNameAndVariacao($nome, $variacao);

                if ($produto) {
                    // Atualizar produto existente
                    $produto->setPrice($preco);
                    $produto->setStock($produto->getStock() + $estoque);
                    $produto->update();
                } else {
                    // Criar novo produto
                    $novo = new ProductModel(null, $nome, $preco, $variacao, $estoque);
                    $novo->save();
                }

                // Salvar na sessão também
                $_SESSION['produtos'][] = [
                    'nome' => $nome,
                    'preco' => $preco,
                    'variacao' => $variacao,
                    'estoque' => $estoque
                ];
            }

            $mensagem = "Produto(s) processado(s) com sucesso.";
        }

        include __DIR__ . '/../Views/Formulario.php';
    }
}

?>