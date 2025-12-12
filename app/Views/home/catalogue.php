<?php
// Vue pour la page catalogue
$title = "Catalogue de produits";
ob_start();
?>

<div class="catalogue-header">
    <h1>Notre Catalogue de Chocolats</h1>
    <p>Découvrez notre sélection de tablettes de chocolat de qualité</p>
</div>

<!-- Filtres et recherche -->
<div class="catalogue-filters">
    <div class="filter-group">
        <input type="text" id="search" placeholder="Rechercher un produit..." class="search-input">
    </div>
    
    <?php if (isset($categories) && !empty($categories)): ?>
    <div class="filter-group">
        <select id="category-filter" class="filter-select">
            <option value="">Toutes les catégories</option>
            <?php foreach ($categories as $category): ?>
            <option value="<?= $category->getId() ?>"><?= htmlspecialchars($category->getName()) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php endif; ?>
    
    <div class="filter-group">
        <select id="price-filter" class="filter-select">
            <option value="">Trier par prix</option>
            <option value="asc">Prix croissant</option>
            <option value="desc">Prix décroissant</option>
        </select>
    </div>
</div>

<!-- Liste des produits -->
<div class="products-grid" id="products-container">
    <?php if (empty($products)): ?>
        <div class="no-products">
            <p>Aucun produit disponible pour le moment.</p>
        </div>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
        <div class="product-card" data-category="<?= $product->getCategoryId() ?>" data-price="<?= $product->getPrice() ?>">
            <div class="product-image">
                <?php if ($product->getImage()): ?>
                    <img src="/uploads/<?= htmlspecialchars($product->getImage()) ?>" alt="<?= htmlspecialchars($product->getName()) ?>" loading="lazy">
                <?php else: ?>
                    <div class="product-image-placeholder">
                        <span>🛒</span>
                    </div>
                <?php endif; ?>
                
                <?php if ($product->getStock() <= 0): ?>
                    <div class="out-of-stock-badge">Rupture</div>
                <?php elseif ($product->getStock() <= 5): ?>
                    <div class="low-stock-badge">Stock faible</div>
                <?php endif; ?>
            </div>
            
            <div class="product-info">
                <h3 class="product-name"><?= htmlspecialchars($product->getName()) ?></h3>
                <p class="product-description"><?= htmlspecialchars($product->getDescription()) ?></p>
                
                <div class="product-meta">
                    <span class="product-price"><?= number_format($product->getPrice(), 2, ',', ' ') ?> €</span>
                    <span class="product-stock">
                        <?php if ($product->getStock() > 0): ?>
                            <?= $product->getStock() ?> en stock
                        <?php else: ?>
                            Indisponible
                        <?php endif; ?>
                    </span>
                </div>
                
                <div class="product-actions">
                    <?php if ($product->getStock() > 0): ?>
                        <form action="/cart/add" method="POST" class="add-to-cart-form">
                            <input type="hidden" name="product_id" value="<?= $product->getId() ?>">
                            <button type="submit" class="btn-add-cart">
                                <span>🛒</span>
                                Ajouter au panier
                            </button>
                        </form>
                    <?php else: ?>
                        <button class="btn-disabled" disabled>Rupture de stock</button>
                    <?php endif; ?>
                    
                    <a href="/product/<?= $product->getId() ?>" class="btn-details">Voir détails</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (isset($totalPages) && $totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?= $i ?>" class="page-link <?= $currentPage == $i ? 'active' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<style>
.catalogue-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 2rem 0;
    background: linear-gradient(135deg, #8B4513, #D2691E);
    color: white;
    border-radius: 10px;
}

.catalogue-header h1 {
    margin: 0 0 0.5rem 0;
    font-size: 2.5rem;
}

.catalogue-header p {
    margin: 0;
    opacity: 0.9;
    font-size: 1.1rem;
}

.catalogue-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.filter-group {
    flex: 1;
    min-width: 200px;
}

.search-input, .filter-select {
    width: 100%;
    padding: 0.75rem;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.3s;
}

.search-input:focus, .filter-select:focus {
    outline: none;
    border-color: #8B4513;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-bottom: 2rem;
}

.product-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    overflow: hidden;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.product-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-card:hover .product-image img {
    transform: scale(1.05);
}

.product-image-placeholder {
    width: 100%;
    height: 100%;
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
}

.out-of-stock-badge, .low-stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: bold;
}

