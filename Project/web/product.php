<?php
require_once 'includes/auth.php';
require_once 'includes/products-data.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$id      = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = $products[$id] ?? null;

if (!$product) {
    header('Location: shop.php');
    exit;
}

$wishlist = isLoggedIn() ? getUserWishlist() : [];
$related  = array_filter($products, fn($p, $k) => $k !== $id, ARRAY_FILTER_USE_BOTH);
$related  = array_slice($related, 0, 4, true);

$pageTitle       = htmlspecialchars($product['name']);
$metaDescription = htmlspecialchars($product['description']);
require_once 'includes/header.php';
?>

<main>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span>
        <a href="shop.php">Shop</a><span>›</span>
        <?= htmlspecialchars($product['name']) ?>
    </nav>

    <div class="product-detail">
        <div class="product-detail-grid">
            <!-- Image -->
            <div class="product-detail-image">
                <?= renderProductImage($product) ?>
            </div>

            <!-- Info -->
            <div class="product-detail-info">
                <span class="badge-tag category">Necklace</span>
                <?php if ($product['isNew']): ?>&nbsp;<span class="badge-tag new">New</span><?php endif; ?>
                <?php if ($product['isBestseller']): ?>&nbsp;<span class="badge-tag bestseller">Bestseller</span><?php endif; ?>

                <h1 class="product-detail-name" style="margin-top:1rem"><?= htmlspecialchars($product['name']) ?></h1>
                <div class="product-rating" style="margin-bottom:0.5rem"><?= renderStars($product['rating']) ?></div>
                <p class="product-detail-price"><?= formatCurrency($product['price'] * USD_TO_PHP) ?></p>
                <p class="product-detail-desc"><?= htmlspecialchars($product['description']) ?></p>

                <!-- Qty + Add to bag -->
                <div class="product-detail-actions">
                    <label class="form-label" for="productQty">Quantity</label>
                    <div class="product-detail-actions-row">
                        <div class="qty-control">
                            <button class="qty-btn qty-dec" type="button" aria-label="Decrease quantity">−</button>
                            <span class="qty-value" id="productQtyDisplay">1</span>
                            <button class="qty-btn qty-inc" type="button" aria-label="Increase quantity">+</button>
                            <input type="hidden" id="productQty" value="1">
                        </div>
                        <button class="btn btn-primary btn-lg add-to-bag-btn"
                                data-product-id="<?= $id ?>"
                                id="detailAddBtn"
                                style="flex:1">
                            Add to Bag
                        </button>
                    </div>
                    <button class="wishlist-btn <?= in_array($id, $wishlist) ? 'active' : '' ?>"
                            data-product-id="<?= $id ?>"
                            id="detailWishlistBtn"
                            style="position:static;background:none;border:none;display:flex;align-items:center;gap:0.5rem;color:var(--color-muted);font-size:0.82rem;cursor:pointer;width:fit-content;padding:0.5rem 0">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:18px;height:18px">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                        <?= in_array($id, $wishlist) ? 'Saved to Wishlist' : 'Add to Wishlist' ?>
                    </button>
                </div>

                <!-- Meta -->
                <div class="product-meta">
                    <div class="product-meta-row"><span class="product-meta-label">Category</span><span class="product-meta-val">Fine Necklace</span></div>
                    <div class="product-meta-row"><span class="product-meta-label">Material</span><span class="product-meta-val">925 Sterling Silver / Gold-filled</span></div>
                    <div class="product-meta-row"><span class="product-meta-label">Shipping</span><span class="product-meta-val">3–7 business days</span></div>
                    <div class="product-meta-row"><span class="product-meta-label">Returns</span><span class="product-meta-val">30-day hassle-free returns</span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related products -->
    <section class="related-section">
        <div class="container">
            <div class="related-header">
                <p class="section-label">✦ You May Also Like</p>
                <h2 class="section-title">More <em>Necklaces</em></h2>
            </div>
            <div class="products-grid">
                <?php foreach ($related as $rid => $rp): ?>
                <article class="product-card" id="related-<?= $rid ?>">
                    <a href="product.php?id=<?= $rid ?>" class="product-image-wrap" tabindex="-1">
                        <?= renderProductImage($rp) ?>
                    </a>
                    <button class="wishlist-btn <?= in_array($rid, $wishlist) ? 'active' : '' ?>"
                            data-product-id="<?= $rid ?>" aria-label="Add to wishlist">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                        </svg>
                    </button>
                    <div class="product-body">
                        <h3 class="product-name"><a href="product.php?id=<?= $rid ?>"><?= htmlspecialchars($rp['name']) ?></a></h3>
                        <div class="product-footer-row">
                            <span class="product-price"><?= formatCurrency($rp['price'] * USD_TO_PHP) ?></span>
                            <button class="btn btn-primary btn-sm add-to-bag-btn" data-product-id="<?= $rid ?>" id="rel-add-<?= $rid ?>">Add to Bag</button>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
