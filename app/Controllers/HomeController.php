<?php

// Active le mode strict pour la vérification des types
declare(strict_types=1);
// Déclare l'espace de noms pour ce contrôleur
namespace Mini\Controllers;
// Importe la classe de base Controller du noyau
use Mini\Core\Controller;
use Mini\Models\User;
use Mini\Models\Product;
use Mini\Models\Category;

// Déclare la classe finale HomeController qui hérite de Controller
final class HomeController extends Controller
{
    // Déclare la méthode d'action par défaut qui ne retourne rien
    public function index()
    {
        // Récupère les produits
        try {
            $products = Product::getAll();
            
            // DEBUG
            error_log("HomeController: Produits trouvés: " . count($products));
            
            // Passe les produits à la vue
            $this->render('home/index', [
                'products' => $products
            ]);
            
        } catch (\Exception $e) {
            error_log("Erreur HomeController: " . $e->getMessage());
            // En cas d'erreur, passez un tableau vide
            $this->render('home/index', [
                'products' => []
            ]);
        }
    }

    public function catalogue()
    {
        // Récupère tous les produits
        $products = Product::getAll();
        $categories = Category::getAll();
        
        // Gestion de la pagination
        $itemsPerPage = 9;
        $currentPage = $_GET['page'] ?? 1;
        $totalProducts = count($products);
        $totalPages = ceil($totalProducts / $itemsPerPage);
        
        // Pagination des produits
        $startIndex = ($currentPage - 1) * $itemsPerPage;
        $paginatedProducts = array_slice($products, $startIndex, $itemsPerPage);
        
        $this->render('home/catalogue', [
            'products' => $paginatedProducts,
            'categories' => $categories,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages
        ]);
    }

    public function users(): void
    {
        // Récupère tous les utilisateurs
        $users = User::getAll();
        
        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Encode les données en JSON et les affiche
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function showCreateUserForm(): void
    {
        // Affiche le formulaire de création d'utilisateur
        $this->render('home/create-user', params: [
            'title' => 'Créer un utilisateur'
        ]);
    }

    public function createUser(): void
    {
        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');
        
        // Vérifie que la méthode HTTP est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Récupère les données JSON du body de la requête
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Si pas de JSON, essaie de récupérer depuis $_POST
        if ($input === null) {
            $input = $_POST;
        }
        
        // Valide les données requises
        if (empty($input['nom']) || empty($input['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "nom" et "email" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }
        
        // Valide le format de l'email
        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format d\'email invalide.'], JSON_PRETTY_PRINT);
            return;
        }
        
        
        // Crée une nouvelle instance User
        $user = new User();
        $user->setNom($input['nom']);
        $user->setEmail($input['email']);
        
        // Sauvegarde l'utilisateur
        if ($user->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => [
                    'nom' => $user->getnom(),
                    'email' => $user->getEmail()
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de l\'utilisateur.'], JSON_PRETTY_PRINT);
        }
    }
}