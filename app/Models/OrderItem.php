<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class OrderItem
{
    private $id;
    private $order_id;
    private $product_id;
    private $quantity;
    private $price;

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function getOrderId() { return $this->order_id; }
    public function getProductId() { return $this->product_id; }
    public function getQuantity() { return $this->quantity; }
    public function getPrice() { return $this->price; }

    public function setOrderId($order_id) { $this->order_id = $order_id; }
    public function setProductId($product_id) { $this->product_id = $product_id; }
    public function setQuantity($quantity) { $this->quantity = $quantity; }
    public function setPrice($price) { $this->price = $price; }

    // =====================
    // Méthodes CRUD
    // =====================

    public static function getByOrder($orderId)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT oi.*, p.name, p.image 
                              FROM order_items oi 
                              JOIN products p ON oi.product_id = p.id 
                              WHERE oi.order_id = ?");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public function save()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $this->order_id,
            $this->product_id,
            $this->quantity,
            $this->price
        ]);
    }

    public function getProduct()
    {
        return Product::findById($this->product_id);
    }
}