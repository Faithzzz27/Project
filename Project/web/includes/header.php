<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/products-data.php';

$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$cartCount     = getCartCount();
$wishlistCount = isLoggedIn() ? getWishlistCount() : 0;
$user          = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $metaDescription ?? 'Aurielle — Fine handcrafted necklaces for modern elegance. Discover timeless pieces crafted with care.' ?>">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) . ' | Aurielle' : 'Aurielle — Fine Jewelry & Necklaces' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/pages.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/checkout.css">
</head>
<body>

<!-- Navigation -->
<header class="site-header" id="siteHeader">
    <div class="nav-container">

        <!-- Mobile hamburger -->
        <button class="hamburger" id="hamburger" aria-label="Toggle menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <!-- Logo -->
        <a href="index.php" class="brand-link">
            <div class="brand">
                <span class="brand-name">Aurielle</span>
                <span class="brand-tagline">Fine Necklaces</span>
            </div>
        </a>

        <!-- Main nav links -->
        <nav class="main-nav" id="mainNav" role="navigation" aria-label="Main navigation">
            <ul>
                <li><a href="index.php"    class="<?= $currentPage === 'index'    ? 'active' : '' ?>">Home</a></li>
                <li><a href="about.php"    class="<?= $currentPage === 'about'    ? 'active' : '' ?>">About</a></li>
                <li><a href="shop.php"     class="<?= $currentPage === 'shop'     ? 'active' : '' ?>">Shop</a></li>
                <li><a href="contact.php"  class="<?= $currentPage === 'contact'  ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>

        <!-- Right icons -->
        <div class="nav-actions">
            <!-- Wishlist -->
            <a href="<?= isLoggedIn() ? 'wishlist.php' : 'login.php?redirect=wishlist.php' ?>" class="nav-icon-btn" id="navWishlist" aria-label="Wishlist (<?= $wishlistCount ?> items)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                </svg>
                <?php if ($wishlistCount > 0): ?>
                    <span class="badge" id="wishlistBadge"><?= $wishlistCount ?></span>
                <?php endif; ?>
            </a>

            <!-- Cart -->
            <a href="cart.php" class="nav-icon-btn" id="navCart" aria-label="Shopping bag (<?= $cartCount ?> items)">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span class="badge" id="cartBadge" <?= $cartCount === 0 ? 'style="display:none"' : '' ?>><?= $cartCount ?></span>
            </a>

            <!-- User -->
            <?php if (isLoggedIn()): ?>
                <div class="user-dropdown" id="userDropdown">
                    <button class="nav-icon-btn user-btn" id="userDropdownBtn" aria-haspopup="true" aria-expanded="false" aria-label="Account menu">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        <span class="user-name-short"><?= htmlspecialchars(explode(' ', $user['name'])[0]) ?></span>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu" role="menu">
                        <p class="dropdown-greeting">Hello, <?= htmlspecialchars($user['name']) ?> 👋</p>
                        <a href="wishlist.php" role="menuitem">My Wishlist</a>
                        <a href="cart.php"     role="menuitem">My Bag</a>
                        <a href="logout.php"   role="menuitem" class="dropdown-logout">Sign Out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="nav-icon-btn" id="navUser" aria-label="Sign In">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span class="nav-signin-label">Sign In</span>
                </a>
            <?php endif; ?>
        </div>

    </div>
</header>

<!-- Toast notification container -->
<div id="toastContainer" role="alert" aria-live="polite"></div>

<!-- Mobile nav overlay -->
<div class="nav-overlay" id="navOverlay"></div>
