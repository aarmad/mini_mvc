## Création DB mini_mvc

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS mini_mvc;
USE mini_mvc;

-- Table des catégories
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des produits
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    stock INT DEFAULT 0,
    category_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    address TEXT,
    city VARCHAR(100),
    zip_code VARCHAR(20),
    country VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des commandes
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'paid', 'shipped', 'delivered') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Table des éléments de commande
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Table du panier (session)
CREATE TABLE cart_sessions (
    id VARCHAR(255) PRIMARY KEY,
    data TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insertion des données de base
INSERT INTO categories (name, description) VALUES 
('Chocolats', 'Tablettes de chocolat de qualité');

INSERT INTO products (name, description, price, stock, category_id) VALUES 
('Tablette de chocolat au lait', 'Chocolat au lait crémeux 100g', 3.50, 50, 1),
('Tablette de chocolat noir', 'Chocolat noir 70% cacao 100g', 4.20, 30, 1);


## 🛠️ Installation

### 1. Prérequis

- PHP 7.4 ou supérieur
- MySQL 5.7 ou supérieur
- Apache avec mod_rewrite activé
- Composer (pour l'autoloading)

### 2. Configuration de la base de données

```bash
# 1. Importer le script SQL
mysql -u root -p < database.sql

# 2. OU via phpMyAdmin :
#    - Créer une base de données "mini_mvc"
#    - Importer le fichier database.sql
```

### 3. Configuration de config.ini (comme vous voulez)

DB_NAME = "mini_mvc"
DB_HOST = "localhost"
DB_USERNAME = "root"
DB_PASSWORD = ""

### 4. Installation des dépendances

#### Installer Composer si nécessaire
composer install

#### OU pour l'autoloading manuel
composer dump-autoload