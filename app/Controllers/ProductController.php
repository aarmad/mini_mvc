<?php

// Active le mode strict pour la vérification des types
declare(strict_types=1);
// Déclare l'espace de noms pour ce contrôleur
namespace Mini\Controllers;
// Importe la classe de base Controller du noyau
use Mini\Core\Controller;
use Mini\Models\Product;

// Déclare la classe finale ProductController qui hérite de Controller
final class ProductController extends Controller
{
    /**
     * Affiche les détails d'un produit
     * Utilise le paramètre GET 'id'
     */
    public function show()
    {
        // Récupère l'ID depuis GET
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        
        if ($id <= 0) {
            $_SESSION['error'] = "Produit non spécifié";
            header('Location: /catalogue');
            exit;
        }
        
        $product = Product::findById($id);
        
        if (!$product) {
            $_SESSION['error'] = "Produit non trouvé";
            header('Location: /catalogue');
            exit;
        }
        
        // Récupère quelques produits similaires (autres produits)
        $allProducts = Product::getAll();
        $relatedProducts = [];
        $count = 0;
        
        foreach ($allProducts as $p) {
            if ($p->getId() != $id && $count < 3) {
                $relatedProducts[] = $p;
                $count++;
            }
        }
        
        $this->render('product/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
/*
    public function listProducts(): void
    {
        // Récupère tous les produits
        $products = Product::getAll();
        
        // Affiche la liste des produits
        $this->render('product/list-products', params: [
            'title' => 'Liste des produits',
            'products' => $products
        ]);
    }

    public function showCreateProductForm(): void
    {
        // Affiche le formulaire de création de produit
        $this->render('product/create-product', params: [
            'title' => 'Créer un produit'
        ]);
    }

    public function createProduct(): void
    {
        // Vérifie que la méthode HTTP est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products/create');
            return;
        }
        
        // Récupère les données depuis $_POST
        $input = $_POST;
        
        // Valide les données requises
        if (empty($input['nom']) || empty($input['prix']) || empty($input['stock'])) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Les champs "nom", "prix" et "stock" sont requis.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }
        
        // Valide le prix (doit être un nombre positif)
        if (!is_numeric($input['prix']) || floatval($input['prix']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le prix doit être un nombre positif.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }
        
        // Valide le stock (doit être un entier positif)
        if (!is_numeric($input['stock']) || intval($input['stock']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le stock doit être un entier positif.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }
        
        // Valide l'URL de l'image si fournie
        $image_url = $input['image_url'] ?? '';
        if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'L\'URL de l\'image n\'est pas valide.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }
        
        // Crée une nouvelle instance Product
        $product = new Product();
        $product->setNom($input['nom']);
        $product->setDescription($input['description'] ?? '');
        $product->setPrix(floatval($input['prix']));
        $product->setStock(intval($input['stock']));
        $product->setImageUrl($image_url);
        
        // Sauvegarde le produit
        if ($product->save()) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Produit créé avec succès.',
                'success' => true
            ]);
        } else {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Erreur lors de la création du produit.',
                'success' => false,
                'old_values' => $input
            ]);
        }
    }

     /**
     * Affiche les détails d'un produit
     * @param int $id ID du produit
    public function show($id)
    {
        $product = Product::findById($id);
        
        if (!$product) {
            $_SESSION['error'] = "Produit non trouvé";
            header('Location: /catalogue');
            exit;
        }
        
        // Pourrait récupérer des produits similaires
        $relatedProducts = $this->getRelatedProducts($product);
        
        $this->render('product/show', [
            'product' => $product,
            'relatedProducts' => $relatedProducts
        ]);
    }
    
    /**
     * Récupère des produits similaires
     * @param Product $product
     * @return array
    private function getRelatedProducts($product)
    {
        // Pour l'instant, retourne d'autres produits de la même catégorie
        // ou les derniers produits si pas de catégorie
        try {
            if ($product->getCategoryId()) {
                return Product::getByCategory($product->getCategoryId());
            }
            
            // Sinon, retourne les derniers produits (sauf celui-ci)
            $allProducts = Product::getAll();
            $related = [];
            $count = 0;
            
            foreach ($allProducts as $p) {
                if ($p->getId() != $product->getId() && $count < 3) {
                    $related[] = $p;
                    $count++;
                }
            }
            
            return $related;
            
        } catch (\Exception $e) {
            return [];
        }
    }
    */
}

