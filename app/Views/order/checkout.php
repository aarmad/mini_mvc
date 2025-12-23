<?php
$title = "Commande - Boutique Chocolat";
ob_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header('Location: /user/login');
    exit;
}

// Récupérer l'utilisateur depuis la session
$user = $_SESSION['user'];

// Fonction pour obtenir le nom complet
function getUserName($user) {
    if (is_object($user)) {
        // Si c'est un objet User
        $firstName = method_exists($user, 'getFirstName') ? $user->getFirstName() : '';
        $lastName = method_exists($user, 'getLastName') ? $user->getLastName() : '';
        return $firstName . ' ' . $lastName;
    } else {
        // Si c'est un tableau
        $firstName = $user['first_name'] ?? $user['firstName'] ?? '';
        $lastName = $user['last_name'] ?? $user['lastName'] ?? '';
        return $firstName . ' ' . $lastName;
    }
}

// Fonction pour obtenir l'email
function getUserEmail($user) {
    if (is_object($user)) {
        // Si c'est un objet User
        return method_exists($user, 'getEmail') ? $user->getEmail() : '';
    } else {
        // Si c'est un tableau
        return $user['email'] ?? '';
    }
}

// Récupérer le panier
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header('Location: /cart');
    exit;
}

// Calculer le total
$total = 0;
$items = [];
foreach ($cart as $productId => $quantity) {
    $product = \Mini\Models\Product::findById($productId);
    if ($product) {
        $subtotal = $product->getPrice() * $quantity;
        $total += $subtotal;
        $items[] = [
            'product' => $product,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}
?>

<h1 style="margin-bottom: 2rem;">Finaliser ma commande</h1>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; max-width: 1200px; margin: 0 auto;">
    <!-- Section gauche : Récapitulatif -->
    <div>
        <h2 style="margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #000;">
            🛒 Récapitulatif du panier
        </h2>
        
        <div style="margin-bottom: 2rem;">
            <?php foreach ($items as $item): ?>
            <div style="border: 1px solid #e9ecef; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; display: flex; gap: 1rem; align-items: center;">
                <!-- Image du produit -->
                <div style="width: 80px; height: 80px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border: 1px solid #e9ecef; border-radius: 4px; overflow: hidden; background: #f8f9fa;">
                    <?php if ($item['product']->getImage()): ?>
                        <img src="/img/<?= htmlspecialchars($item['product']->getImage()) ?>" 
                             alt="<?= htmlspecialchars($item['product']->getName()) ?>"
                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <span style="font-size: 1.5rem; color: #8B4513;">🍫</span>
                    <?php endif; ?>
                </div>
                
                <div style="flex-grow: 1;">
                    <h4 style="margin: 0 0 0.5rem 0;">
                        <?= htmlspecialchars($item['product']->getName()) ?>
                    </h4>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Quantité: <?= $item['quantity'] ?></span>
                        <span style="font-weight: bold;"><?= number_format($item['subtotal'], 2, ',', ' ') ?> €</span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Total -->
        <div style="border-top: 2px solid #000; padding-top: 1rem;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Sous-total:</span>
                <span><?= number_format($total, 2, ',', ' ') ?> €</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>Livraison:</span>
                <span>5,00 €</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; border-top: 1px solid #000; padding-top: 1rem;">
                <span>Total TTC:</span>
                <span style="color: #8B4513;">
                    <?= number_format($total + 5, 2, ',', ' ') ?> €
                </span>
            </div>
        </div>
    </div>
    
    <!-- Section droite : Formulaire de commande -->
    <div>
        <h2 style="margin-bottom: 1.5rem; padding-bottom: 0.5rem; border-bottom: 2px solid #000;">
            📝 Informations de livraison
        </h2>
        
        <form method="POST" action="/order/create" style="display: grid; gap: 1rem;">
            <!-- Informations client -->
            <div style="background: #f8f9fa; padding: 1.5rem; border-radius: 4px; margin-bottom: 1rem;">
                <h3 style="margin-bottom: 1rem;">Client</h3>
                <p><strong>Nom:</strong> <?= htmlspecialchars(getUserName($user)) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars(getUserEmail($user)) ?></p>
            </div>
            
            <!-- Adresse de livraison -->
            <div style="margin-bottom: 1rem;">
                <label for="address" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">
                    📦 Adresse de livraison *
                </label>
                <textarea id="address" name="address" required 
                          style="width: 100%; padding: 0.75rem; border: 1px solid #000; border-radius: 4px; min-height: 100px;"
                          placeholder="Votre adresse complète..."></textarea>
            </div>
            
            <!-- Ville et code postal -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label for="city" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">
                        Ville *
                    </label>
                    <input type="text" id="city" name="city" required 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #000; border-radius: 4px;">
                </div>
                <div>
                    <label for="zip_code" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">
                        Code postal *
                    </label>
                    <input type="text" id="zip_code" name="zip_code" required 
                           style="width: 100%; padding: 0.75rem; border: 1px solid #000; border-radius: 4px;">
                </div>
            </div>
            
            <!-- Pays -->
            <div style="margin-bottom: 1.5rem;">
                <label for="country" style="display: block; margin-bottom: 0.5rem; font-weight: bold;">
                    Pays *
                </label>
                <select id="country" name="country" required 
                        style="width: 100%; padding: 0.75rem; border: 1px solid #000; border-radius: 4px;">
                    <option value="">Sélectionnez un pays</option>
                    <option value="FR">France</option>
                    <option value="BE">Belgique</option>
                    <option value="CH">Suisse</option>
                    <option value="LU">Luxembourg</option>
                </select>
            </div>
            
            <!-- Mode de paiement -->
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 1rem;">💳 Mode de paiement</h3>
                <div style="display: grid; gap: 0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="payment_method" value="card" checked>
                        Carte bancaire
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="radio" name="payment_method" value="paypal">
                        PayPal
                    </label>
                </div>
            </div>
            
            <!-- Conditions générales -->
            <div style="margin-bottom: 2rem;">
                <label style="display: flex; align-items: flex-start; gap: 0.5rem;">
                    <input type="checkbox" name="terms" required>
                    <span>
                        J'accepte les <a href="#" style="color: #000; text-decoration: underline;">conditions générales de vente</a> 
                        et la <a href="#" style="color: #000; text-decoration: underline;">politique de confidentialité</a> *
                    </span>
                </label>
            </div>
            
            <!-- Boutons d'action -->
            <div style="display: flex; gap: 1rem;">
                <a href="/cart" class="btn" style="background: transparent; color: #000; border: 1px solid #000;">
                    ← Retour au panier
                </a>
                <button type="submit" class="btn" style="background: #28a745; color: white; border: none; flex-grow: 1;">
                    ✅ Confirmer la commande
                </button>
            </div>
            
            <p style="font-size: 0.9rem; color: #666; margin-top: 1rem;">
                * Champs obligatoires
            </p>
        </form>
    </div>
</div>

<style>
    .btn {
        display: inline-block;
        padding: 0.75rem 1.5rem;
        text-decoration: none;
        cursor: pointer;
        font-size: 1rem;
        border-radius: 4px;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    input[type="text"],
    input[type="email"],
    textarea,
    select {
        font-family: inherit;
        font-size: 1rem;
    }
    
    input[type="text"]:focus,
    input[type="email"]:focus,
    textarea:focus,
    select:focus {
        outline: none;
        border-color: #8B4513;
        box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.1);
    }
</style>