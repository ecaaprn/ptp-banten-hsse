<?php
// index.php - Dashboard
require_once __DIR__ . '/config/database.php';

$pageTitle = "Dashboard";
$breadcrumb = "HSSE / DASHBOARD";

include __DIR__ . '/includes/header.php';

// Calculate KPI Metrics
$totalKejadian = $pdo->query("SELECT COUNT(*) FROM kecelakaan")->fetchColumn();
$fatalityCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Fatality'")->fetchColumn();
$ltiCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Lost Time Injury'")->fetchColumn();
$medicalCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Medical Treatment'")->fetchColumn();
$firstAidCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'First Aid'")->fetchColumn();
$nearMissCount = $pdo->query("SELECT COUNT(*) FROM kecelakaan WHERE klasifikasi = 'Near Miss'")->fetchColumn();

// Fetch 5 Recent Incidents
$stmtRecent = $pdo->query("SELECT * FROM kecelakaan ORDER BY tanggal DESC, id DESC LIMIT 5");
$recentIncidents = $stmtRecent->fetchAll();
?>

<div class="page-header-title">
  <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">PTP BANTEN</div>
  <h1>Database Kecelakaan Kerja</h1>
  <p>Monitoring dan Analisis Kecelakaan Kerja</p>
</div>

<!-- KPI Cards Section (Matching Canva Screenshot 1) -->
<div class="kpi-cards-grid">
  <!-- Total Kejadian -->
  <div class="kpi-card total">
    <div class="kpi-card-header">
      <span class="kpi-title">Total Kejadian</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $totalKejadian ?></div>
    <div class="kpi-sub">Tahun berjalan</div>
  </div>

  <!-- Fatality -->
  <div class="kpi-card fatality">
    <div class="kpi-card-header">
      <span class="kpi-title">Fatality</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $fatalityCount ?></div>
    <div class="kpi-sub">Perlu perhatian</div>
  </div>

  <!-- Lost Time Injury -->
  <div class="kpi-card lti">
    <div class="kpi-card-header">
      <span class="kpi-title">Lost Time Injury</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $ltiCount ?></div>
    <div class="kpi-sub"><?= round(($ltiCount / max(1, $totalKejadian)) * 100, 1) ?>% kejadian</div>
  </div>

  <!-- Medical Treatment -->
  <div class="kpi-card medical">
    <div class="kpi-card-header">
      <span class="kpi-title">Medical Treatment</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 12h-4l-3 9L9 3l-3 9H2"></path></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $medicalCount ?></div>
    <div class="kpi-sub"><?= round(($medicalCount / max(1, $totalKejadian)) * 100, 1) ?>% kejadian</div>
  </div>

  <!-- First Aid -->
  <div class="kpi-card firstaid">
    <div class="kpi-card-header">
      <span class="kpi-title">First Aid</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $firstAidCount ?></div>
    <div class="kpi-sub"><?= round(($firstAidCount / max(1, $totalKejadian)) * 100, 1) ?>% kejadian</div>
  </div>

  <!-- Near Miss -->
  <div class="kpi-card nearmiss">
    <div class="kpi-card-header">
      <span class="kpi-title">Near Miss</span>
      <div class="kpi-icon-box">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
      </div>
    </div>
    <div class="kpi-value"><?= $nearMissCount ?></div>
    <div class="kpi-sub">Dilaporkan tim</div>
  </div>
</div>

<!-- Charts Section -->
<div class="charts-grid">
  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>Grafik Tren Kecelakaan</h3>
        <p>Jumlah laporan kejadian per bulan</p>
      </div>
      <span class="badge-year">2026</span>
    </div>
    <div class="chart-container">
      <canvas id="chartTrenKecelakaan"></canvas>
    </div>
  </div>

  <div class="chart-card">
    <div class="chart-header">
      <div class="chart-title-box">
        <h3>Grafik Jenis Kecelakaan</h3>
        <p>Distribusi laporan tahun berjalan</p>
      </div>
    </div>
    <div class="chart-container">
      <canvas id="chartJenisKecelakaan"></canvas>
    </div>
  </div>
</div>

