<?php
session_start();
require_once __DIR__ . '/../Core/config.php';
class Cart {

    public static function addProduct($produtoId, $nome, $preco, $quantidade, $estoque) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Se já tem o produto, soma a quantidade (sem passar do estoque)
        if (isset($_SESSION['cart'][$produtoId])) {
            $novaQtd = $_SESSION['cart'][$produtoId]['quantidade'] + $quantidade;
            if ($novaQtd > $estoque) {
                return false; // Não pode ultrapassar estoque
            }
            $_SESSION['cart'][$produtoId]['quantidade'] = $novaQtd;
        } else {
            if ($quantidade > $estoque) {
                return false; // Não pode ultrapassar estoque
            }
            $_SESSION['cart'][$produtoId] = [
                'nome' => $nome,
                'preco' => $preco,
                'quantidade' => $quantidade,
                'estoque' => $estoque
            ];
        }
        return true;
    }

    public static function removeProduct($produtoId) {
        if (isset($_SESSION['cart'][$produtoId])) {
            unset($_SESSION['cart'][$produtoId]);
        }
    }

    public static function updateQuantity($produtoId, $quantidade) {
        if (isset($_SESSION['cart'][$produtoId])) {
            if ($quantidade <= 0) {
                self::removeProduct($produtoId);
            } elseif ($quantidade <= $_SESSION['cart'][$produtoId]['estoque']) {
                $_SESSION['cart'][$produtoId]['quantidade'] = $quantidade;
            }
        }
    }

    public static function getTotal() {
        $total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $total += $item['preco'] * $item['quantidade'];
            }
        }
        return $total;
    }

    public static function getItems() {
        return $_SESSION['cart'] ?? [];
    }

    public static function clear() {
        unset($_SESSION['cart']);
    }
}