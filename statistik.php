<?php
// statistik.php
require_once __DIR__ . '/config/database.php';

$pageTitle = "Statistik Kecelakaan";
$breadcrumb = "HSSE / STATISTIK";

include __DIR__ . '/includes/header.php';

// Fetch location statistics
$stmtLoc = $pdo->query("SELECT lokasi, COUNT(*) as jumlah FROM kecelakaan GROUP BY lokasi ORDER BY jumlah DESC");
$locationStats = $stmtLoc->fetchAll();

// Fetch classification counts
$fatalityCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Fatality'")->fetchColumn();
$ltiCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Lost Time Injury'")->fetchColumn();
$medicalCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Medical Treatment'")->fetchColumn();
$firstAidNearMissCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi IN ('First Aid', 'Near Miss')")->fetchColumn();
?>

<div style="margin-bottom: 24px;">
  <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">ANALITIK HSSE</div>
  <h1 style="font-size: 24px; font-weight: 800; color: #0B1E36;">Statistik Kecelakaan Kerja</h1>
  <p style="font-size: 13px; color: #64748B;">Ringkasan analitik kecelakaan kerja periode Januari–Desember 2026.</p>
</div>

<!-- Grid Row 1: Charts (Bulan & Jenis) -->
<div class="charts-grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 24px;">
  <!-- Statistik Berdasarkan Bulan -->
  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>STATISTIK BERDASARKAN BULAN</h3>
        <p>Tren kejadian per bulan di tahun 2026</p>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="chartTrenKecelakaan"></canvas>
    </div>
  </div>

  <!-- Statistik Berdasarkan Jenis -->
  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>STATISTIK BERDASARKAN JENIS</h3>
        <p>Distribusi jumlah laporan per jenis insiden</p>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="chartJenisHoriz"></canvas>
    </div>
  </div>
</div>

<!-- Grid Row 2: Lokasi & Klasifikasi Breakdown (Matching Canva Screenshot 4) -->
<div class="charts-grid" style="grid-template-columns: 1fr 1fr;">
  <!-- Statistik Berdasarkan Lokasi -->
  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>STATISTIK BERDASARKAN LOKASI</h3>
        <p>Jumlah insiden menurut area kerja pelabuhan</p>
      </div>
    </div>

    <div style="overflow-x: auto;">
      <table class="custom-table">
        <thead>
          <tr>
            <th>LOKASI AREA</th>
            <th style="text-align: right;">JUMLAH KEJADIAN</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($locationStats as $loc): ?>
            <tr>
              <td style="font-weight: 600; color: #1E293B;"><?= htmlspecialchars($loc['lokasi']) ?></td>
              <td style="text-align: right; font-weight: 800; color: #1890FF;"><?= $loc['jumlah'] ?> kejadian</td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Klasifikasi & Risk Matrix Cards -->
  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>KLASIFIKASI & TOTAL KERUGIAN</h3>
        <p>Ringkasan akumulasi tingkat keparahan risiko</p>
      </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
      <div style="background-color: var(--color-fatality-bg); border-left: 4px solid var(--color-fatality); padding: 18px; border-radius: 10px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--color-fatality); text-transform: uppercase;">Fatality</span>
        <div style="font-size: 28px; font-weight: 800; color: #1E293B; margin-top: 6px;"><?= $fatalityCount ?></div>
        <div style="font-size: 11px; color: #64748B;">Korban jiwa / fatal</div>
      </div>

      <div style="background-color: var(--color-lti-bg); border-left: 4px solid var(--color-lti); padding: 18px; border-radius: 10px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--color-lti); text-transform: uppercase;">Lost Time Injury (LTI)</span>
        <div style="font-size: 28px; font-weight: 800; color: #1E293B; margin-top: 6px;"><?= $ltiCount ?></div>
        <div style="font-size: 11px; color: #64748B;">Hari kerja hilang</div>
      </div>

      <div style="background-color: var(--color-medical-bg); border-left: 4px solid var(--color-medical); padding: 18px; border-radius: 10px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--color-medical); text-transform: uppercase;">Medical Treatment</span>
        <div style="font-size: 28px; font-weight: 800; color: #1E293B; margin-top: 6px;"><?= $medicalCount ?></div>
        <div style="font-size: 11px; color: #64748B;">Penanganan medis</div>
      </div>

      <div style="background-color: var(--color-firstaid-bg); border-left: 4px solid var(--color-firstaid); padding: 18px; border-radius: 10px;">
        <span style="font-size: 11px; font-weight: 700; color: var(--color-firstaid); text-transform: uppercase;">First Aid / Near Miss</span>
        <div style="font-size: 28px; font-weight: 800; color: #1E293B; margin-top: 6px;"><?= $firstAidNearMissCount ?></div>
        <div style="font-size: 11px; color: #64748B;">P3K & Hampir celaka</div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
