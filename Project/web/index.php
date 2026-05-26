<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/products-data.php';

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$pageTitle       = 'Aurielle — Fine Necklaces';
$metaDescription = 'Discover Aurielle\'s handcrafted necklace collection. Timeless elegance crafted for modern women.';

$featured    = array_filter($products, fn($p) => $p['isBestseller']);
$newArrivals = array_filter($products, fn($p) => $p['isNew']);
$wishlist    = isLoggedIn() ? getUserWishlist() : [];

require_once 'includes/header.php';
?>

<main>
    <section class="hero" id="home-hero">
        <div class="hero-content">
            <span class="hero-label">✦ New Collection 2026 ✦</span>
            <h1><em>Wear Your</em> <span>Story</span></h1>
            <p class="hero-subtitle">Handcrafted necklaces that speak of elegance, love, and timeless beauty — made for the woman who knows her worth.</p>
            <div class="hero-actions">
                <a href="shop.php" class="btn btn-primary btn-lg" id="heroShopBtn">Shop the Collection</a>
                <a href="about.php" class="btn btn-outline btn-lg" id="heroAboutBtn">Our Story</a>
            </div>
        </div>
        <div class="hero-scroll" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 5v14M5 12l7 7 7-7"/></svg>
            <span>Scroll</span>
        </div>
    </section>

    <section class="home-section" id="featured">
        <div class="home-section-header">
            <p class="section-label">✦ Bestsellers</p>
            <h2 class="section-title">Most <em>Loved</em> Pieces</h2>
        </div>
        <div class="products-grid">
            <?php foreach (array_slice($featured, 0, 4, true) as $id => $p): ?>
            <article class="product-card" id="product-card-<?= $id ?>">
                <div class="product-badges">
                    <?php if ($p['isNew']): ?><span class="badge-tag new">New</span><?php endif; ?>
                    <?php if ($p['isBestseller']): ?><span class="badge-tag bestseller">Bestseller</span><?php endif; ?>
                </div>
                <a href="product.php?id=<?= $id ?>" class="product-image-wrap" tabindex="-1" aria-hidden="true">
                    <?= renderProductImage($p) ?>
                </a>
                <button class="wishlist-btn <?= in_array($id, $wishlist) ? 'active' : '' ?>"
                        data-product-id="<?= $id ?>" aria-label="Add to wishlist">
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
                        <button class="btn btn-primary btn-sm add-to-bag-btn" data-product-id="<?= $id ?>" id="add-<?= $id ?>">Add to Bag</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center;margin-top:3rem;">
            <a href="shop.php" class="btn btn-outline btn-lg" id="viewAllBtn">View All Necklaces</a>
        </div>
    </section>

    <section class="trust-strip" id="trust">
        <div class="trust-grid">
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 5v3h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                <h3>Nationwide Shipping</h3>
                <p>Delivered with care to your door nationwide.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <h3>Authentic Quality</h3>
                <p>Every piece is quality-checked and certified before shipping.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M23 7l-7 5 7 5V7z"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>
                <h3>Easy Returns</h3>
                <p>Not in love? Return within 30 days — no questions asked.</p>
            </div>
            <div class="trust-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l9.84-9.84a5.5 5.5 0 0 0 0-7.78z"/></svg>
                <h3>Handcrafted Love</h3>
                <p>Each necklace is made by artisan hands, with heart and intention.</p>
            </div>
        </div>
    </section>

    <?php if (!empty($newArrivals)): ?>
    <section class="home-section" id="new-arrivals">
        <div class="home-section-header">
            <p class="section-label">✦ Just In</p>
            <h2 class="section-title">New <em>Arrivals</em></h2>
        </div>
        <div class="products-grid">
            <?php foreach (array_slice($newArrivals, 0, 4, true) as $id => $p): ?>
            <article class="product-card" id="new-card-<?= $id ?>">
                <div class="product-badges"><span class="badge-tag new">New</span></div>
                <a href="product.php?id=<?= $id ?>" class="product-image-wrap" tabindex="-1">
                    <?= renderProductImage($p) ?>
                </a>
                <button class="wishlist-btn <?= in_array($id, $wishlist) ? 'active' : '' ?>"
                        data-product-id="<?= $id ?>" aria-label="Add to wishlist">
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
                        <button class="btn btn-primary btn-sm add-to-bag-btn" data-product-id="<?= $id ?>" id="new-add-<?= $id ?>">Add to Bag</button>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section class="newsletter-strip" id="newsletter">
        <p class="section-label">✦ Join the Club</p>
        <h2>Be the First to Know</h2>
        <p>New arrivals, exclusive deals, and styling inspiration — delivered to your inbox.</p>
        <form class="newsletter-form-home" onsubmit="handleNewsletter(event)" id="homeNewsletter">
            <input type="email" placeholder="Enter your email address" required aria-label="Email for newsletter">
            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
    </section>

</main>

<?php require_once 'includes/footer.php'; ?>