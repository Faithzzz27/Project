<?php
require_once 'includes/auth.php';
require_once 'includes/products-data.php';
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Redirect to cart if empty
if (empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

// Handle order placement (mock)
$orderPlaced = false;
$orderNumber = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'place_order') {
    $orderNumber = 'AUR-' . strtoupper(substr(uniqid(), -6));
    // Store order summary for confirmation screen
    $_SESSION['last_order'] = [
        'number'   => $orderNumber,
        'cart'     => $_SESSION['cart'],
        'total'    => getCartTotal($_SESSION['cart']),
        'shipping' => [
            'name'    => trim($_POST['full_name'] ?? ''),
            'blk_lot' => trim($_POST['blk_lot']   ?? ''),
            'street'  => trim($_POST['street']    ?? ''),
            'barangay'=> trim($_POST['barangay']  ?? ''),
            'city'    => trim($_POST['city']       ?? ''),
            'province'=> trim($_POST['province']   ?? ''),
            'country' => trim($_POST['country']    ?? ''),
            'zip'     => trim($_POST['zip']        ?? ''),
            'phone'   => trim($_POST['phone']      ?? ''),
        ],
        'payment'  => $_POST['payment_method'] ?? 'card',
        'placed_at'=> date('F j, Y'),
    ];
    // Clear cart
    $_SESSION['cart'] = [];
    header('Location: checkout.php?success=1');
    exit;
}

// Retrieve last order for success screen
if (isset($_GET['success']) && !empty($_SESSION['last_order'])) {
    $order = $_SESSION['last_order'];
    $orderPlaced = true;
}

$cart     = $_SESSION['cart'];
$subtotal = getCartTotal($cart);
$shipping = 199;
$total    = $subtotal + $shipping;
$user     = getCurrentUser();

$pageTitle       = $orderPlaced ? 'Order Confirmed!' : 'Checkout';
$metaDescription = 'Complete your Aurielle order securely.';
require_once 'includes/header.php';
?>

<main>
<?php if ($orderPlaced && isset($order)): ?>

    <!-- ══════════════════════════════════════════════════
         SUCCESS SCREEN
    ═══════════════════════════════════════════════════ -->
    <div class="checkout-page">
        <div class="checkout-success" id="orderSuccess">
            <div class="success-icon">🎉</div>
            <div class="success-order-num">Order <?= htmlspecialchars($order['number']) ?></div>
            <h1>Thank You<?= $user ? ', ' . htmlspecialchars(explode(' ', $user['name'])[0]) : '' ?>!</h1>
            <p>Your order has been placed and is being prepared with love. You'll receive a confirmation email at <strong><?= $user ? htmlspecialchars($user['email']) : 'your email' ?></strong>.</p>
            <p style="font-size:0.85rem">Estimated delivery: <strong>3–7 business days</strong> &nbsp;·&nbsp; Placed on <strong><?= htmlspecialchars($order['placed_at']) ?></strong></p>

            <div class="success-items-preview">
                <h4>Items in your order</h4>
                <?php foreach ($order['cart'] as $pid => $qty):
                    $p = $products[$pid] ?? null; if (!$p) continue; ?>
                <div class="review-item">
                    <div class="review-item-img" style="width:60px; height:60px;">
                        <?= renderProductImage($p) ?>
                    </div>
                    <div>
                        <p class="review-item-name"><?= htmlspecialchars($p['name']) ?></p>
                        <p class="review-item-cat">Qty: <?= $qty ?></p>
                    </div>
                    <span class="review-item-price"><?= formatCurrency($p['price'] * USD_TO_PHP * $qty) ?></span>
                </div>
                <?php endforeach; ?>
                <hr style="border:none;border-top:1px solid var(--color-border);margin:1rem 0">
                <div style="display:flex;justify-content:space-between;font-weight:600">
                    <span>Total Paid</span>
                    <span><?= formatCurrency($order['total'] + 199) ?></span>
                </div>
            </div>

            <div style="background:var(--color-bg);border:1px solid var(--color-border);border-radius:var(--radius-md);padding:1rem;margin-bottom:1rem;text-align:left">
                <h4 style="font-family:var(--font-sans);font-size:0.7rem;font-weight:600;letter-spacing:0.12em;text-transform:uppercase;color:var(--color-muted);margin-bottom:0.5rem">Shipping to</h4>
                <p style="font-size:0.85rem;color:var(--color-text);line-height:1.7">
                    <?= htmlspecialchars($order['shipping']['name']) ?><br>
                    Blk/Lot <?= htmlspecialchars($order['shipping']['blk_lot']) ?>,
                    <?= htmlspecialchars($order['shipping']['street']) ?>,<br>
                    Brgy. <?= htmlspecialchars($order['shipping']['barangay']) ?>,
                    <?= htmlspecialchars($order['shipping']['city']) ?>,<br>
                    <?= htmlspecialchars($order['shipping']['province']) ?>,
                    <?= htmlspecialchars($order['shipping']['country']) ?> <?= htmlspecialchars($order['shipping']['zip']) ?>
                </p>
            </div>

            <div class="success-actions">
                <a href="shop.php" class="btn btn-primary btn-lg" id="continueShopping">Continue Shopping</a>
                <a href="index.php" class="btn btn-outline btn-lg" id="backHome">Back to Home</a>
            </div>
        </div>
    </div>

