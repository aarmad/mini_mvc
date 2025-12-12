<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Category
{
    private $id;
    private $name;
    private $description;
    private $created_at;

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getCreatedAt() { return $this->created_at; }

    public function setName($name) { $this->name = $name; }
    public function setDescription($description) { $this->description = $description; }

    // =====================
    // Méthodes CRUD
    // =====================

    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    public function save()
    {
        $pdo = Database::getPDO();
        
        if ($this->id) {
            // Update
            $stmt = $pdo->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
            return $stmt->execute([
                $this->name,
                $this->description,
                $this->id
            ]);
        } else {
            // Insert
            $stmt = $pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
            $result = $stmt->execute([
                $this->name,
                $this->description
            ]);
            
            if ($result) {
                $this->id = $pdo->lastInsertId();
            }
            return $result;
        }
    }

    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    public function getProducts()
    {
        return Product::getByCategory($this->id);
    }
}