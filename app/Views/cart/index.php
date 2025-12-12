<?php
$title = "Panier - Boutique Chocolat";
ob_start();
?>

<h1 style="margin-bottom: 2rem;">Mon Panier</h1>

<?php if (empty($products)): ?>
    <p>Votre panier est vide.</p>
    <a href="/catalogue" class="btn">Continuer mes achats</a>
<?php else: ?>
    <form method="POST" action="/cart/update">
        <div style="display: grid; gap: 1rem; margin-bottom: 2rem;">
            <?php foreach ($products as $product): ?>
            <div style="border: 1px solid #000; padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3><?= htmlspecialchars($product->getName()) ?></h3>
                    <p>Prix unitaire: <?= number_format($product->getPrice(), 2, ',', ' ') ?> €</p>
                </div>
                
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <input type="number" name="quantities[<?= $product->getId() ?>]" 
                           value="<?= $product->quantity ?>" min="0" 
                           style="width: 60px; padding: 0.5rem; border: 1px solid #000;">
                    
                    <p style="font-weight: bold;">
                        <?= number_format($product->subtotal, 2, ',', ' ') ?> €
                    </p>
                    
                    <a href="/cart/remove/<?= $product->getId() ?>" class="btn" 
                       style="background: #fff; color: #000; border: 1px solid #000;">
                        Supprimer
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="border-top: 2px solid #000; padding-top: 1rem; margin-bottom: 2rem;">
            <h2 style="text-align: right;">Total: <?= number_format($total, 2, ',', ' ') ?> €</h2>
        </div>

        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
            <button type="submit" class="btn">Mettre à jour le panier</button>
            <a href="/checkout" class="btn" style="background: #000; color: #fff;">Commander</a>
        </div>
    </form>
<?php endif; ?>

<?php
?>