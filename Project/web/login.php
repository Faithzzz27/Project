<?php
require_once 'includes/auth.php';
if (isLoggedIn()) { header('Location: index.php'); exit; }

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if (!$email || !$password) {
        $error = 'Please fill in all fields.';
    } else {
        $result = loginUser($email, $password);
        if ($result['success']) {
            $redirect = $_GET['redirect'] ?? 'index.php';
            header('Location: ' . htmlspecialchars($redirect));
            exit;
        } else {
            $error = $result['message'];
        }
    }
}

$pageTitle       = 'Sign In';
$metaDescription = 'Sign in to your Aurielle account to manage your orders, wishlist and shopping bag.';
require_once 'includes/header.php';
?>

<main class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <span class="auth-logo-name">Aurielle</span>
            <span class="auth-logo-sub">Fine Necklaces</span>
        </div>

        <h1 class="auth-title">Welcome back</h1>
        <p class="auth-subtitle">Sign in to your account to continue</p>

        <?php if ($error): ?>
            <div class="flash error" role="alert">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="validate" id="loginForm" novalidate>
            <input type="hidden" name="csrf" value="login">
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
                       placeholder="••••••••" required autocomplete="current-password">
                <span class="form-error-msg"></span>
            </div>
            <button type="submit" class="btn btn-primary btn-full btn-lg" id="loginBtn">Sign In</button>
        </form>

        <div class="auth-divider"><span>or</span></div>
        <p class="auth-footer">Don't have an account? <a href="register.php">Create one</a></p>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>
