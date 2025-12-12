<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Order
{
    private $id;
    private $user_id;
    private $total;
    private $status;
    private $created_at;

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function getUserId() { return $this->user_id; }
    public function getTotal() { return $this->total; }
    public function getStatus() { return $this->status; }
    public function getCreatedAt() { return $this->created_at; }

    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setTotal($total) { $this->total = $total; }
    public function setStatus($status) { $this->status = $status; }

    // =====================
    // Méthodes CRUD
    // =====================

    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public static function getUserOrders($userId)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT o.*, COUNT(oi.id) as items_count 
                              FROM orders o 
                              LEFT JOIN order_items oi ON o.id = oi.order_id 
                              WHERE o.user_id = ? 
                              GROUP BY o.id 
                              ORDER BY o.created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public function save()
    {
        $pdo = Database::getPDO();
        
        if ($this->id) {
            // Update
            $stmt = $pdo->prepare("UPDATE orders SET user_id = ?, total = ?, status = ? WHERE id = ?");
            return $stmt->execute([
                $this->user_id,
                $this->total,
                $this->status,
                $this->id
            ]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, ?)");
            $result = $stmt->execute([
                $this->user_id,
                $this->total,
                $this->status ?? 'pending'
            ]);
            
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public function createOrder($userId, $items, $total)
    {
        $pdo = Database::getPDO();
        $pdo->beginTransaction();
        
        try {
            // Créer la commande
            $this->setUserId($userId);
            $this->setTotal($total);
            $this->setStatus('pending');
            
            if (!$this->save()) {
                throw new \Exception("Erreur lors de la création de la commande");
            }
            
            // Ajouter les éléments de commande
            foreach ($items as $item) {
                $orderItem = new OrderItem();
                $orderItem->setOrderId($this->id);
                $orderItem->setProductId($item['product_id']);
                $orderItem->setQuantity($item['quantity']);
                $orderItem->setPrice($item['price']);
                
                if (!$orderItem->save()) {
                    throw new \Exception("Erreur lors de l'ajout d'un produit à la commande");
                }
                
                // Mettre à jour le stock
                $product = Product::findById($item['product_id']);
                if (!$product->updateStock($item['quantity'])) {
                    throw new \Exception("Stock insuffisant pour: " . $product->getName());
                }
            }
            
            $pdo->commit();
            return $this->id;
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
    }

    public function getItems()
    {
        return OrderItem::getByOrder($this->id);
    }
}