<?php else: ?>

    <!-- ══════════════════════════════════════════════════
         CHECKOUT FORM
    ═══════════════════════════════════════════════════ -->
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span>
        <a href="cart.php">Bag</a><span>›</span> Checkout
    </nav>

    <div class="checkout-page">

        <!-- Step indicators -->
        <div class="checkout-steps" role="tablist" aria-label="Checkout steps">
            <div class="step active" id="stepIndicator1">
                <div class="step-circle">1</div>
                <span class="step-label">Shipping</span>
            </div>
            <div class="step-connector" id="connector1"></div>
            <div class="step" id="stepIndicator2">
                <div class="step-circle">2</div>
                <span class="step-label">Payment</span>
            </div>
            <div class="step-connector" id="connector2"></div>
            <div class="step" id="stepIndicator3">
                <div class="step-circle">3</div>
                <span class="step-label">Review</span>
            </div>
        </div>

        <form method="POST" id="checkoutForm" novalidate>
            <input type="hidden" name="action" value="place_order">

            <div class="checkout-layout">

                <!-- Left: panels -->
                <div>
                    <!-- ─── STEP 1: Shipping ─── -->
                    <div class="checkout-panel active" id="panel1">
                        <h2><span>1</span> Shipping Information</h2>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="full_name">Full name</label>
                                <input class="form-input" type="text" id="full_name" name="full_name"
                                       value="<?= $user ? htmlspecialchars($user['name']) : '' ?>"
                                       placeholder="Your full name" required>
                                <span class="form-error-msg"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="email_co">Email address</label>
                                <input class="form-input" type="email" id="email_co" name="email_co"
                                       value="<?= $user ? htmlspecialchars($user['email']) : '' ?>"
                                       placeholder="you@example.com" required>
                                <span class="form-error-msg"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="blk_lot">Blk and Lot</label>
                                <input class="form-input" type="text" id="blk_lot" name="blk_lot" placeholder="e.g. Blk 1 Lot 2" required>
                                <span class="form-error-msg"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="street">Street</label>
                                <input class="form-input" type="text" id="street" name="street" placeholder="e.g. Main Street" required>
                                <span class="form-error-msg"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="barangay">Barangay</label>
                            <input class="form-input" type="text" id="barangay" name="barangay" placeholder="e.g. Brgy. San Lorenzo" required>
                            <span class="form-error-msg"></span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="city">City / Municipality</label>
                                <input class="form-input" type="text" id="city" name="city"
                                       placeholder="e.g. Makati City" required>
                                <span class="form-error-msg"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="province">Province</label>
                                <input class="form-input" type="text" id="province" name="province" placeholder="e.g. Metro Manila" required>
                                <span class="form-error-msg"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="country">Country</label>
                                <input class="form-input" type="text" id="country" name="country" value="Philippines" required>
                                <span class="form-error-msg"></span>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="zip">ZIP code</label>
                                <input class="form-input" type="text" id="zip" name="zip"
                                       placeholder="1200" required>
                                <span class="form-error-msg"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone number</label>
                            <input class="form-input" type="tel" id="phone" name="phone"
                                   placeholder="+63 9xx xxx xxxx" required>
                            <span class="form-error-msg"></span>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="notes">Order notes <span style="font-weight:400;color:var(--color-muted)">(optional)</span></label>
                            <textarea class="form-textarea" id="notes" name="notes" placeholder="Special instructions, gift message, etc." style="min-height:80px"></textarea>
                        </div>

                        <div class="checkout-nav">
                            <a href="cart.php" class="btn btn-ghost" id="backToCart">← Back to Bag</a>
                            <button type="button" class="btn btn-primary btn-lg" id="toStep2Btn" onclick="goToStep(2)">
                                Continue to Payment →
                            </button>
                        </div>
                    </div>

                    <!-- ─── STEP 2: Payment ─── -->
                    <div class="checkout-panel" id="panel2">
                        <h2><span>2</span> Payment Method</h2>

                        <div class="payment-methods">
                            <label class="payment-option selected" id="opt-card" for="pm_card">
                                <input type="radio" name="payment_method" id="pm_card" value="card" checked>
                                <div>
                                    <p class="payment-option-label">Credit / Debit Card</p>
                                    <p class="payment-option-sub">Visa, Mastercard, JCB</p>
                                </div>
                                <div class="payment-icons">
                                    <span class="payment-icon-pill">VISA</span>
                                    <span class="payment-icon-pill">MC</span>
                                    <span class="payment-icon-pill">JCB</span>
                                </div>
                            </label>

                            <label class="payment-option" id="opt-gcash" for="pm_gcash">
                                <input type="radio" name="payment_method" id="pm_gcash" value="gcash">
                                <div>
                                    <p class="payment-option-label">GCash</p>
                                    <p class="payment-option-sub">Pay via GCash mobile wallet</p>
                                </div>
                                <div class="payment-icons">
                                    <span class="payment-icon-pill" style="background:#007AFF;color:#fff">G</span>
                                </div>
                            </label>

                            <label class="payment-option" id="opt-cod" for="pm_cod">
                                <input type="radio" name="payment_method" id="pm_cod" value="cod">
                                <div>
                                    <p class="payment-option-label">Cash on Delivery</p>
                                    <p class="payment-option-sub">Pay when your order arrives</p>
                                </div>
                                <div class="payment-icons">
                                    <span class="payment-icon-pill">COD</span>
                                </div>
                            </label>

                            <label class="payment-option" id="opt-maya" for="pm_maya">
                                <input type="radio" name="payment_method" id="pm_maya" value="maya">
                                <div>
                                    <p class="payment-option-label">Maya (PayMaya)</p>
                                    <p class="payment-option-sub">Pay via Maya digital wallet</p>
                                </div>
                                <div class="payment-icons">
                                    <span class="payment-icon-pill" style="background:#00C98D;color:#fff">M</span>
                                </div>
                            </label>
                        </div>

                        <!-- Card fields (shown only for card method) -->
                        <div class="card-fields show" id="cardFields">
                            <div class="form-group">
                                <label class="form-label" for="card_number">Card number</label>
                                <input class="form-input" type="text" id="card_number" name="card_number"
                                       placeholder="1234 5678 9012 3456" maxlength="19" autocomplete="cc-number">
                                <span class="form-hint">This is a mock checkout — no real payment is processed.</span>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="card_expiry">Expiry date</label>
                                    <input class="form-input" type="text" id="card_expiry" name="card_expiry"
                                           placeholder="MM / YY" maxlength="7" autocomplete="cc-exp">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="card_cvc">CVC</label>
                                    <input class="form-input" type="text" id="card_cvc" name="card_cvc"
                                           placeholder="•••" maxlength="4" autocomplete="cc-csc">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="card_name">Name on card</label>
                                <input class="form-input" type="text" id="card_name" name="card_name"
                                       placeholder="As it appears on the card">
                            </div>
                        </div>

                        <!-- GCash / Maya instructions -->
                        <div class="card-fields" id="walletInstructions">
                            <div style="background:var(--color-bg);border:1px solid var(--color-border);border-radius:var(--radius-md);padding:1.25rem;font-size:0.85rem;color:var(--color-muted);line-height:1.8">
                                📱 After placing your order, you'll receive a payment link via SMS/email to complete payment through your selected wallet.
                            </div>
                        </div>

                        <!-- COD instructions -->
                        <div class="card-fields" id="codInstructions">
                            <div style="background:var(--color-bg);border:1px solid var(--color-border);border-radius:var(--radius-md);padding:1.25rem;font-size:0.85rem;color:var(--color-muted);line-height:1.8">
                                🏠 Pay in cash when your package arrives. Please prepare the exact amount. Our courier will provide a receipt.
                            </div>
                        </div>

                        <div class="checkout-nav">
                            <button type="button" class="btn btn-ghost" id="backToStep1" onclick="goToStep(1)">← Back</button>
                            <button type="button" class="btn btn-primary btn-lg" id="toStep3Btn" onclick="goToStep(3)">
                                Review Order →
                            </button>
                        </div>
                    </div>

                    <!-- ─── STEP 3: Review ─── -->
                    <div class="checkout-panel" id="panel3">
                        <h2><span>3</span> Review Your Order</h2>

                        <!-- Review items -->
                        <div class="review-items" id="reviewItems">
                            <?php foreach ($cart as $pid => $qty):
                                $p = $products[$pid] ?? null; if (!$p) continue;
                            ?>
                            <div class="review-item">
                                <div class="review-item-img" style="width:60px; height:60px;">
                                    <?= renderProductImage($p) ?>
                                </div>
                                <div>
                                    <p class="review-item-name"><?= htmlspecialchars($p['name']) ?></p>
                                    <p class="review-item-cat">Qty: <?= $qty ?></p>
                                </div>
                                <span class="review-item-price"><?= formatCurrency($p['price'] * USD_TO_PHP * $qty) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Shipping + Payment summary -->
                        <div class="review-addresses" id="reviewAddresses">
                            <div class="review-address-box">
                                <h4>Shipping Address</h4>
                                <p id="reviewShippingText" style="color:var(--color-muted);font-style:italic;font-size:0.82rem">Fill in shipping details in Step 1.</p>
                            </div>
                            <div class="review-address-box">
                                <h4>Payment Method</h4>
                                <p id="reviewPaymentText" style="color:var(--color-muted);font-size:0.82rem">Credit / Debit Card</p>
                            </div>
                        </div>

                        <!-- Mock notice -->
                        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:var(--radius-md);padding:1rem 1.25rem;font-size:0.82rem;color:#7a5c00;margin-bottom:1.5rem;display:flex;gap:0.6rem;align-items:flex-start">
                            <span style="font-size:1rem;flex-shrink:0">⚠️</span>
                            <span><strong>Demo mode:</strong> This is a mock checkout. No real payment will be charged and no actual order will be shipped. Your cart will be cleared after placing the order.</span>
                        </div>

                        <div class="checkout-nav">
                            <button type="button" class="btn btn-ghost" id="backToStep2" onclick="goToStep(2)">← Back</button>
                            <button type="submit" class="btn btn-gold btn-lg" id="placeOrderBtn">
                                Place Order ✦
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right: order summary -->
                <aside class="checkout-summary" id="checkoutSummary">
                    <h3>Order Summary</h3>
                    <div class="checkout-summary-items">
                        <?php foreach ($cart as $pid => $qty):
                            $p = $products[$pid] ?? null; if (!$p) continue;
                        ?>
                        <div class="cs-item">
                            <div class="cs-item-img" style="position:relative; width:48px; height:48px;">
                                <?= renderProductImage($p) ?>
                                <span class="cs-item-badge"><?= $qty ?></span>
                            </div>
                            <span class="cs-item-name"><?= htmlspecialchars($p['name']) ?></span>
                            <span class="cs-item-price"><?= formatCurrency($p['price'] * USD_TO_PHP * $qty) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <hr class="cs-divider">
                    <div class="cs-row"><span>Subtotal</span><span><?= formatCurrency($subtotal) ?></span></div>
                    <div class="cs-row">
                        <span>Shipping</span>
                        <span><?= formatCurrency($shipping) ?></span>
                    </div>
                    <div class="cs-total"><span>Total</span><span><?= formatCurrency($total) ?></span></div>

                    <div style="margin-top:1.25rem;padding:1rem;background:var(--color-bg);border-radius:var(--radius-sm);font-size:0.78rem;color:var(--color-muted);display:flex;gap:0.5rem;align-items:flex-start">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" style="width:16px;height:16px;flex-shrink:0;color:var(--color-gold);margin-top:1px"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Secure mock checkout. No real payment is processed.
                    </div>
                </aside>

            </div><!-- .checkout-layout -->
        </form>
    </div><!-- .checkout-page -->

