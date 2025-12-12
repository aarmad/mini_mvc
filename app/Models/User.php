<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class User
{
    private $id;
    private $email;
    private $password;
    private $first_name;
    private $last_name;
    private $address;
    private $city;
    private $zip_code;
    private $country;
    private $phone;
    private $role;
    private $created_at;

    // =====================
    // Getters / Setters
    // =====================

    public function getId() { return $this->id; }
    public function getEmail() { return $this->email; }
    public function getPassword() { return $this->password; }
    public function getFirstName() { return $this->first_name; }
    public function getLastName() { return $this->last_name; }
    public function getAddress() { return $this->address; }
    public function getCity() { return $this->city; }
    public function getZipCode() { return $this->zip_code; }
    public function getCountry() { return $this->country; }
    public function getPhone() { return $this->phone; }
    public function getRole() { return $this->role; }
    public function getCreatedAt() { return $this->created_at; }

    public function setEmail($email) { $this->email = $email; }
    public function setPassword($password) { $this->password = password_hash($password, PASSWORD_DEFAULT); }
    public function setFirstName($first_name) { $this->first_name = $first_name; }
    public function setLastName($last_name) { $this->last_name = $last_name; }
    public function setAddress($address) { $this->address = $address; }
    public function setCity($city) { $this->city = $city; }
    public function setZipCode($zip_code) { $this->zip_code = $zip_code; }
    public function setCountry($country) { $this->country = $country; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setRole($role) { $this->role = $role; }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public static function getAll()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_CLASS, self::class);
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $id
     * @return User|null
     */
    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return User|null
     */
    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, self::class);
        return $stmt->fetch();
    }

    /**
     * Crée un nouvel utilisateur
     * @return bool
     */
    public function save()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("INSERT INTO users (email, password, first_name, last_name, address, city, zip_code, country, phone, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        return $stmt->execute([
            $this->email,
            $this->password,
            $this->first_name,
            $this->last_name,
            $this->address,
            $this->city,
            $this->zip_code,
            $this->country,
            $this->phone,
            $this->role ?? 'user'
        ]);
    }

    /**
     * Met à jour les informations d'un utilisateur existant
     * @return bool
     */
    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE users SET email = ?, first_name = ?, last_name = ?, address = ?, city = ?, zip_code = ?, country = ?, phone = ?, role = ? WHERE id = ?");
        
        return $stmt->execute([
            $this->email,
            $this->first_name,
            $this->last_name,
            $this->address,
            $this->city,
            $this->zip_code,
            $this->country,
            $this->phone,
            $this->role,
            $this->id
        ]);
    }

    /**
     * Met à jour le mot de passe
     * @return bool
     */
    public function updatePassword($newPassword)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $stmt->execute([$hashedPassword, $this->id]);
    }

    /**
     * Supprime un utilisateur
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }

    /**
     * Vérifie le mot de passe
     * @param string $password
     * @return bool
     */
    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    /**
     * Enregistre un nouvel utilisateur
     * @param array $data
     * @return User|false
     */
    public static function register($data)
    {
        $user = new self();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        
        if ($user->save()) {
            return $user;
        }
        return false;
    }
}