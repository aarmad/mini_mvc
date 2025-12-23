<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

class UserController extends Controller
{
    public function login()
    {
        if (isset($_SESSION['user'])) {
            error_log("Déjà connecté, redirection vers dashboard");
            header('Location: /dashboard');
            exit;
        }
        
        $error = null;
        
        if ($_POST) {
            error_log("Tentative de connexion pour: " . $_POST['email']);
            
            $user = User::findByEmail($_POST['email']);
            
            if ($user && $user->verifyPassword($_POST['password'])) {
                error_log("Connexion réussie pour: " . $_POST['email']);
                
                $_SESSION['user'] = $user;
                $_SESSION['user_id'] = $user->getId();
                
                error_log("User stocké en session: " . get_class($user));
                error_log("Session ID: " . session_id());
                error_log("Session data après login: " . print_r($_SESSION, true));
                
                $_SESSION['success'] = "Connexion réussie ! Bienvenue " . $user->getFirstName();
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
                error_log("Échec connexion pour: " . $_POST['email']);
            }
        }
        
        $this->render('user/login', ['error' => $error]);
    }
    
    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
            exit;
        }
        
        $error = null;
        
        if ($_POST) {
            if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['email']) || empty($_POST['password'])) {
                $error = "Tous les champs sont obligatoires";
            } elseif ($_POST['password'] !== $_POST['confirm_password']) {
                $error = "Les mots de passe ne correspondent pas";
            } else {
                $existingUser = User::findByEmail($_POST['email']);
                if ($existingUser) {
                    $error = "Cet email est déjà utilisé";
                } else {
                    $user = User::register([
                        'email' => $_POST['email'],
                        'password' => $_POST['password'],
                        'first_name' => $_POST['first_name'],
                        'last_name' => $_POST['last_name']
                    ]);
                    
                    if ($user) {
                        $_SESSION['user'] = $user;
                        $_SESSION['success'] = "Inscription réussie ! Bienvenue " . $user->getFirstName();
                        header('Location: /dashboard');
                        exit;
                    } else {
                        $error = "Erreur lors de l'inscription";
                    }
                }
            }
        }
        
        $this->render('user/register', ['error' => $error]);
    }
    
    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * Redirige vers la page de connexion si non connecté
     */
     private function checkAuth()
        {
            $currentUrl = $_SERVER['REQUEST_URL'] ?? '';
            
            if (!isset($_SESSION['user'])) {
                if (!str_contains($currentUrl, '/user/login')) {
                    $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
                    header('Location: /login');
                    exit;
                }
            }
        }
    
    public function dashboard()
    {   
        $this->render('user/dashboard', ['orders' => []]);
    }
}