<?php
$products = [
    1 => [
        'name'         => 'Eternal Rose Necklace',
        'category'     => 'Necklace',
        'price'        => 79.99,
        'description'  => 'A delicate gold chain adorned with a shimmering white rose pendant — timeless elegance for every occasion.',
        'image'        => 'images/products/enternarosenecklace.jpg',
        'rating'       => 4.8,
        'isNew'        => true,
        'isBestseller' => false,
    ],
    2 => [
        'name'         => 'Luna Crescent Necklace',
        'category'     => 'Necklace',
        'price'        => 94.99,
        'description'  => 'A graceful crescent moon pendant encrusted with shimmering crystals and suspended on a fine sterling silver chain, perfect for the free-spirited soul.',
        'image'        => 'images/products/lunaCresentnecklace.jpg',
        'rating'       => 4.9,
        'isNew'        => false,
        'isBestseller' => true,
    ],
    3 => [
        'name'         => 'Celestial Star Pendant',
        'category'     => 'Necklace',
        'price'        => 64.99,
        'description'  => 'A dainty star pendant encrusted with micro-pavé crystals on an 18-karat gold-filled chain.',
        'image'        => 'images/products/celestialStar.jpg',
        'rating'       => 4.7,
        'isNew'        => false,
        'isBestseller' => true,
    ],
    4 => [
        'name'         => 'Crystal Teardrop Chain',
        'category'     => 'Necklace',
        'price'        => 109.99,
        'description'  => 'A brilliant teardrop-cut crystal pendant set in polished gold on a delicate bead chain — understated luxury at its finest.',
        'image'        => 'images/products/pearlteardropchain.jpg',
        'rating'       => 4.6,
        'isNew'        => false,
        'isBestseller' => false,
    ],
    5 => [
        'name'         => 'Diamond Solitaire Necklace',
        'category'     => 'Necklace',
        'price'        => 149.99,
        'description'  => 'A brilliant-cut crystal solitaire in a four-prong setting on a delicate cable chain — effortlessly sophisticated.',
        'image'        => 'images/products/diamondsolitairenecklace.jpg',
        'rating'       => 5.0,
        'isNew'        => true,
        'isBestseller' => true,
    ],
    6 => [
        'name'         => 'Twisted Gold Rope Chain',
        'category'     => 'Necklace',
        'price'        => 89.99,
        'description'  => 'A bold yet refined 18-karat gold-plated rope chain that makes a statement on its own or layered.',
        'image'        => 'images/products/twistedgoldropechain.jpg',
        'rating'       => 4.5,
        'isNew'        => false,
        'isBestseller' => false,
    ],
    7 => [
        'name'         => 'Fleur de Lis Pendant',
        'category'     => 'Necklace',
        'price'        => 74.99,
        'description'  => 'An ornate fleur de lis charm finished in polished yellow gold, evoking old-world Parisian charm.',
        'image'        => 'images/products/fluerdelispendant.jpg',
        'rating'       => 4.7,
        'isNew'        => false,
        'isBestseller' => false,
    ],
    8 => [
        'name'         => 'Infinity Love Necklace',
        'category'     => 'Necklace',
        'price'        => 59.99,
        'description'  => 'An infinity symbol pendant encrusted with shimmering crystals on a polished gold chain — a meaningful gift symbolizing endless love.',
        'image'        => 'images/products/infinitylove.jpg',
        'rating'       => 4.8,
        'isNew'        => false,
        'isBestseller' => true,
    ],
    9 => [
        'name'         => 'Vintage Locket Chain',
        'category'     => 'Necklace',
        'price'        => 119.99,
        'description'  => 'A keepsake heart-shaped locket with an engraved floral motif — holds two photos, treasures your most precious memories.',
        'image'        => 'images/products/vintagelocketchain.jpg',
        'rating'       => 4.9,
        'isNew'        => true,
        'isBestseller' => false,
    ],
    10 => [
        'name'         => 'Gemstone Cluster Pendant',
        'category'     => 'Necklace',
        'price'        => 134.99,
        'description'  => 'A vibrant cluster of semi-precious purple amethyst gemstones in a curved arrangement — a wearable bouquet of elegance.',
        'image'        => 'images/products/gemstoneclusterpendant.jpg',
        'rating'       => 4.6,
        'isNew'        => false,
        'isBestseller' => false,
    ],
    11 => [
        'name'         => 'Minimalist Bar Necklace',
        'category'     => 'Necklace',
        'price'        => 49.99,
        'description'  => 'A sleek horizontal bar pendant in 18-karat gold — the perfect understated piece for everyday wear.',
        'image'        => 'images/products/minimalistbar.jpg',
        'rating'       => 4.5,
        'isNew'        => false,
        'isBestseller' => false,
    ],
    12 => [
        'name'         => 'Layered Chain Set',
        'category'     => 'Necklace',
        'price'        => 99.99,
        'description'  => 'A curated set of three fine gold chains in varied lengths, featuring a minimalist bar and baguette crystal accents — the effortless layered look, ready to wear.',
        'image'        => 'images/products/layeredchainset.jpg',
        'rating'       => 4.9,
        'isNew'        => true,
        'isBestseller' => true,
    ],
];

define('USD_TO_PHP', 56.50);

function formatCurrency(float $amount): string {
    return '₱' . number_format($amount, 2);
}

function getCartTotal(array $cart): float {
    global $products;
    $total = 0.0;
    foreach ($cart as $id => $qty) {
        if (isset($products[$id])) {
            $total += $products[$id]['price'] * $qty * USD_TO_PHP;
        }
    }
    return $total;
}

function getCartCount(): int {
    if (!isset($_SESSION['cart'])) return 0;
    return array_sum($_SESSION['cart']);
}

function getWishlistCount(): int {
    return count(getUserWishlist());
}

function renderPlaceholder(string $name, string $classes = ''): string {
    $initials = strtoupper(substr($name, 0, 2));
    return '<div class="img-placeholder ' . htmlspecialchars($classes) . '" aria-label="' . htmlspecialchars($name) . '">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
            <rect x="3" y="3" width="18" height="18" rx="3"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
        </svg>
        <span>' . htmlspecialchars($name) . '</span>
    </div>';
}

function renderProductImage(array $product, string $classes = ''): string {
    $imgPath = $product['image'];
    if (file_exists(__DIR__ . '/../' . $imgPath)) {
        return '<img src="' . htmlspecialchars($imgPath) . '" alt="' . htmlspecialchars($product['name']) . '" class="product-img ' . htmlspecialchars($classes) . '">';
    }
    return renderPlaceholder($product['name'], $classes);
}

function renderStars(float $rating): string {
    $full  = floor($rating);
    $half  = ($rating - $full) >= 0.5 ? 1 : 0;
    $empty = 5 - $full - $half;
    $html  = '<span class="stars" aria-label="Rating: ' . $rating . ' out of 5">';
    for ($i = 0; $i < $full; $i++)  $html .= '<span class="star full">★</span>';
    if ($half)                        $html .= '<span class="star half">★</span>';
    for ($i = 0; $i < $empty; $i++) $html .= '<span class="star empty">★</span>';
    $html .= '<span class="star-count">(' . $rating . ')</span></span>';
    return $html;
}
