<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Order;
use Mini\Models\Product;

class OrderController extends Controller
{
    public function checkout()
    {
        // Vérifier l'authentification
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vous devez être connecté pour passer commande";
            header('Location: /user/login');
            exit;
        }
        
        // Vérifier que le panier n'est pas vide
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = "Votre panier est vide";
            header('Location: /cart');
            exit;
        }
        
        $this->render('order/checkout');
    }
    
    public function create()
    {
        // Vérifier l'authentification
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vous devez être connecté pour passer commande";
            header('Location: /user/login');
            exit;
        }
        
        // Vérifier les données du formulaire
        if (empty($_POST['address']) || empty($_POST['city']) || empty($_POST['zip_code']) || empty($_POST['country'])) {
            $_SESSION['error'] = "Veuillez remplir tous les champs obligatoires";
            header('Location: /order/checkout');
            exit;
        }
        
        if (!isset($_POST['terms'])) {
            $_SESSION['error'] = "Vous devez accepter les conditions générales";
            header('Location: /order/checkout');
            exit;
        }
        
        // Vérifier que le panier n'est pas vide
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            $_SESSION['error'] = "Votre panier est vide";
            header('Location: /cart');
            exit;
        }
        
        // Calculer le total
        $total = 0;
        $items = [];
        
        foreach ($cart as $productId => $quantity) {
            $product = Product::findById($productId);
            if ($product) {
                // Vérifier le stock
                if ($product->getStock() < $quantity) {
                    $_SESSION['error'] = "Stock insuffisant pour: " . $product->getName();
                    header('Location: /cart');
                    exit;
                }
                
                $subtotal = $product->getPrice() * $quantity;
                $total += $subtotal;
                $items[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }
        }
        
        // Ajouter les frais de livraison
        $total += 5.00;
        
        // Simuler la création de commande (à adapter avec votre modèle Order)
        $orderId = rand(1000, 9999);
        
        // Enregistrer les informations de commande
        $_SESSION['last_order'] = [
            'id' => $orderId,
            'total' => $total,
            'address' => $_POST['address'],
            'city' => $_POST['city'],
            'zip_code' => $_POST['zip_code'],
            'country' => $_POST['country'],
            'payment_method' => $_POST['payment_method'] ?? 'card',
            'date' => date('d/m/Y H:i')
        ];
        
        // Vider le panier
        unset($_SESSION['cart']);
        
        // Rediriger vers la confirmation
        $_SESSION['success'] = "Commande #$orderId passée avec succès !";
        header('Location: /order/confirm');
        exit;
    }
    
    public function confirm()
    {
        // Vérifier l'authentification
        if (!isset($_SESSION['user'])) {
            header('Location: /user/login');
            exit;
        }
        
        // Vérifier qu'il y a une commande récente
        if (!isset($_SESSION['last_order'])) {
            header('Location: /');
            exit;
        }
        
        $order = $_SESSION['last_order'];
        
        $this->render('order/confirm', [
            'order' => $order
        ]);
    }
}