.out-of-stock-badge {
    background: #dc3545;
    color: white;
}

.low-stock-badge {
    background: #ffc107;
    color: #212529;
}

.product-info {
    padding: 1.5rem;
}

.product-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
}

.product-description {
    color: #666;
    font-size: 0.9rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #8B4513;
}

.product-stock {
    font-size: 0.85rem;
    color: #666;
}

.product-actions {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.add-to-cart-form {
    margin: 0;
}

.btn-add-cart, .btn-details, .btn-disabled {
    padding: 0.75rem 1rem;
    border: none;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: center;
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-add-cart {
    background: #8B4513;
    color: white;
}

.btn-add-cart:hover {
    background: #654321;
    transform: translateY(-2px);
}

.btn-details {
    background: transparent;
    color: #8B4513;
    border: 2px solid #8B4513;
}

.btn-details:hover {
    background: #8B4513;
    color: white;
}

.btn-disabled {
    background: #6c757d;
    color: white;
    cursor: not-allowed;
}

.no-products {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.no-products p {
    margin: 0;
    color: #666;
    font-size: 1.1rem;
}

.pagination {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 2rem;
}

.page-link {
    padding: 0.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 6px;
    text-decoration: none;
    color: #333;
    transition: all 0.3s ease;
}

.page-link:hover, .page-link.active {
    background: #8B4513;
    color: white;
    border-color: #8B4513;
}

/* Responsive */
@media (max-width: 768px) {
    .catalogue-filters {
        flex-direction: column;
    }
    
    .filter-group {
        min-width: 100%;
    }
    
    .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .product-info {
        padding: 1rem;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .catalogue-header h1 {
        font-size: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const categoryFilter = document.getElementById('category-filter');
    const priceFilter = document.getElementById('price-filter');
    const productsContainer = document.getElementById('products-container');
    const productCards = document.querySelectorAll('.product-card');
    
    function filterProducts() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categoryFilter.value;
        const priceOrder = priceFilter.value;
        
        let visibleProducts = 0;
        
        productCards.forEach(card => {
            const productName = card.querySelector('.product-name').textContent.toLowerCase();
            const productCategory = card.getAttribute('data-category');
            const productPrice = parseFloat(card.getAttribute('data-price'));
            
            const matchesSearch = productName.includes(searchTerm);
            const matchesCategory = !selectedCategory || productCategory === selectedCategory;
            
            let shouldShow = matchesSearch && matchesCategory;
            
            card.style.display = shouldShow ? 'block' : 'none';
            
            if (shouldShow) {
                visibleProducts++;
            }
        });
        
        // Trier par prix si demandé
        if (priceOrder && productsContainer) {
            const sortedCards = Array.from(productCards)
                .filter(card => card.style.display !== 'none')
                .sort((a, b) => {
                    const priceA = parseFloat(a.getAttribute('data-price'));
                    const priceB = parseFloat(b.getAttribute('data-price'));
                    return priceOrder === 'asc' ? priceA - priceB : priceB - priceA;
                });
            
            // Réorganiser les éléments dans le conteneur
            sortedCards.forEach(card => {
                productsContainer.appendChild(card);
            });
        }
        
        // Afficher message si aucun produit
        const noProductsElement = document.querySelector('.no-products');
        if (visibleProducts === 0) {
            if (!noProductsElement) {
                const noProducts = document.createElement('div');
                noProducts.className = 'no-products';
                noProducts.innerHTML = '<p>Aucun produit ne correspond à vos critères de recherche.</p>';
                productsContainer.appendChild(noProducts);
            }
        } else if (noProductsElement) {
            noProductsElement.remove();
        }
    }
    
    // Événements pour les filtres
    searchInput.addEventListener('input', filterProducts);
    categoryFilter.addEventListener('change', filterProducts);
    priceFilter.addEventListener('change', filterProducts);
    
    // Animation d'ajout au panier
    document.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const button = this.querySelector('.btn-add-cart');
            const originalText = button.innerHTML;
            
            button.innerHTML = '✓ Ajouté !';
            button.style.background = '#28a745';
            
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
            }, 2000);
        });
    });
});
</script>