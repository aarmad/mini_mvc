<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Boutique Chocolat' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #000;
            background-color: #fff;
        }
        
        .header {
            background: #fff;
            border-bottom: 2px solid #000;
            padding: 1rem 0;
        }
        
        .nav {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 1rem;
        }
        
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #000;
            text-decoration: none;
        }
        
        .nav-links {
            display: flex;
            list-style: none;
            gap: 2rem;
        }
        
        .nav-links a {
            color: #000;
            text-decoration: none;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            text-decoration: underline;
        }
        
        .main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem;
            min-height: 70vh;
        }
        
        .footer {
            background: #fff;
            border-top: 2px solid #000;
            padding: 2rem 0;
            margin-top: 2rem;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            text-align: center;
            color: #000;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #000;
            background: #f8f9fa;
        }
        
        .alert-success {
            border-color: #000;
            background: #f8f9fa;
        }
        
        .alert-error {
            border-color: #000;
            background: #f8f9fa;
        }
        
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: #fff;
            color: #000;
            border: 1px solid #000;
            text-decoration: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: #000;
            color: #fff;
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="nav">
            <a href="/" class="logo">CHOCOLATERIE</a>
            <ul class="nav-links">
                <li><a href="/">Accueil</a></li>
                <li><a href="/catalogue">Catalogue</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="/dashboard">Mon Compte</a></li>
                    <li><a href="/cart">Panier</a></li>
                    <li><a href="/logout">Déconnexion</a></li>
                <?php else: ?>
                    <li><a href="/login">Connexion</a></li>
                    <li><a href="/register">Inscription</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="main">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?= $content ?>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; 2024 Chocolaterie. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>