<!-- Recent Data Table -->
<div class="table-card">
  <div class="table-card-header">
    <div class="table-card-title">
      <h3>Kejadian Terbaru</h3>
      <p>Laporan insiden keselamatan kerja terkini</p>
    </div>
    <a href="data-kecelakaan.php" class="btn btn-primary btn-sm">
      Lihat Semua Data
    </a>
  </div>

  <div style="overflow-x: auto;">
    <table class="custom-table">
      <thead>
        <tr>
          <th>ID KEJADIAN</th>
          <th>TANGGAL</th>
          <th>LOKASI</th>
          <th>JENIS KECELAKAAN</th>
          <th>KLASIFIKASI</th>
          <th>STATUS</th>
          <th>AKSI</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($recentIncidents as $row): ?>
          <?php
            $klasClass = 'nearmiss';
            $klas = strtolower($row['klasifikasi']);
            if (strpos($klas, 'fatality') !== false) $klasClass = 'fatality';
            elseif (strpos($klas, 'lost time') !== false || strpos($klas, 'lti') !== false) $klasClass = 'lti';
            elseif (strpos($klas, 'medical') !== false) $klasClass = 'medical';
            elseif (strpos($klas, 'first aid') !== false) $klasClass = 'firstaid';

            $statusState = 'on-progress';
            $st = strtolower($row['status']);
            if ($st == 'closed') $statusState = 'closed';
            elseif ($st == 'open') $statusState = 'open';
          ?>
          <tr>
            <td style="font-weight: 700; color: #1890FF;"><?= htmlspecialchars($row['id_kejadian']) ?></td>
            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td><?= htmlspecialchars($row['jenis_kecelakaan']) ?></td>
            <td>
              <span class="badge-status <?= $klasClass ?>">
                <?= htmlspecialchars($row['klasifikasi']) ?>
              </span>
            </td>
            <td>
              <span class="badge-state <?= $statusState ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
            </td>
            <td>
              <button class="btn btn-outline btn-sm" onclick="showDetailModal(
                '<?= $row['id'] ?>',
                '<?= htmlspecialchars($row['id_kejadian'], ENT_QUOTES) ?>',
                '<?= date('d F Y', strtotime($row['tanggal'])) ?>',
                '<?= htmlspecialchars($row['lokasi'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['jenis_kecelakaan'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['klasifikasi'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['perusahaan'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['nama_korban'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['status'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['kronologi'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['penyebab_langsung'], ENT_QUOTES) ?>',
                '<?= htmlspecialchars($row['tindakan_korektif'], ENT_QUOTES) ?>'
              )">
                Detail
              </button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="modal-overlay">
  <div class="modal-container">
    <div class="modal-header">
      <h3 style="font-size: 16px; font-weight: 800; color: #0B1E36;">Detail Kecelakaan Kerja</h3>
      <button class="modal-close" onclick="closeModal()">&times;</button>
    </div>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; font-size: 13px; margin-bottom: 20px;">
      <div><strong>ID Kejadian:</strong> <span id="modalIdKejadian" style="color:#1890FF; font-weight:700;"></span></div>
      <div><strong>Tanggal:</strong> <span id="modalTanggal"></span></div>
      <div><strong>Lokasi:</strong> <span id="modalLokasi"></span></div>
      <div><strong>Jenis Kecelakaan:</strong> <span id="modalJenis"></span></div>
      <div><strong>Klasifikasi:</strong> <span id="modalKlasifikasi"></span></div>
      <div><strong>Perusahaan:</strong> <span id="modalPerusahaan"></span></div>
      <div><strong>Korban / Jabatan:</strong> <span id="modalKorban"></span></div>
      <div><strong>Status:</strong> <span id="modalStatus"></span></div>
    </div>
    <div style="background-color: #F8FAFC; padding: 14px; border-radius: 8px; font-size: 12px; margin-bottom: 12px;">
      <strong style="display:block; margin-bottom:4px; color:#475569;">Kronologi:</strong>
      <p id="modalKronologi" style="color:#334155; line-height: 1.5;"></p>
    </div>
    <div style="background-color: #F8FAFC; padding: 14px; border-radius: 8px; font-size: 12px; margin-bottom: 12px;">
      <strong style="display:block; margin-bottom:4px; color:#475569;">Penyebab Langsung:</strong>
      <p id="modalPenyebab" style="color:#334155; line-height: 1.5;"></p>
    </div>
    <div style="background-color: #F8FAFC; padding: 14px; border-radius: 8px; font-size: 12px; margin-bottom: 20px;">
      <strong style="display:block; margin-bottom:4px; color:#475569;">Tindakan Korektif:</strong>
      <p id="modalTindakan" style="color:#334155; line-height: 1.5;"></p>
    </div>
    <div style="text-align: right;">
      <button class="btn btn-outline" onclick="closeModal()">Tutup</button>
    </div>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
