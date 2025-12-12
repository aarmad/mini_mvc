<?php
$title = "Inscription - Boutique Chocolat";
ob_start();
?>

<div style="max-width: 400px; margin: 0 auto;">
    <h1 style="text-align: center; margin-bottom: 2rem;">Inscription</h1>
    
    <form method="POST" style="display: flex; flex-direction: column; gap: 1rem;">
        <div>
            <label for="first_name" style="display: block; margin-bottom: 0.5rem;">Prénom:</label>
            <input type="text" id="first_name" name="first_name" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <div>
            <label for="last_name" style="display: block; margin-bottom: 0.5rem;">Nom:</label>
            <input type="text" id="last_name" name="last_name" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <div>
            <label for="email" style="display: block; margin-bottom: 0.5rem;">Email:</label>
            <input type="email" id="email" name="email" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <div>
            <label for="password" style="display: block; margin-bottom: 0.5rem;">Mot de passe:</label>
            <input type="password" id="password" name="password" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <div>
            <label for="confirm_password" style="display: block; margin-bottom: 0.5rem;">Confirmer le mot de passe:</label>
            <input type="password" id="confirm_password" name="confirm_password" required 
                   style="width: 100%; padding: 0.5rem; border: 1px solid #000;">
        </div>
        
        <button type="submit" class="btn" style="width: 100%;">S'inscrire</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Déjà un compte ? <a href="/login" style="color: #000;">Se connecter</a>
    </p>
</div>