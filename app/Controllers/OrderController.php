<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Order;
use Mini\Models\Product;

class OrderController extends Controller
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
    
    public function checkout()
    {
        $this->checkAuth();
        
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = "Votre panier est vide";
            header('Location: /cart');
            exit;
        }
        
        $products = [];
        $total = 0;
        
        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);
            if ($product && $product->isAvailable($quantity)) {
                $product->quantity = $quantity;
                $product->subtotal = $product->getPrice() * $quantity;
                $products[] = $product;
                $total += $product->subtotal;
            }
        }
        
        $this->render('order/checkout', [
            'products' => $products,
            'total' => $total
        ]);
    }
    
    public function create()
    {
        $this->checkAuth();
        
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = "Votre panier est vide";
            header('Location: /cart');
            exit;
        }
        
        $items = [];
        $total = 0;
        
        // Vérifier le stock et préparer les items
        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);
            if (!$product || !$product->isAvailable($quantity)) {
                $_SESSION['error'] = "Stock insuffisant pour: " . ($product->getName() ?? 'un produit');
                header('Location: /cart');
                exit;
            }
            
            $items[] = [
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->getPrice()
            ];
            $total += $product->getPrice() * $quantity;
        }
        
        try {
            $order = new Order();
            $orderId = $order->createOrder($_SESSION['user']->getId(), $items, $total);
            
            // Vider le panier
            unset($_SESSION['cart']);
            
            $_SESSION['success'] = "Commande #$orderId passée avec succès !";
            header('Location: /dashboard');
            exit;
            
        } catch (\Exception $e) {
            $_SESSION['error'] = "Erreur lors de la commande: " . $e->getMessage();
            header('Location: /cart');
            exit;
        }
    }
}