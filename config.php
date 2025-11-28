<?php
// config

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

// Raiz - definição global
define("BASE_PATH", __DIR__);

// link itens
$baseUrl = '/catalogo-produtos/';

// Link BD

require_once BASE_PATH . '/includes/conexao.php'; 
?>