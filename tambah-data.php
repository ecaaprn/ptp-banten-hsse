<?php
// tambah-data.php
require_once __DIR__ . '/config/database.php';

$pageTitle = "Tambah Data Kecelakaan";
$breadcrumb = "HSSE / TAMBAH DATA";

// Auto-generate Next Incident ID (e.g. INC-2026-033)
$lastIdStmt = $pdo->query("SELECT id_kejadian FROM kecelakaan ORDER BY id DESC LIMIT 1");
$lastId = $lastIdStmt->fetchColumn();
if ($lastId && preg_match('/INC-2026-(\d+)/', $lastId, $matches)) {
    $nextNum = intval($matches[1]) + 1;
} else {
    $nextNum = 1;
}
$autoId = "INC-2026-" . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kejadian = trim($_POST['id_kejadian']);
    $tanggal = trim($_POST['tanggal']);
    $waktu = trim($_POST['waktu']);
    $lokasi = trim($_POST['lokasi']);
    $jenis_kecelakaan = trim($_POST['jenis_kecelakaan']);
    $klasifikasi = trim($_POST['klasifikasi']);
    $nama_korban = trim($_POST['nama_korban']);
    $perusahaan = trim($_POST['perusahaan']);
    $status_korban = trim($_POST['status_korban']);
    $kronologi = trim($_POST['kronologi']);
    $penyebab_langsung = trim($_POST['penyebab_langsung']);
    $tindakan_korektif = trim($_POST['tindakan_korektif']);
    $status = trim($_POST['status']);

    if (!empty($id_kejadian) && !empty($tanggal) && !empty($lokasi) && !empty($jenis_kecelakaan) && !empty($klasifikasi)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO kecelakaan (
                id_kejadian, tanggal, waktu, lokasi, jenis_kecelakaan, klasifikasi,
                nama_korban, perusahaan, status_korban, kronologi, penyebab_langsung, tindakan_korektif, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

            $stmt->execute([
                $id_kejadian, $tanggal, $waktu, $lokasi, $jenis_kecelakaan, $klasifikasi,
                $nama_korban, $perusahaan, $status_korban, $kronologi, $penyebab_langsung, $tindakan_korektif, $status
            ]);

            header("Location: data-kecelakaan.php?msg=success");
            exit();
        } catch (PDOException $e) {
            $error = "Gagal menyimpan data: " . $e->getMessage();
        }
    } else {
        $error = "Harap isi seluruh kolom wajib (*)!";
    }
}

include __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 24px;">
  <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">MANAJEMEN INSIDEN / INPUT LAPORAN</div>
  <h1 style="font-size: 24px; font-weight: 800; color: #0B1E36;">Tambah Data Kecelakaan</h1>
  <p style="font-size: 13px; color: #64748B;">Lengkapi informasi kejadian untuk menghasilkan laporan investigasi awal.</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert-error" style="margin-bottom: 20px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form action="tambah-data.php" method="POST">
  <div class="form-card">
    <!-- Section 1: Informasi Kejadian -->
    <div class="form-section-title">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
      Informasi Kejadian
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="id_kejadian">ID Kejadian *</label>
        <input type="text" id="id_kejadian" name="id_kejadian" class="form-control" value="<?= htmlspecialchars($autoId) ?>" required readonly style="background-color:#E2E8F0; font-weight:700;">
      </div>

      <div class="form-group">
        <label for="tanggal">Tanggal *</label>
        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
      </div>

      <div class="form-group">
        <label for="waktu">Waktu *</label>
        <input type="time" id="waktu" name="waktu" class="form-control" value="09:00" required>
      </div>

      <div class="form-group">
        <label for="lokasi">Lokasi *</label>
        <input type="text" id="lokasi" name="lokasi" class="form-control" placeholder="Contoh: Dermaga 07, Gudang 03" required>
      </div>

      <div class="form-group">
        <label for="jenis_kecelakaan">Jenis Kecelakaan *</label>
        <select id="jenis_kecelakaan" name="jenis_kecelakaan" class="form-control" required>
          <option value="">Pilih jenis kecelakaan</option>
          <option value="Terjatuh/Tergelincir">Terjatuh/Tergelincir</option>
          <option value="Tertabrak">Tertabrak</option>
          <option value="Terguling">Terguling</option>
          <option value="Terjepit">Terjepit</option>
          <option value="Hampir tertabrak">Hampir tertabrak</option>
          <option value="Lainnya">Lainnya</option>
        </select>
      </div>

      <div class="form-group">
        <label for="klasifikasi">Klasifikasi Kecelakaan *</label>
        <select id="klasifikasi" name="klasifikasi" class="form-control" required>
          <option value="">Pilih klasifikasi</option>
          <option value="Fatality">Fatality</option>
          <option value="Lost Time Injury">Lost Time Injury (LTI)</option>
          <option value="Medical Treatment">Medical Treatment</option>
          <option value="First Aid">First Aid</option>
          <option value="Near Miss">Near Miss</option>
        </select>
      </div>
    </div>

    <!-- Section 2: Informasi Korban -->
    <div class="form-section-title" style="margin-top: 10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
      Informasi Korban
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="nama_korban">Nama / Jabatan Korban *</label>
        <input type="text" id="nama_korban" name="nama_korban" class="form-control" placeholder="Nama atau jabatan korban" required>
      </div>

      <div class="form-group">
        <label for="perusahaan">Nama Perusahaan *</label>
        <input type="text" id="perusahaan" name="perusahaan" class="form-control" placeholder="Contoh: PTP Banten / PT Logistik" required>
      </div>

      <div class="form-group">
        <label for="status_korban">Status Korban *</label>
        <select id="status_korban" name="status_korban" class="form-control" required>
          <option value="Karyawan">Karyawan</option>
          <option value="TKBM">TKBM</option>
          <option value="Mitra">Mitra / Subkon</option>
          <option value="Tamu">Tamu</option>
        </select>
      </div>
    </div>

    <!-- Section 3: Kronologi & Investigasi -->
    <div class="form-section-title" style="margin-top: 10px;">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
      Kronologi & Investigasi
    </div>

    <div class="form-group" style="margin-bottom: 20px;">
      <label for="kronologi">Kronologi Singkat *</label>
      <textarea id="kronologi" name="kronologi" class="form-control" placeholder="Jelaskan urutan kejadian secara ringkas dan objektif..." required></textarea>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="penyebab_langsung">Penyebab Langsung</label>
        <input type="text" id="penyebab_langsung" name="penyebab_langsung" class="form-control" placeholder="Contoh: Permukaan kerja licin, alat aus">
      </div>

      <div class="form-group">
        <label for="tindakan_korektif">Tindakan Korektif</label>
        <input type="text" id="tindakan_korektif" name="tindakan_korektif" class="form-control" placeholder="Rencana perbaikan K3">
      </div>

      <div class="form-group">
        <label for="status">Status Laporan *</label>
        <select id="status" name="status" class="form-control" required>
          <option value="On Progress">On Progress</option>
          <option value="Closed">Closed</option>
          <option value="Open">Open</option>
        </select>
      </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
      <a href="data-kecelakaan.php" class="btn btn-outline">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan Laporan</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
