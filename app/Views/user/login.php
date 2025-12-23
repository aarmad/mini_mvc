<?php
$title = "Connexion - Boutique Chocolat";
ob_start();
?>

<div style="max-width: 400px; margin: 0 auto;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Connexion</h1>
    
    <?php if (isset($error)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 1rem; border: 1px solid #f5c6cb; margin-bottom: 1rem; border-radius: 4px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 1rem; border: 1px solid #c3e6cb; margin-bottom: 1rem; border-radius: 4px;">
            <?= $_SESSION['success'] ?>
            <?php unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label for="email" style="display: block; margin-bottom: 0.5rem;">Email:</label>
            <input type="email" id="email" name="email" required 
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <div>
            <label for="password" style="display: block; margin-bottom: 0.5rem;">Mot de passe:</label>
            <input type="password" id="password" name="password" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <button type="submit" class="btn" style="width: 100%; background: #000; color: #fff;">Se connecter</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Pas de compte ? <a href="/register" style="color: #000; text-decoration: underline;">S'inscrire</a>
    </p>
</div>