<?php endif; ?>
</main>

<script>
// ── Checkout multi-step logic ─────────────────────────
let currentStep = 1;

function goToStep(step) {
    const panels = document.querySelectorAll('.checkout-panel');
    const indicators = document.querySelectorAll('.step');
    const connectors = document.querySelectorAll('.step-connector');

    // Validate step 1 before proceeding to step 2
    if (step === 2 && currentStep === 1) {
        const fields = ['full_name','email_co','blk_lot','street','barangay','city','province','country','zip','phone'];
        let valid = true;
        fields.forEach(id => {
            const el = document.getElementById(id);
            const err = el?.parentElement?.querySelector('.form-error-msg');
            if (el && !el.value.trim()) {
                el.classList.add('error');
                if (err) err.textContent = 'This field is required.';
                valid = false;
            } else if (el) {
                el.classList.remove('error');
                if (err) err.textContent = '';
            }
        });
        if (!valid) { showToast && showToast('Please fill in all shipping details.', 'error'); return; }
    }

    currentStep = step;

    panels.forEach((p, i) => p.classList.toggle('active', i + 1 === step));
    indicators.forEach((ind, i) => {
        ind.classList.toggle('active', i + 1 === step);
        ind.classList.toggle('done',   i + 1 < step);
    });
    connectors.forEach((c, i) => c.classList.toggle('done', i + 1 < step));

    // Update review text when reaching step 3
    if (step === 3) updateReview();

    // Scroll to top of checkout panel smoothly
    document.querySelector('.checkout-steps')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function updateReview() {
    const name    = document.getElementById('full_name')?.value || '';
    const blkLot  = document.getElementById('blk_lot')?.value   || '';
    const street  = document.getElementById('street')?.value    || '';
    const brgy    = document.getElementById('barangay')?.value  || '';
    const city    = document.getElementById('city')?.value      || '';
    const prov    = document.getElementById('province')?.value  || '';
    const country = document.getElementById('country')?.value   || '';
    const zip     = document.getElementById('zip')?.value       || '';
    const el      = document.getElementById('reviewShippingText');
    if (el) el.innerHTML = `${name}<br>Blk/Lot ${blkLot}, ${street},<br>Brgy. ${brgy}, ${city},<br>${prov}, ${country} ${zip}`;

    const method  = document.querySelector('input[name="payment_method"]:checked')?.value || 'card';
    const labels  = { card:'Credit / Debit Card', gcash:'GCash', cod:'Cash on Delivery', maya:'Maya (PayMaya)' };
    const elPay   = document.getElementById('reviewPaymentText');
    if (elPay) elPay.textContent = labels[method] || method;
}

// ── Payment method toggle ─────────────────────────────
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', () => {
        document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
        radio.closest('.payment-option').classList.add('selected');

        const val = radio.value;
        document.getElementById('cardFields').classList.toggle('show', val === 'card');
        document.getElementById('walletInstructions').classList.toggle('show', val === 'gcash' || val === 'maya');
        document.getElementById('codInstructions').classList.toggle('show', val === 'cod');
    });
});

// ── Card number formatting ────────────────────────────
document.getElementById('card_number')?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').substring(0, 16);
    this.value = v.replace(/(.{4})/g, '$1 ').trim();
});
document.getElementById('card_expiry')?.addEventListener('input', function() {
    let v = this.value.replace(/\D/g, '').substring(0, 4);
    if (v.length >= 2) v = v.substring(0,2) + ' / ' + v.substring(2);
    this.value = v;
});

// ── Place order loading state ─────────────────────────
document.getElementById('placeOrderBtn')?.addEventListener('click', function() {
    this.textContent = 'Placing Order…';
    this.disabled = true;
});
</script>

<?php require_once 'includes/footer.php'; ?>
