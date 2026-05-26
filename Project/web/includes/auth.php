<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('USERS_FILE', __DIR__ . '/../data/users.json');

function getUsers(): array {
    if (!file_exists(USERS_FILE)) return [];
    $json = file_get_contents(USERS_FILE);
    return json_decode($json, true) ?? [];
}

function saveUsers(array $users): void {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT));
}

function registerUser(string $name, string $email, string $password): array {
    $users = getUsers();
    foreach ($users as $u) {
        if (strtolower($u['email']) === strtolower($email)) {
            return ['success' => false, 'message' => 'An account with this email already exists.'];
        }
    }
    $user = [
        'id'         => uniqid('usr_', true),
        'name'       => trim($name),
        'email'      => strtolower(trim($email)),
        'password'   => password_hash($password, PASSWORD_DEFAULT),
        'created_at' => date('Y-m-d H:i:s'),
        'wishlist'   => [],
    ];
    $users[] = $user;
    saveUsers($users);
    // Auto-login
    $_SESSION['user'] = [
        'id'    => $user['id'],
        'name'  => $user['name'],
        'email' => $user['email'],
    ];
    return ['success' => true, 'message' => 'Account created successfully!'];
}

function loginUser(string $email, string $password): array {
    $users = getUsers();
    foreach ($users as $user) {
        if (strtolower($user['email']) === strtolower(trim($email))) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'email' => $user['email'],
                ];
                return ['success' => true, 'message' => 'Welcome back, ' . $user['name'] . '!'];
            } else {
                return ['success' => false, 'message' => 'Incorrect password. Please try again.'];
            }
        }
    }
    return ['success' => false, 'message' => 'No account found with that email address.'];
}

function logoutUser(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function getCurrentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function getUserWishlist(): array {
    if (!isLoggedIn()) return [];
    $users = getUsers();
    foreach ($users as $u) {
        if ($u['id'] === $_SESSION['user']['id']) {
            return $u['wishlist'] ?? [];
        }
    }
    return [];
}

function toggleWishlist(int $productId): bool {
    if (!isLoggedIn()) return false;
    $users = getUsers();
    foreach ($users as &$u) {
        if ($u['id'] === $_SESSION['user']['id']) {
            $wishlist = $u['wishlist'] ?? [];
            if (in_array($productId, $wishlist)) {
                $wishlist = array_values(array_filter($wishlist, fn($id) => $id !== $productId));
                $added = false;
            } else {
                $wishlist[] = $productId;
                $added = true;
            }
            $u['wishlist'] = $wishlist;
            saveUsers($users);
            return $added;
        }
    }
    return false;
}

function isInWishlist(int $productId): bool {
    return in_array($productId, getUserWishlist());
}
