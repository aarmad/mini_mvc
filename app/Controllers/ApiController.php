<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;

class ApiController extends Controller
{
    public function getProducts()
    {
        header('Content-Type: application/json');
        $products = Product::getAll();
        echo json_encode($products);
    }
    
    public function getCartCount()
    {
        header('Content-Type: application/json');
        $count = 0;
        if (isset($_SESSION['cart'])) {
            $count = array_sum($_SESSION['cart']);
        }
        echo json_encode(['count' => $count]);
    }
}