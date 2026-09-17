<?php
// laporan.php
require_once __DIR__ . '/config/database.php';

$pageTitle = "Laporan HSSE";
$breadcrumb = "HSSE / LAPORAN";

// CSV Export Handler
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=Laporan_Kecelakaan_Kerja_PTP_Banten_2026.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID Kejadian', 'Tanggal', 'Waktu', 'Lokasi', 'Jenis Kecelakaan', 'Klasifikasi', 'Korban', 'Perusahaan', 'Status Korban', 'Status Laporan']);

    $stmt = $pdo->query("SELECT id_kejadian, tanggal, waktu, lokasi, jenis_kecelakaan, klasifikasi, nama_korban, perusahaan, status_korban, status FROM kecelakaan ORDER BY tanggal DESC");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

include __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 24px;" class="no-print">
  <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">DOKUMEN & LAPORAN</div>
  <h1 style="font-size: 24px; font-weight: 800; color: #0B1E36;">Laporan HSSE</h1>
  <p style="font-size: 13px; color: #64748B;">Akses laporan ringkas untuk kebutuhan monitoring manajemen.</p>
</div>

<!-- Main Document Card (Matching Canva Screenshot 3) -->
<div class="form-card no-print" style="max-width: 680px;">
  <div style="display: flex; gap: 20px; align-items: flex-start;">
    <div style="width: 54px; height: 54px; background: #E6F7FF; color: #1890FF; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
    </div>

    <div style="flex: 1;">
      <h3 style="font-size: 16px; font-weight: 800; color: #0B1E36; margin-bottom: 6px;">
        Laporan Monitoring Kecelakaan Kerja 2026
      </h3>
      <p style="font-size: 13px; color: #64748B; line-height: 1.6; margin-bottom: 20px;">
        Ringkasan data kecelakaan, status tindak lanjut, dan statistik HSSE sampai dengan periode Agustus 2026.
      </p>

      <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <button onclick="window.print()" class="btn btn-primary">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
          Cetak / Cetak PDF
        </button>

        <a href="laporan.php?export=csv" class="btn btn-outline">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
          Export CSV / Excel
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Printable Preview Area -->
<div class="table-card print-only-area" style="margin-top: 30px;">
  <div class="table-card-header" style="flex-direction: column; align-items: flex-start;">
    <h2 style="font-size: 18px; font-weight: 800; color: #0B1E36;">PT PELABUHAN TANJUNG PRIOK CABANG BANTEN</h2>
    <h3 style="font-size: 14px; font-weight: 700; color: #64748B;">REKAPITULASI DOKUMEN LAPORAN KECELAKAAN KERJA (HSSE 2026)</h3>
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
          <th>PERUSAHAAN</th>
          <th>KORBAN / JABATAN</th>
          <th>STATUS</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $stmtAll = $pdo->query("SELECT * FROM kecelakaan ORDER BY tanggal DESC");
        while ($row = $stmtAll->fetch()):
        ?>
          <tr>
            <td style="font-weight: 700; color: #1890FF;"><?= htmlspecialchars($row['id_kejadian']) ?></td>
            <td><?= date('d M Y', strtotime($row['tanggal'])) ?></td>
            <td><?= htmlspecialchars($row['lokasi']) ?></td>
            <td><?= htmlspecialchars($row['jenis_kecelakaan']) ?></td>
            <td><?= htmlspecialchars($row['klasifikasi']) ?></td>
            <td><?= htmlspecialchars($row['perusahaan']) ?></td>
            <td><?= htmlspecialchars($row['nama_korban']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<style>
@media print {
  body { background: #FFFFFF; }
  .sidebar, .topbar, .no-print { display: none !important; }
  .main-wrapper { margin-left: 0 !important; }
  .content-body { padding: 0 !important; }
  .table-card { border: none !important; box-shadow: none !important; }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
