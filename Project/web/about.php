<?php
require_once 'includes/auth.php';

$pageTitle       = 'Our Story';
$metaDescription = 'Learn about Aurielle — handcrafted fine necklaces made with passion, artistry, and timeless elegance.';
require_once 'includes/header.php';
?>

<main>
    <!-- Hero -->
    <section class="about-hero" id="about-hero">
        <p class="section-label">✦ Our Story</p>
        <h1>Beauty in Every <em>Link</em></h1>
        <p>We believe a necklace is more than an accessory — it's a memory, a milestone, a piece of you. Aurielle was born from a love of timeless craftsmanship and modern femininity.</p>
    </section>

    <!-- Story -->
    <section class="about-story" id="about-story">
        <p class="section-label">✦ How It Began</p>
        <h2>A Passion, Crafted <em>with Purpose</em></h2>
        <p>Aurielle started in a small studio with a simple belief: every woman deserves jewelry that feels as special as she is. Our founder, driven by a love of artistry and elegance, began crafting necklaces by hand — each one a quiet celebration of the person who would wear it.</p>
        <p>From that first workshop, Aurielle has grown into a curated collection of fine necklaces — each thoughtfully designed, meticulously crafted, and made to last a lifetime. We work only with quality materials: sterling silver, gold-fill, and freshwater pearls that stand the test of time.</p>
        <p>Today, every Aurielle necklace still carries that original spirit: handmade with care, designed with love, and created to become a part of your story.</p>
        <a href="shop.php" class="btn btn-gold btn-lg" style="margin-top:1.5rem" id="aboutShopBtn">Shop the Collection</a>
    </section>

    <!-- Values -->
    <section class="about-values" id="about-values">
        <div class="about-values-title">
            <span class="section-label">✦ What We Stand For</span>
            <h2>Our <em style="color:var(--color-gold)">Values</em></h2>
        </div>
        <div class="about-values-grid container">
            <div class="about-value">
                <div class="about-value-icon">✦</div>
                <h3>Artisan Craftsmanship</h3>
                <p>Every piece is crafted by skilled artisan hands — no mass production, no shortcuts. Just genuine dedication to quality.</p>
            </div>
            <div class="about-value">
                <div class="about-value-icon">♡</div>
                <h3>Timeless Design</h3>
                <p>We design for longevity, not trends. Our necklaces are made to be worn for decades, passed down through generations.</p>
            </div>
            <div class="about-value">
                <div class="about-value-icon">◇</div>
                <h3>Conscious Materials</h3>
                <p>We source responsibly — using conflict-free gemstones, recycled metals, and sustainable packaging wherever possible.</p>
            </div>
        </div>
    </section>

    <!-- Promise -->
    <section class="about-promise" id="about-promise">
        <p class="section-label">✦ The Aurielle Promise</p>
        <h2>Our Commitment <em>to You</em></h2>
        <p>When you choose Aurielle, you're not just buying a necklace. You're investing in a piece made to last — and a brand that stands behind every single one.</p>
        <ul class="promise-list">
            <li><span>✦</span> Quality guarantee on all pieces — we'll replace any defect, no questions asked.</li>
            <li><span>✦</span> 30-day returns — if it's not perfect, we'll make it right.</li>
            <li><span>✦</span> Handwritten care notes included with every order.</li>
            <li><span>✦</span> Real people answering your messages — no bots, no scripts.</li>
        </ul>
    </section>
</main>

<?php require_once 'includes/footer.php'; ?>
