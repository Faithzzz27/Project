    <!-- Site Footer -->
    <footer class="site-footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h2 class="footer-logo">Aurielle</h2>
                <p class="footer-tagline">Fine Necklaces &amp; Timeless Beauty</p>
                <p class="footer-about">Handcrafted with passion, every Aurielle necklace tells a story. We believe jewelry should be as unique as the person who wears it.</p>
            </div>

            <div class="footer-links">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="shop.php">Shop All</a></li>
                    <li><a href="about.php">Our Story</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h3>Customer Care</h3>
                <ul>
                    <li><a href="contact.php">Help &amp; FAQ</a></li>
                    <li><a href="#">Shipping &amp; Returns</a></li>
                    <li><a href="#">Size Guide</a></li>
                    <li><a href="#">Jewelry Care</a></li>
                </ul>
            </div>

            <div class="footer-newsletter">
                <h3>Stay in the Loop</h3>
                <p>Get early access to new arrivals, exclusive offers, and styling tips.</p>
                <form class="newsletter-form" id="footerNewsletter" onsubmit="handleNewsletter(event)">
                    <input type="email" placeholder="Your email address" required aria-label="Email for newsletter">
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Aurielle Fine Necklaces. All rights reserved.</p>
            <div class="footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </footer>

    <script src="app.js"></script>
</body>
</html>
