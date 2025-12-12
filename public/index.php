<?php

declare(strict_types=1);

define('BASE_PATH', '/mini_mvc/public');

require dirname(path: __DIR__) . '/vendor/autoload.php';

use Mini\Core\Router;

// Table des routes minimaliste
$routes = [
    // Pages publiques
    ['GET', '/', ['Mini\Controllers\HomeController', 'index']],
    ['GET', '/catalogue', ['Mini\Controllers\HomeController', 'catalogue']],
    
    // Authentification
    ['GET', '/login', ['Mini\Controllers\UserController', 'login']],
    ['POST', '/login', ['Mini\Controllers\UserController', 'login']],
    ['GET', '/register', ['Mini\Controllers\UserController', 'register']],
    ['POST', '/register', ['Mini\Controllers\UserController', 'register']],
    ['GET', '/logout', ['Mini\Controllers\UserController', 'logout']],
    
    // Espace client
    ['GET', '/dashboard', ['Mini\Controllers\UserController', 'dashboard']],
    
    // Panier
    ['GET', '/cart', ['Mini\Controllers\CartController', 'index']],
    ['POST', '/cart/add', ['Mini\Controllers\CartController', 'add']],
    ['GET', '/cart/remove', ['Mini\Controllers\CartController', 'remove']],
    ['POST', '/cart/update', ['Mini\Controllers\CartController', 'update']],
    
    // Commandes
    ['GET', '/checkout', ['Mini\Controllers\OrderController', 'checkout']],
    ['POST', '/order/create', ['Mini\Controllers\OrderController', 'create']],
    
    // API pour AJAX (bonus)
    ['GET', '/api/products', ['Mini\Controllers\ApiController', 'getProducts']],
    ['GET', '/api/cart/count', ['Mini\Controllers\ApiController', 'getCartCount']],
];
// Bootstrap du router
$router = new Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);