<?php
session_start();
require_once 'includes/auth.php';
require_once 'includes/products-data.php';

header('Content-Type: application/json');

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$action    = $_GET['action'] ?? '';
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$qty       = max(1, intval($_GET['qty'] ?? 1));

switch ($action) {
    case 'add':
        if ($productId && isset($products[$productId])) {
            $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + $qty;
        }
        break;

    case 'remove':
        unset($_SESSION['cart'][$productId]);
        break;

    case 'update':
        if ($productId && $qty > 0 && isset($products[$productId])) {
            $_SESSION['cart'][$productId] = $qty;
        } elseif ($qty <= 0) {
            unset($_SESSION['cart'][$productId]);
        }
        break;

    case 'clear':
        $_SESSION['cart'] = [];
        break;

    case 'wishlist':
        if (!isLoggedIn()) {
            echo json_encode(['loggedOut' => true]);
            exit;
        }
        $added = toggleWishlist($productId);
        echo json_encode([
            'added'         => $added,
            'wishlistCount' => count(getUserWishlist()),
        ]);
        exit;
}

echo json_encode([
    'cartCount' => getCartCount(),
    'cartTotal' => formatCurrency(getCartTotal($_SESSION['cart'])),
]);
