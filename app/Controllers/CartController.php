<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;

class CartController extends Controller
{
    /**
     * Vérifie si l'utilisateur est connecté
     */
    private function checkAuth()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder au panier";
            header('Location: /mini_mvc/public/user/login');
            exit;
        }
    }
    
    public function index()
    {
        $cart = $_SESSION['cart'] ?? [];
        $products = [];
        $total = 0;
        
        if (!empty($cart)) {
            foreach ($cart as $productId => $quantity) {
                $product = Product::findById($productId);
                if ($product) {
                    $product->quantity = $quantity;
                    $product->subtotal = $product->getPrice() * $quantity;
                    $products[] = $product;
                    $total += $product->subtotal;
                }
            }
        }
        
        $this->render('cart/index', [
            'products' => $products,
            'total' => $total
        ]);
    }
    
    public function add()
    {
        if (!isset($_POST['product_id'])) {
            $_SESSION['error'] = "Produit non spécifié";
            header('Location: /');
            exit;
        }
        
        $productId = (int)$_POST['product_id'];
        $product = Product::findById($productId);
        
        if (!$product) {
            $_SESSION['error'] = "Produit non trouvé";
            header('Location: /');
            exit;
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $currentQuantity = $_SESSION['cart'][$productId] ?? 0;
        $_SESSION['cart'][$productId] = $currentQuantity + 1;
        
        $_SESSION['success'] = "Produit ajouté au panier";
        header('Location: /cart');
        exit;
    }
    
    public function remove($id)
    {
        $productId = (int)$id;
        
        if (isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = "Produit retiré du panier";
        }
        
        header('Location: /cart');
        exit;
    }
    
    public function update()
    {
        if ($_POST && isset($_POST['quantities'])) {
            foreach ($_POST['quantities'] as $productId => $quantity) {
                $productId = (int)$productId;
                $quantity = (int)$quantity;
                
                if ($quantity <= 0) {
                    unset($_SESSION['cart'][$productId]);
                } else {
                    $_SESSION['cart'][$productId] = $quantity;
                }
            }
            $_SESSION['success'] = "Panier mis à jour";
        }
        
        header('Location: /cart');
        exit;
    }
}