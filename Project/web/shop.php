<?php
require_once 'includes/auth.php';
require_once 'includes/products-data.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Sorting
$sort = $_GET['sort'] ?? 'default';
$sorted = $products;
if ($sort === 'price-asc')  uasort($sorted, fn($a,$b) => $a['price'] <=> $b['price']);
if ($sort === 'price-desc') uasort($sorted, fn($a,$b) => $b['price'] <=> $a['price']);
if ($sort === 'newest')     $sorted = array_filter($sorted, fn($p) => $p['isNew']) + array_filter($sorted, fn($p) => !$p['isNew']);

$wishlist = isLoggedIn() ? getUserWishlist() : [];

$pageTitle       = 'Shop All Necklaces';
$metaDescription = 'Browse Aurielle\'s full necklace collection. Handcrafted fine necklaces for every style and occasion.';
require_once 'includes/header.php';
?>

<main>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span> Shop
    </nav>

    <div class="shop-page">
        <div class="shop-header">
            <div>
                <h1>All Necklaces</h1>
                <p class="shop-count"><?= count($sorted) ?> pieces</p>
            </div>
            <div class="shop-controls">
                <form method="GET" id="sortForm">
                    <select name="sort" class="sort-select" id="sortSelect" onchange="this.form.submit()" aria-label="Sort products">
                        <option value="default"    <?= $sort==='default'    ? 'selected':'' ?>>Sort: Featured</option>
                        <option value="price-asc"  <?= $sort==='price-asc' ? 'selected':'' ?>>Price: Low to High</option>
                        <option value="price-desc" <?= $sort==='price-desc'? 'selected':'' ?>>Price: High to Low</option>
                        <option value="newest"     <?= $sort==='newest'    ? 'selected':'' ?>>Newest First</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="products-grid" id="productsGrid">
            <?php foreach ($sorted as $id => $p): ?>
            <article class="product-card" id="shop-card-<?= $id ?>">
                <div class="product-badges">
                    <?php if ($p['isNew']): ?><span class="badge-tag new">New</span><?php endif; ?>
                    <?php if ($p['isBestseller']): ?><span class="badge-tag bestseller">Bestseller</span><?php endif; ?>
                </div>
                <a href="product.php?id=<?= $id ?>" class="product-image-wrap" tabindex="-1">
                    <?= renderProductImage($p) ?>
                </a>
                <button class="wishlist-btn <?= in_array($id, $wishlist) ? 'active' : '' ?>"
                        data-product-id="<?= $id ?>" aria-label="Toggle wishlist for <?= htmlspecialchars($p['name']) ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                    </svg>
                </button>
                <div class="product-body">
                    <p class="product-cat">Necklace</p>
                    <h3 class="product-name"><a href="product.php?id=<?= $id ?>"><?= htmlspecialchars($p['name']) ?></a></h3>
                    <div class="product-rating"><?= renderStars($p['rating']) ?></div>
                    <p class="product-desc"><?= htmlspecialchars($p['description']) ?></p>
                    <div class="product-footer-row">
                        <span class="product-price"><?= formatCurrency($p['price'] * USD_TO_PHP) ?></span>
                        <button class="btn btn-primary btn-sm add-to-bag-btn" data-product-id="<?= $id ?>" id="shop-add-<?= $id ?>">Add to Bag</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
