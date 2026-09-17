<?php
// logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$_SESSION = [];
session_unset();
session_destroy();

// Clear cookie
if (isset($_COOKIE['ptp_auth_session'])) {
    setcookie('ptp_auth_session', '', time() - 3600, '/');
}

header("Location: login.php");
exit();

