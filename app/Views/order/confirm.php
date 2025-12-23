<?php
$title = "Confirmation de commande - Boutique Chocolat";
ob_start();

// Récupérer l'utilisateur depuis la session
$user = $_SESSION['user'];

// Fonction helper pour obtenir l'email en fonction du type de l'utilisateur
function getUserEmailFromSession($user) {
    if (is_object($user) && method_exists($user, 'getEmail')) {
        return $user->getEmail();
    } elseif (is_array($user)) {
        return $user['email'] ?? '';
    }
    return '';
}

// Utiliser la fonction
$userEmail = htmlspecialchars(getUserEmailFromSession($user));
?>

<div style="max-width: 800px; margin: 0 auto; text-align: center; padding: 3rem 0;">
    <div style="background: #d4edda; color: #155724; padding: 2rem; border-radius: 8px; margin-bottom: 2rem;">
        <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">✅ Commande confirmée !</h1>
        <p style="font-size: 1.2rem;">
            Merci pour votre commande #<?= $order['id'] ?>
        </p>
    </div>
    
    <div style="border: 1px solid #000; padding: 2rem; border-radius: 8px; text-align: left; margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1.5rem;">Récapitulatif de votre commande</h2>
        
        <div style="display: grid; gap: 1rem;">
            <div>
                <strong>Numéro de commande:</strong> #<?= $order['id'] ?>
            </div>
            <div>
                <strong>Date:</strong> <?= $order['date'] ?>
            </div>
            <div>
                <strong>Total:</strong> <?= number_format($order['total'], 2, ',', ' ') ?> €
            </div>
            <div>
                <strong>Mode de paiement:</strong> 
                <?= $order['payment_method'] == 'card' ? 'Carte bancaire' : 'PayPal' ?>
            </div>
            <div>
                <strong>Adresse de livraison:</strong><br>
                <?= nl2br(htmlspecialchars($order['address'])) ?><br>
                <?= htmlspecialchars($order['zip_code']) ?> <?= htmlspecialchars($order['city']) ?><br>
                <?= htmlspecialchars($order['country']) ?>
            </div>
        </div>
    </div>
    
    <div style="margin-bottom: 2rem;">
        <p style="font-size: 1.1rem; margin-bottom: 1.5rem;">
            Un email de confirmation vous a été envoyé à <?= $userEmail ?>
        </p>
        
        <p style="color: #666; margin-bottom: 2rem;">
            Votre commande sera préparée et expédiée dans les 24-48 heures.
        </p>
    </div>
    
    <div style="display: flex; gap: 1rem; justify-content: center;">
        <a href="/" class="btn" style="background: #000; color: #fff;">
            Retour à l'accueil
        </a>
        <a href="/dashboard" class="btn" style="background: transparent; color: #000; border: 1px solid #000;">
            Voir mes commandes
        </a>
        <a href="/catalogue" class="btn" style="background: #8B4513; color: #fff;">
            Continuer mes achats
        </a>
    </div>
</div>