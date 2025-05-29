<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once __DIR__ . '/../Core/config.php';
require_once __DIR__ . '/../Core/Cart.php'; // ou onde estiver esse arquivo

class CartController {
    public function show() {
        $cart = Cart::getItems();
        $total = Cart::getTotal();
        include __DIR__ . '/../Views/carrinho.php';
    }

    public function add($id, $nome, $preco, $quantidade, $estoque) {
        $success = Cart::addProduct($id, $nome, $preco, $quantidade, $estoque);
        if (!$success) {
            // TODO: Tratar erro, como estoque insuficiente
        }
        $this->show();
    }

    public function remove($id) {
        Cart::removeProduct($id);
        $this->show();
    }
}