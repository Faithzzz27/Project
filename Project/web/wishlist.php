<?php
require_once 'includes/auth.php';
require_once 'includes/products-data.php';

if (!isLoggedIn()) {
    header('Location: login.php?redirect=wishlist.php');
    exit;
}

// Handle move to bag / remove
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pid    = intval($_POST['product_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if ($action === 'move_to_bag' && $pid && isset($products[$pid])) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
        toggleWishlist($pid); // remove from wishlist
    } elseif ($action === 'remove') {
        toggleWishlist($pid);
    }
    header('Location: wishlist.php');
    exit;
}

$wishlistIds = getUserWishlist();
$wishlistProducts = array_filter($products, fn($p, $k) => in_array($k, $wishlistIds), ARRAY_FILTER_USE_BOTH);

$pageTitle       = 'My Wishlist';
$metaDescription = 'Your Aurielle wishlist — saved necklaces you love.';
require_once 'includes/header.php';
?>

<main>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span> My Wishlist
    </nav>

    <div class="wishlist-page">
        <h1>My Wishlist
            <span style="font-family:var(--font-sans);font-size:1rem;font-weight:400;color:var(--color-muted);margin-left:0.5rem">(<?= count($wishlistProducts) ?> items)</span>
        </h1>

        <?php if (empty($wishlistProducts)): ?>
        <div class="empty-state" id="emptyWishlist">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
            </svg>
            <h2>Your wishlist is empty</h2>
            <p>Save the necklaces you love and find them here later.</p>
            <a href="shop.php" class="btn btn-primary btn-lg" id="shopNowBtn">Explore Necklaces</a>
        </div>

        <?php else: ?>
        <div class="products-grid" id="wishlistGrid">
            <?php foreach ($wishlistProducts as $id => $p): ?>
            <article class="product-card" id="wish-card-<?= $id ?>">
                <div class="product-badges">
                    <?php if ($p['isNew']): ?><span class="badge-tag new">New</span><?php endif; ?>
                </div>
                <a href="product.php?id=<?= $id ?>" class="product-image-wrap" tabindex="-1">
                    <?= renderProductImage($p) ?>
                </a>
                <div class="product-body">
                    <p class="product-cat">Necklace</p>
                    <h3 class="product-name"><a href="product.php?id=<?= $id ?>"><?= htmlspecialchars($p['name']) ?></a></h3>
                    <div class="product-rating"><?= renderStars($p['rating']) ?></div>
                    <p class="product-price" style="margin:0.75rem 0"><?= formatCurrency($p['price'] * USD_TO_PHP) ?></p>
                    <div style="display:flex;flex-direction:column;gap:0.5rem">
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <input type="hidden" name="action" value="move_to_bag">
                            <button type="submit" class="btn btn-primary btn-full btn-sm" id="move-<?= $id ?>">Move to Bag</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="product_id" value="<?= $id ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="btn btn-ghost btn-full btn-sm" id="unwish-<?= $id ?>">Remove</button>
                        </form>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
