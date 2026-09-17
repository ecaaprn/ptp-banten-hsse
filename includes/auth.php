<?php
// includes/auth.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function currentUser() {
    return [
        'id' => isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null,
        'username' => isset($_SESSION['username']) ? $_SESSION['username'] : 'admin',
        'nama' => isset($_SESSION['nama_lengkap']) ? $_SESSION['nama_lengkap'] : 'Administrator HSSE',
        'role' => isset($_SESSION['role']) ? $_SESSION['role'] : 'Admin HSSE'
    ];
}
