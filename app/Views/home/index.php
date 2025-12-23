<?php
$title = "Accueil - Boutique Chocolat";
ob_start();
?>

<div style="text-align: center; padding: 3rem 0;">
    <h1 style="font-size: 2.5rem; margin-bottom: 1rem;">Bienvenue dans notre chocolaterie</h1>
    <p style="font-size: 1.2rem; margin-bottom: 2rem;">Découvrez nos tablettes de chocolat d'exception</p>
    <a href="/catalogue" class="btn" style="padding: 1rem 2rem; font-size: 1.1rem; background: #000; color: #fff;">Voir le catalogue</a>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
    <?php foreach ($products as $product): ?>
    <div style="border: 1px solid #000; padding: 1.5rem; text-align: center; background: #fff; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        
        <!-- Image du produit -->
        <div style="height: 200px; margin-bottom: 1rem; overflow: hidden; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #e9ecef; padding-bottom: 1rem;">
            <?php if ($product->getImage()): ?>
                <img src="/img/<?= htmlspecialchars($product->getImage()) ?>" 
                     alt="<?= htmlspecialchars($product->getName()) ?>"
                     style="max-width: 100%; max-height: 180px; object-fit: contain; transition: transform 0.3s ease;">
            <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #f8f9fa; border-radius: 4px;">
                    <span style="font-size: 3rem; color: #8B4513; margin-bottom: 0.5rem;">🍫</span>
                    <p style="font-size: 0.9rem; color: #666;">Image non disponible</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Nom du produit -->
        <h3 style="margin-bottom: 0.5rem; font-size: 1.2rem;">
            <a href="/product?id=<?= $product->getId() ?>" 
               style="color: #000; text-decoration: none; font-weight: 600;">
                <?= htmlspecialchars($product->getName()) ?>
            </a>
        </h3>
        
        <!-- Description (tronquée) -->
        <p style="color: #666; font-size: 0.9rem; margin-bottom: 1rem; height: 40px; overflow: hidden; line-height: 1.4;">
            <?= htmlspecialchars(mb_strlen($product->getDescription()) > 80 ? mb_substr($product->getDescription(), 0, 80) . '...' : $product->getDescription()) ?>
        </p>
        
        <!-- Prix -->
        <p style="font-weight: bold; font-size: 1.3rem; color: #8B4513; margin-bottom: 1rem;">
            <?= number_format($product->getPrice(), 2, ',', ' ') ?> €
        </p>
        
        <!-- Stock -->
        <div style="margin-bottom: 1rem;">
            <?php if ($product->getStock() > 5): ?>
                <span style="background: #28a745; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">
                    ✅ En stock
                </span>
            <?php elseif ($product->getStock() > 0): ?>
                <span style="background: #ffc107; color: #212529; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">
                    ⚠️ Stock faible (<?= $product->getStock() ?>)
                </span>
            <?php else: ?>
                <span style="background: #dc3545; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">
                    ❌ Rupture
                </span>
            <?php endif; ?>
        </div>
        
        <!-- Actions -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <?php if ($product->getStock() > 0): ?>
                <?php if (isset($_SESSION['user'])): ?>
                    <form action="/cart/add" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                        <button type="submit" class="btn" style="background: #000; color: #fff; width: 100%;">
                            🛒 Ajouter au panier
                        </button>
                    </form>
                <?php else: ?>
                    <button class="btn" disabled style="background: #6c757d; color: #fff; width: 100%; cursor: not-allowed;">
                        🔒 Connectez-vous pour acheter
                    </button>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn" disabled style="background: #6c757d; color: #fff; width: 100%; cursor: not-allowed;">
                    Rupture de stock
                </button>
            <?php endif; ?>
            
            <a href="/product?id=<?= $product->getId() ?>" 
               class="btn" 
               style="border: 1px solid #000; background: transparent; color: #000; width: 100%;">
                👁️ Voir les détails
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
    .btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: #fff;
        color: #000;
        border: 1px solid #000;
        text-decoration: none;
        cursor: pointer;
        font-size: 0.9rem;
        border-radius: 4px;
        transition: all 0.3s ease;
    }
    
    .btn:hover:not(:disabled) {
        background: #000;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    /* Animation au survol de l'image */
    div:hover img {
        transform: scale(1.05);
    }
</style>