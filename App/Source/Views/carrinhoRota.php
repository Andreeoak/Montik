<?php
require_once __DIR__ . '/../Core/config.php'; // ajustar conforme caminho real

require_once __DIR__ . '/../Controllers/CartController.php';

$controller = new CartController();

$action = $_GET['action'] ?? 'show';


switch ($action) {
    case 'add':
        $id = $_GET['id'] ?? null;
        $nome = $_GET['nome'] ?? 'Produto';
        $preco = isset($_GET['preco']) ? (float)$_GET['preco'] : 0;
        $qtd = isset($_GET['qtd']) ? (int)$_GET['qtd'] : 1;
        $estoque = isset($_GET['estoque']) ? (int)$_GET['estoque'] : 10; // Default 10 para testes

        if ($id) {
            $controller->add($id, $nome, $preco, $qtd, $estoque);
        } else {
            $controller->show();
        }
        break;

    case 'remove':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $controller->remove($id);
        } else {
            $controller->show();
        }
        break;

    case 'show':
    default:
        $controller->show();
        break;
}