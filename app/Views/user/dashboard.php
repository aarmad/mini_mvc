<?php
$title = "Mon Compte - Boutique Chocolat";
ob_start();

// Récupère l'utilisateur depuis la session (passé par le contrôleur)
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
?>

<h1 style="margin-bottom: 2rem;">Mon Compte</h1>

<?php if ($user): ?>
    <div style="display: grid; gap: 2rem;">
        <section>
            <h2 style="margin-bottom: 1rem;">Mes Informations</h2>
            <div style="border: 1px solid #000; padding: 1.5rem;">
                <p><strong>Nom:</strong> <?= htmlspecialchars($user->getFirstName() . ' ' . $user->getLastName()) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
                <?php if (method_exists($user, 'getRole')): ?>
                    <p><strong>Rôle:</strong> <?= htmlspecialchars($user->getRole()) ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section>
            <h2 style="margin-bottom: 1rem;">Mes Commandes</h2>
            <?php if (empty($orders)): ?>
                <div style="border: 1px solid #000; padding: 1.5rem; text-align: center;">
                    <p>✅ Aucune commande passée pour le moment.</p>
                    <a href="/catalogue" class="btn" style="margin-top: 1rem;">Découvrir nos produits</a>
                </div>
            <?php else: ?>
                <div style="display: grid; gap: 1rem;">
                    <?php foreach ($orders as $order): ?>
                    <div style="border: 1px solid #000; padding: 1.5rem;">
                        <p><strong>Commande #<?= $order->getId() ?></strong></p>
                        <p>Total: <?= number_format($order->getTotal(), 2, ',', ' ') ?> €</p>
                        <p>Statut: <?= $order->getStatus() ?></p>
                        <p>Date: <?= $order->getCreatedAt() ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
        
        <section>
            <h2 style="margin-bottom: 1rem;">Actions</h2>
            <div style="display: flex; gap: 1rem;">
                <a href="/catalogue" class="btn">Continuer mes achats</a>
                <a href="/cart" class="btn">Voir mon panier</a>
                <a href="/logout" class="btn" style="background: #dc3545; color: white;">Déconnexion</a>
            </div>
        </section>
    </div>
<?php else: ?>
    <div style="text-align: center; padding: 3rem;">
        <p>Vous n'êtes pas connecté.</p>
        <a href="/login" class="btn">Se connecter</a>
        <a href="/register" class="btn" style="margin-left: 1rem;">S'inscrire</a>
    </div>
<?php endif; ?>

<?php
?>