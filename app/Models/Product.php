<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;
use PDOException;

class Product
{
    private $id;
    private $name;
    private $description;
    private $price;
    private $image;
    private $stock;
    private $category_id;
    private $created_at;

    // =====================
    // Getters
    // =====================

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getPrice() { return $this->price; }
    public function getImage() { return $this->image; }
    public function getStock() { return $this->stock; }
    public function getCategoryId() { return $this->category_id; }
    public function getCreatedAt() { return $this->created_at; }

    // =====================
    // Setters
    // =====================

    public function setName($name) { $this->name = $name; }
    public function setDescription($description) { $this->description = $description; }
    public function setPrice($price) { $this->price = $price; }
    public function setImage($image) { $this->image = $image; }
    public function setStock($stock) { $this->stock = $stock; }
    public function setCategoryId($category_id) { $this->category_id = $category_id; }

    // =====================
    // Méthodes CRUD avec base de données
    // =====================

    /**
     * Récupère tous les produits depuis la base de données
     * @return Product[]
     */
    public static function getAll()
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "SELECT * FROM products ORDER BY created_at DESC";
            $stmt = $pdo->query($sql);
            
            // Récupère tous les résultats sous forme d'objets Product
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new self();
                self::hydrate($product, $row);
                $products[] = $product;
            }
            
            return $products;
            
        } catch (PDOException $e) {
            error_log("Erreur Product::getAll(): " . $e->getMessage());
            
            // En cas d'erreur, retourne un tableau vide
            // ou lancez l'exception si vous préférez
            return [];
        }
    }

    /**
     * Récupère un produit par son ID
     * @param int $id
     * @return Product|null
     */
    public static function findById($id)
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "SELECT * FROM products WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($row) {
                $product = new self();
                self::hydrate($product, $row);
                return $product;
            }
            
            return null;
            
        } catch (PDOException $e) {
            error_log("Erreur Product::findById($id): " . $e->getMessage());
            return null;
        }
    }

    /**
     * Récupère les produits par catégorie
     * @param int $categoryId
     * @return Product[]
     */
    public static function getByCategory($categoryId)
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY name";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$categoryId]);
            
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new self();
                self::hydrate($product, $row);
                $products[] = $product;
            }
            
            return $products;
            
        } catch (PDOException $e) {
            error_log("Erreur Product::getByCategory($categoryId): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Crée ou met à jour un produit
     * @return bool
     */
    public function save()
    {
        try {
            $pdo = Database::getPDO();
            
            if ($this->id) {
                // Mise à jour
                $sql = "UPDATE products SET 
                        name = :name,
                        description = :description,
                        price = :price,
                        image = :image,
                        stock = :stock,
                        category_id = :category_id
                        WHERE id = :id";
                
                $stmt = $pdo->prepare($sql);
                return $stmt->execute([
                    ':name' => $this->name,
                    ':description' => $this->description,
                    ':price' => $this->price,
                    ':image' => $this->image,
                    ':stock' => $this->stock,
                    ':category_id' => $this->category_id,
                    ':id' => $this->id
                ]);
                
            } else {
                // Insertion
                $sql = "INSERT INTO products (name, description, price, image, stock, category_id) 
                        VALUES (:name, :description, :price, :image, :stock, :category_id)";
                
                $stmt = $pdo->prepare($sql);
                $result = $stmt->execute([
                    ':name' => $this->name,
                    ':description' => $this->description,
                    ':price' => $this->price,
                    ':image' => $this->image,
                    ':stock' => $this->stock,
                    ':category_id' => $this->category_id
                ]);
                
                if ($result) {
                    $this->id = $pdo->lastInsertId();
                }
                
                return $result;
            }
            
        } catch (PDOException $e) {
            error_log("Erreur Product::save(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime un produit
     * @return bool
     */
    public function delete()
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$this->id]);
            
        } catch (PDOException $e) {
            error_log("Erreur Product::delete(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour le stock
     * @param int $quantity
     * @return bool
     */
    public function updateStock($quantity)
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?";
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([$quantity, $this->id, $quantity]);
            
        } catch (PDOException $e) {
            error_log("Erreur Product::updateStock(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifie si le produit est disponible
     * @param int $quantity
     * @return bool
     */
    public function isAvailable($quantity = 1)
    {
        return $this->stock >= $quantity;
    }

    /**
     * Récupère les produits avec un stock faible
     * @param int $threshold Seuil de stock faible (défaut: 5)
     * @return Product[]
     */
    public static function getLowStock($threshold = 5)
    {
        try {
            $pdo = Database::getPDO();
            
            $sql = "SELECT * FROM products WHERE stock > 0 AND stock <= ? ORDER BY stock ASC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$threshold]);
            
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $product = new self();
                self::hydrate($product, $row);
                $products[] = $product;
            }
            
            return $products;
            
        } catch (PDOException $e) {
            error_log("Erreur Product::getLowStock(): " . $e->getMessage());
            return [];
        }
    }

    // =====================
    // Méthodes privées
    // =====================

    /**
     * Hydrate un objet Product avec les données d'un tableau
     * @param Product $product
     * @param array $data
     */
    private static function hydrate(Product $product, array $data)
    {
        $product->id = $data['id'] ?? null;
        $product->name = $data['name'] ?? '';
        $product->description = $data['description'] ?? '';
        $product->price = $data['price'] ?? 0;
        $product->image = $data['image'] ?? '';
        $product->stock = $data['stock'] ?? 0;
        $product->category_id = $data['category_id'] ?? null;
        $product->created_at = $data['created_at'] ?? '';
    }
}