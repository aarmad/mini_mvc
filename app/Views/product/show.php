<?php
$title = htmlspecialchars($product->getName()) . " - Boutique Chocolat";
ob_start();
?>

<div style="max-width: 1200px; margin: 0 auto; padding: 2rem;">
    <!-- Bouton retour -->
    <a href="/catalogue" style="display: inline-block; margin-bottom: 2rem; color: #000; text-decoration: none;">
        &larr; Retour au catalogue
    </a>
    
    <!-- Détails du produit -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem;">
        <!-- Image du produit -->
        <div>
            <?php if ($product->getImage()): ?>
                <img src="/img/<?= htmlspecialchars($product->getImage()) ?>"
                     alt="<?= htmlspecialchars($product->getName()) ?>"
                     style="width: 100%; border: 1px solid #000;">
            <?php else: ?>
                <div style="width: 100%; height: 400px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                    <span style="font-size: 3rem;">🍫</span>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Informations du produit -->
        <div>
            <h1 style="font-size: 2rem; margin-bottom: 1rem;"><?= htmlspecialchars($product->getName()) ?></h1>
            
            <p style="font-size: 1.5rem; font-weight: bold; color: #8B4513; margin-bottom: 1.5rem;">
                <?= number_format($product->getPrice(), 2, ',', ' ') ?> €
            </p>
            
            <div style="margin-bottom: 2rem;">
                <h3 style="margin-bottom: 0.5rem;">Description</h3>
                <p style="line-height: 1.6;"><?= nl2br(htmlspecialchars($product->getDescription())) ?></p>
            </div>
            
            <!-- Stock -->
            <div style="margin-bottom: 2rem;">
                <p><strong>Référence :</strong> PROD-<?= str_pad($product->getId(), 4, '0', STR_PAD_LEFT) ?></p>
                <p>
                    <strong>Disponibilité :</strong> 
                    <?php if ($product->getStock() > 0): ?>
                        <span style="color: #28a745;">En stock (<?= $product->getStock() ?> unités)</span>
                    <?php else: ?>
                        <span style="color: #dc3545;">Rupture de stock</span>
                    <?php endif; ?>
                </p>
            </div>
            
            <!-- Actions -->
            <div style="margin-top: 2rem;">
                <?php if ($product->getStock() > 0): ?>
                    <?php if (isset($_SESSION['user'])): ?>
                        <form action="/cart/add" method="POST" style="display: flex; gap: 1rem; align-items: center;">
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                            <button type="submit" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem; background: #000; color: #fff;">
                                🛒 Ajouter au panier
                            </button>
                        </form>
                    <?php else: ?>
                        <div style="background: #f8f9fa; padding: 1rem; border: 1px solid #000; border-radius: 4px;">
                            <p style="margin-bottom: 1rem;">Vous devez être connecté pour acheter ce produit.</p>
                            <a href="/login" class="btn" style="background: #000; color: #fff;">
                                Se connecter
                            </a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div style="background: #f8f9fa; padding: 1rem; border: 1px solid #000; border-radius: 4px;">
                        <p style="margin-bottom: 1rem;">Ce produit est actuellement en rupture de stock.</p>
                        <a href="/catalogue" class="btn">Voir d'autres produits</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Produits similaires -->
    <?php if (!empty($relatedProducts)): ?>
    <div style="margin-top: 4rem; padding-top: 2rem; border-top: 1px solid #e9ecef;">
        <h2 style="text-align: center; margin-bottom: 2rem;">Vous aimerez aussi</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
            <div style="border: 1px solid #000; padding: 1rem; text-align: center; border-radius: 4px;">
                <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;">
                    <a href="/mini_mvc/public/product?id=<?= $relatedProduct->getId() ?>" 
                       style="color: #000; text-decoration: none;">
                        <?= htmlspecialchars($relatedProduct->getName()) ?>
                    </a>
                </h3>
                <p style="font-weight: bold; margin-bottom: 1rem;">
                    <?= number_format($relatedProduct->getPrice(), 2, ',', ' ') ?> €
                </p>
                <a href="/product?id=<?= $relatedProduct->getId() ?>" class="btn">
                    Voir le produit
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
