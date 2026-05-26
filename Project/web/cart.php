<?php
require_once 'includes/auth.php';
require_once 'includes/products-data.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Handle cart form POST (quantity update / remove)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pid    = intval($_POST['product_id'] ?? 0);
    $qty    = intval($_POST['qty'] ?? 0);

    if ($action === 'update' && $pid) {
        if ($qty > 0) $_SESSION['cart'][$pid] = $qty;
        else unset($_SESSION['cart'][$pid]);
    } elseif ($action === 'remove' && $pid) {
        unset($_SESSION['cart'][$pid]);
    } elseif ($action === 'clear') {
        $_SESSION['cart'] = [];
    }
    header('Location: cart.php');
    exit;
}

$cart     = $_SESSION['cart'];
$subtotal = getCartTotal($cart);
$shipping = 199;
$total    = $subtotal + $shipping;

$pageTitle       = 'Shopping Bag';
$metaDescription = 'Review your Aurielle shopping bag and proceed when ready.';
require_once 'includes/header.php';
?>

<main>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span> Shopping Bag
    </nav>

    <div class="cart-page">
        <h1>Your Shopping Bag
            <?php if (!empty($cart)): ?>
                <span style="font-family:var(--font-sans);font-size:1rem;font-weight:400;color:var(--color-muted);margin-left:0.5rem">(<?= getCartCount() ?> items)</span>
            <?php endif; ?>
        </h1>

        <?php if (empty($cart)): ?>
        <div class="empty-state" id="emptyCart">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/>
                <path d="M16 10a4 4 0 0 1-8 0"/>
            </svg>
            <h2>Your bag is empty</h2>
            <p>Looks like you haven't added any necklaces yet.</p>
            <a href="shop.php" class="btn btn-primary btn-lg" id="startShoppingBtn">Start Shopping</a>
        </div>

        <?php else: ?>
        <div class="cart-layout">
            <!-- Cart items -->
            <div>
                <table class="cart-table" id="cartTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart as $pid => $qty):
                            if (!isset($products[$pid])) continue;
                            $p        = $products[$pid];
                            $itemPrice = $p['price'] * USD_TO_PHP;
                            $itemTotal = $itemPrice * $qty;
                        ?>
                        <tr id="cart-row-<?= $pid ?>">
                            <td>
                                <div class="cart-item-cell">
                                    <div class="cart-item-img" style="width:80px; height:80px;">
                                        <?= renderProductImage($p) ?>
                                    </div>
                                    <div>
                                        <p class="cart-item-name"><?= htmlspecialchars($p['name']) ?></p>
                                        <p class="cart-item-cat">Necklace</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="cart-price"><?= formatCurrency($itemPrice) ?></span></td>
                            <td>
                                <form method="POST" id="qtyForm-<?= $pid ?>">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="product_id" value="<?= $pid ?>">
                                    <div class="qty-control">
                                        <button class="qty-btn qty-dec" type="button" aria-label="Decrease">−</button>
                                        <span class="qty-value"><?= $qty ?></span>
                                        <button class="qty-btn qty-inc" type="button" aria-label="Increase">+</button>
                                        <input type="hidden" name="qty" value="<?= $qty ?>">
                                    </div>
                                </form>
                            </td>
                            <td><span class="cart-subtotal"><?= formatCurrency($itemTotal) ?></span></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="product_id" value="<?= $pid ?>">
                                    <button type="submit" class="btn btn-danger" id="remove-<?= $pid ?>" aria-label="Remove <?= htmlspecialchars($p['name']) ?>">✕ Remove</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="display:flex;justify-content:space-between;align-items:center;margin-top:1.5rem;flex-wrap:wrap;gap:1rem">
                    <a href="shop.php" class="btn btn-ghost" id="continueShoppingBtn">← Continue Shopping</a>
                    <form method="POST">
                        <input type="hidden" name="action" value="clear">
                        <button type="submit" class="btn btn-ghost" id="clearBagBtn">Clear Bag</button>
                    </form>
                </div>
            </div>

            <!-- Order summary -->
            <aside class="order-summary" id="orderSummary">
                <h2>Order Summary</h2>
                <div class="summary-row"><span>Subtotal</span><span><?= formatCurrency($subtotal) ?></span></div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span><?= formatCurrency($shipping) ?></span>
                </div>
                <div class="summary-row total"><span>Total</span><span><?= formatCurrency($total) ?></span></div>

                <a href="checkout.php" class="btn btn-gold btn-full btn-lg" id="checkoutBtn">
                    Proceed to Checkout →
                </a>
            </aside>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
