<?php
$title = "Accueil - Boutique Chocolat";
ob_start();
?>

<div style="text-align: center; padding: 3rem 0;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Bienvenue dans notre chocolaterie</h1>
    <p style="font-size: 1.2rem; margin-bottom: 2rem;">Découvrez nos tablettes de chocolat d'exception</p>
    <a href="/catalogue" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem;">Voir le catalogue</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
    <?php foreach ($products as $product): ?>
    <div style="border: 1px solid #000; padding: 1.5rem; text-align: center;">
        <h3><?= htmlspecialchars($product->getName()) ?></h3>
        <p><?= htmlspecialchars($product->getDescription()) ?></p>
        <p style="font-weight: bold; font-size: 1.2rem;"><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</p>
        <?php if ($product->getStock() > 0): ?>
            <form action="/cart/add" method="POST" style="margin-top: 1rem;">
                <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                <button type="submit" class="btn">Ajouter au panier</button>
            </form>
        <?php else: ?>
            <p style="color: #666;">Rupture de stock</p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>
</div>
