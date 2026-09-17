<?php
// hapus-data.php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
requireLogin();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM kecelakaan WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: data-kecelakaan.php?msg=deleted");
exit();
