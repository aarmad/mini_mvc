<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

class UserController extends Controller
{
    public function login()
    {
        if ($_POST) {
            $user = User::findByEmail($_POST['email']);
            
            if ($user && $user->verifyPassword($_POST['password'])) {
                $_SESSION['user'] = $user;
                $_SESSION['success'] = "Connexion réussie !";
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect";
            }
        }
        
        $this->render('user/login', ['error' => $error ?? null]);
    }
    
    public function register()
    {
        if ($_POST) {
            if ($_POST['password'] !== $_POST['confirm_password']) {
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
                        $_SESSION['success'] = "Inscription réussie ! Connectez-vous.";
                        header('Location: /login');
                        exit;
                    } else {
                        $error = "Erreur lors de l'inscription";
                    }
                }
            }
        }
        
        $this->render('user/register', ['error' => $error ?? null]);
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
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            header('Location: /mini_mvc/public/user/login');
            exit;
        }
    }
    
    public function dashboard()
    {
        $this->checkAuth();
        
        $this->render('user/dashboard', ['orders' => $orders]);
    }
}