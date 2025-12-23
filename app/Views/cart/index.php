<?php
$title = "Panier - Boutique Chocolat";
ob_start();
?>

<h1 style="margin-bottom: 2rem;">Mon Panier</h1>

<?php if (empty($products)): ?>
    <div style="text-align: center; padding: 3rem; border: 1px dashed #000; border-radius: 8px;">
        <p style="font-size: 1.2rem; margin-bottom: 1rem;">🛒 Votre panier est vide</p>
        <a href="/catalogue" class="btn" style="background: #000; color: #fff; padding: 0.75rem 1.5rem;">
            Découvrir nos produits
        </a>
    </div>
<?php else: ?>
    <form method="POST" action="/cart/update">
        <div style="display: grid; gap: 1.5rem; margin-bottom: 2rem;">
            <?php foreach ($products as $product): ?>
            <div style="border: 1px solid #000; padding: 1.5rem; border-radius: 8px; background: #fff; display: flex; gap: 1.5rem; align-items: center;">
                
                <!-- Image du produit -->
                <div style="width: 120px; height: 120px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border: 1px solid #e9ecef; border-radius: 4px; overflow: hidden; background: #f8f9fa;">
                    <?php if ($product->getImage()): ?>
                        <img src="/img/<?= htmlspecialchars($product->getImage()) ?>" 
                             alt="<?= htmlspecialchars($product->getName()) ?>"
                             style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <span style="font-size: 2rem; color: #8B4513;">🍫</span>
                    <?php endif; ?>
                </div>
                
                <!-- Informations du produit -->
                <div style="flex-grow: 1;">
                    <h3 style="margin-bottom: 0.5rem;">
                        <a href="/product?id=<?= $product->getId() ?>" 
                           style="color: #000; text-decoration: none; font-size: 1.2rem;">
                            <?= htmlspecialchars($product->getName()) ?>
                        </a>
                    </h3>
                    
                    <p style="color: #666; margin-bottom: 0.5rem;">
                        <?= htmlspecialchars(mb_strlen($product->getDescription()) > 100 ? mb_substr($product->getDescription(), 0, 100) . '...' : $product->getDescription()) ?>
                    </p>
                    
                    <div style="display: flex; gap: 2rem; align-items: center;">
                        <div>
                            <p style="font-weight: bold; font-size: 1.1rem; color: #8B4513;">
                                <?= number_format($product->getPrice(), 2, ',', ' ') ?> €
                                <span style="font-size: 0.9rem; color: #666;">/unité</span>
                            </p>
                        </div>
                        
                        <!-- Quantité -->
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <label for="qty-<?= $product->getId() ?>" style="font-weight: 500;">Quantité:</label>
                            <input type="number" 
                                   id="qty-<?= $product->getId() ?>" 
                                   name="quantities[<?= $product->getId() ?>]" 
                                   value="<?= $product->quantity ?>" 
                                   min="0" 
                                   max="<?= $product->getStock() ?>"
                                   style="width: 70px; padding: 0.5rem; border: 1px solid #000; border-radius: 4px; text-align: center;">
                        </div>
                        
                        <!-- Sous-total -->
                        <div style="margin-left: auto;">
                            <p style="font-weight: bold; font-size: 1.2rem;">
                                <?= number_format($product->subtotal, 2, ',', ' ') ?> €
                            </p>
                            <p style="font-size: 0.85rem; color: #666; text-align: right;">
                                <?= $product->quantity ?> × <?= number_format($product->getPrice(), 2, ',', ' ') ?> €
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <a href="/cart/remove?id=<?= $product->getId() ?>" 
                       class="btn" 
                       style="background: #dc3545; color: white; border: none; padding: 0.5rem 1rem;"
                       onclick="return confirm('Supprimer ce produit du panier ?');">
                        ❌ Supprimer
                    </a>
                    
                    <a href="/product?id=<?= $product->getId() ?>" 
                       class="btn" 
                       style="background: transparent; color: #000; border: 1px solid #000; padding: 0.5rem 1rem;">
                        👁️ Voir
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Récapitulatif -->
        <div style="border-top: 2px solid #000; padding-top: 1.5rem; margin-bottom: 2rem;">
            <div style="max-width: 400px; margin-left: auto;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                    <span>Nombre d'articles:</span>
                    <span style="font-weight: bold;"><?= array_sum(array_column($products, 'quantity')) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: bold; border-top: 1px solid #e9ecef; padding-top: 1rem;">
                    <span>Total:</span>
                    <span style="color: #8B4513; font-size: 1.3rem;">
                        <?= number_format($total, 2, ',', ' ') ?> €
                    </span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <a href="/catalogue" class="btn" style="background: transparent; color: #000; border: 1px solid #000;">
                    ← Continuer mes achats
                </a>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn" style="background: #6c757d; color: white; border: none;">
                    🔄 Mettre à jour le panier
                </button>
                <a href="/order/checkout" class="btn" style="background: #000; color: #fff; padding: 0.75rem 1.5rem;">
                    🚀 Passer la commande
                </a>
            </div>
        </div>
    </form>
<?php endif; ?>

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
        text-align: center;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    input[type="number"] {
        -moz-appearance: textfield;
    }
    
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>