<?php
require_once 'includes/auth.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$subject || !$message) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        // In a real app, send email here. For now, just show success.
        $success = "Thank you, {$name}! We've received your message and will get back to you within 24 hours.";
    }
}

$pageTitle       = 'Contact Us';
$metaDescription = 'Get in touch with Aurielle. We\'d love to hear from you — questions, feedback, or just to say hello.';
require_once 'includes/header.php';
?>

<main>
    <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="index.php">Home</a><span>›</span> Contact
    </nav>

    <div class="contact-page">
        <h1>Get in <em>Touch</em></h1>
        <p class="page-sub">We love hearing from you. Send us a message and we'll respond within 24 hours.</p>

        <!-- Form -->
        <div>
            <?php if ($success): ?>
                <div class="flash success" id="contactSuccess">✓ <?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="flash error" id="contactError">⚠ <?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" class="validate" id="contactForm" novalidate>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="contact-name">Your name</label>
                        <input class="form-input" type="text" id="contact-name" name="name"
                               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" placeholder="Full name" required>
                        <span class="form-error-msg"></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="contact-email">Email address</label>
                        <input class="form-input" type="email" id="contact-email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="you@example.com" required>
                        <span class="form-error-msg"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact-subject">Subject</label>
                    <input class="form-input" type="text" id="contact-subject" name="subject"
                           value="<?= htmlspecialchars($_POST['subject'] ?? '') ?>" placeholder="How can we help?" required>
                    <span class="form-error-msg"></span>
                </div>
                <div class="form-group">
                    <label class="form-label" for="contact-message">Message</label>
                    <textarea class="form-textarea" id="contact-message" name="message" placeholder="Tell us more…" required><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                    <span class="form-error-msg"></span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg" id="contactSubmitBtn">Send Message</button>
            </form>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
