<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

define('PTP_AUTH_SECRET', 'ptp_banten_hsse_secure_salt_2026');

// Auto-restore session from cookie if running in serverless / Vercel
if (empty($_SESSION['user_id']) && !empty($_COOKIE['ptp_auth_session'])) {
    $payload = @json_decode(base64_decode($_COOKIE['ptp_auth_session']), true);
    if ($payload && isset($payload['username'], $payload['sign'])) {
        $expectedSign = hash_hmac('sha256', $payload['id'] . '|' . $payload['username'], PTP_AUTH_SECRET);
        if (hash_equals($expectedSign, $payload['sign'])) {
            $_SESSION['user_id'] = $payload['id'];
            $_SESSION['username'] = $payload['username'];
            $_SESSION['nama_lengkap'] = $payload['nama_lengkap'];
            $_SESSION['role'] = $payload['role'];
        }
    }
}

function isLoggedIn() {
    return !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function currentUser() {
    return [
        'id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1,
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : 'admin',
        'nama' => isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Administrator HSSE',
        'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'Admin HSSE'
    ];
}

