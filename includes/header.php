<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
requireLogin();
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($pageTitle) ? $pageTitle . ' - ' : '' ?>Database Kecelakaan Kerja | PTP Banten HSSE</title>
  
  <!-- CSS Styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  
  <!-- Chart.js CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="app-container">
    <?php include __DIR__ . '/sidebar.php'; ?>
    
    <div class="main-wrapper">
      <!-- Topbar Header -->
      <header class="topbar">
        <div class="breadcrumb-path">
          <?= isset($breadcrumb) ? $breadcrumb : 'HSSE / DASHBOARD' ?>
        </div>
        
        <div class="topbar-right">
          <div class="topbar-date-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            <span>18 Agustus 2026</span>
          </div>

          <div class="user-profile-badge">
            <div class="avatar-circle">
              <?= strtoupper(substr($user['nama'], 0, 1)) ?>
            </div>
            <div class="user-info-text">
              <div class="name"><?= htmlspecialchars($user['nama']) ?></div>
              <div class="role"><?= htmlspecialchars($user['role']) ?></div>
            </div>
          </div>

          <a href="logout.php" class="btn-logout" title="Keluar">
            Keluar
          </a>
        </div>
      </header>

      <main class="content-body">
