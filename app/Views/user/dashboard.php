<?php
$title = "Mon Compte - Boutique Chocolat";
ob_start();
?>

<h1 style="margin-bottom: 2rem;">Mon Compte</h1>

<div style="display: grid; gap: 2rem;">
    <section>
        <h2 style="margin-bottom: 1rem;">Mes Informations</h2>
        <div style="border: 1px solid #000; padding: 1rem;">
            <p><strong>Nom:</strong> <?= htmlspecialchars($_SESSION['user']->getFirstName() . ' ' . $_SESSION['user']->getLastName()) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['user']->getEmail()) ?></p>
        </div>
    </section>

    <section>
        <h2 style="margin-bottom: 1rem;">Mes Commandes</h2>
        <?php if (empty($orders)): ?>
            <p>Aucune commande passée.</p>
        <?php else: ?>
            <div style="display: grid; gap: 1rem;">
                <?php foreach ($orders as $order): ?>
                <div style="border: 1px solid #000; padding: 1rem;">
                    <p><strong>Commande #<?= $order->getId() ?></strong></p>
                    <p>Total: <?= number_format($order->getTotal(), 2, ',', ' ') ?> €</p>
                    <p>Statut: <?= $order->getStatus() ?></p>
                    <p>Date: <?= $order->getCreatedAt() ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>