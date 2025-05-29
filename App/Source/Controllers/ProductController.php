<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

            //var_dump($nome, $preco, $variacoes, $estoque);

            foreach ($variacoes as $variacao) {
                $produto = ProductModel::findByNameAndVariacao($nome, $variacao);

                if ($produto) {
                    // Atualizar produto existente
                    $produto->setPrice($preco);
                    $produto->setStock($produto->getStock() + $estoque); // soma ao estoque atual
                    $produto->update();
                } else {
                    // Criar novo produto
                    $novo = new ProductModel(null, $nome, $preco, $variacao, $estoque);
                    $novo->save();
                }
            }

            $mensagem = "Produto(s) processado(s) com sucesso.";
        }
        include __DIR__ . '/../Views/Formulario.php';
    }
}
?>