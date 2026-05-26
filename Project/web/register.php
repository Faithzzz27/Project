<?php
require_once 'includes/auth.php';
if (isLoggedIn()) { header('Location: index.php'); exit; }

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if (!$name || !$email || !$password || !$confirm) {
        $error = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $result = registerUser($name, $email, $password);
        if ($result['success']) {
            header('Location: index.php');
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$pageTitle       = 'Create Account';
$metaDescription = 'Create your free Aurielle account and enjoy wishlist, order history, and exclusive member offers.';
require_once 'includes/header.php';
?>

<main class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="auth-logo-name">Aurielle</span>
            <span class="auth-logo-sub">Fine Necklaces</span>
        </div>

        <h1 class="auth-title">Create account</h1>
        <p class="auth-subtitle">Join Aurielle for exclusive access and offers</p>

        <?php if ($error): ?>
            <div class="flash error" role="alert">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="validate" id="registerForm" novalidate>
            <div class="form-group">
                <label class="form-label" for="name">Full name</label>
                <input class="form-input" type="text" id="name" name="name"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                       placeholder="Your full name" required autocomplete="name">
                <span class="form-error-msg"></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="email">Email address</label>
                <input class="form-input" type="email" id="email" name="email"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       placeholder="you@example.com" required autocomplete="email">
                <span class="form-error-msg"></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input class="form-input" type="password" id="password" name="password"
                       placeholder="At least 6 characters" required autocomplete="new-password" minlength="6">
                <span class="form-hint">Minimum 6 characters</span>
                <span class="form-error-msg"></span>
            </div>
            <div class="form-group">
                <label class="form-label" for="confirm">Confirm password</label>
                <input class="form-input" type="password" id="confirm" name="confirm"
                       placeholder="Repeat your password" required autocomplete="new-password">
                <span class="form-error-msg"></span>
            </div>
            <button type="submit" class="btn btn-gold btn-full btn-lg" id="registerBtn">Create Account</button>
        </form>

        <div class="auth-divider"><span>or</span></div>
        <p class="auth-footer">Already have an account? <a href="login.php">Sign in</a></p>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
