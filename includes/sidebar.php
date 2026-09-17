<?php
// includes/sidebar.php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <div class="sidebar-header">
    <div class="sidebar-logo-icon">PTP</div>
    <div class="sidebar-title-box">
      <h2>PTP BANTEN</h2>
      <p>HSSE MANAGEMENT</p>
    </div>
  </div>

  <nav class="sidebar-menu">
    <a href="index.php" class="menu-item <?= ($currentPage == 'index.php') ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
      <span>Dashboard</span>
    </a>

    <a href="data-kecelakaan.php" class="menu-item <?= ($currentPage == 'data-kecelakaan.php') ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
      <span>Data Kecelakaan</span>
    </a>

    <a href="tambah-data.php" class="menu-item <?= ($currentPage == 'tambah-data.php') ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="16"></line><line x1="8" y1="12" x2="16" y2="12"></line></svg>
      <span>Tambah Data</span>
    </a>

    <a href="statistik.php" class="menu-item <?= ($currentPage == 'statistik.php') ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
      <span>Statistik</span>
    </a>

    <a href="laporan.php" class="menu-item <?= ($currentPage == 'laporan.php') ? 'active' : '' ?>">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
      <span>Laporan</span>
    </a>
  </nav>

  <div class="sidebar-footer">
    <div class="sidebar-footer-info">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"></path><circle cx="12" cy="10" r="3"></circle></svg>
      <div>
        <strong>HSSE</strong>
        <span>PT Pelabuhan Tanjung Priok<br>Cabang Banten</span>
      </div>
    </div>
  </div>
</aside>
