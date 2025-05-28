<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Carregar configurações primeiro
require_once __DIR__ . '/Source/Core/config.php';

// Depois carregar o controller
require_once __DIR__ . '/Source/Controllers/ProductController.php';

// Criar instância do controlador
$controller = new ProductController();

// Chamar método que lida com o cadastro
$controller->cadastrar();