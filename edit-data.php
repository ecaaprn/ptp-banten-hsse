<?php
// edit-data.php
require_once __DIR__ . '/config/database.php';

$pageTitle = "Edit Data Kecelakaan";
$breadcrumb = "HSSE / EDIT DATA";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $pdo->prepare("SELECT * FROM kecelakaan WHERE id = ?");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    header("Location: data-kecelakaan.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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

    if (!empty($tanggal) && !empty($lokasi) && !empty($jenis_kecelakaan) && !empty($klasifikasi)) {
        try {
            $stmtUpdate = $pdo->prepare("UPDATE kecelakaan SET
                tanggal = ?, waktu = ?, lokasi = ?, jenis_kecelakaan = ?, klasifikasi = ?,
                nama_korban = ?, perusahaan = ?, status_korban = ?, kronologi = ?,
                penyebab_langsung = ?, tindakan_korektif = ?, status = ?
                WHERE id = ?");

            $stmtUpdate->execute([
                $tanggal, $waktu, $lokasi, $jenis_kecelakaan, $klasifikasi,
                $nama_korban, $perusahaan, $status_korban, $kronologi,
                $penyebab_langsung, $tindakan_korektif, $status, $id
            ]);

            header("Location: data-kecelakaan.php?msg=updated");
            exit();
        } catch (PDOException $e) {
            $error = "Gagal memperbarui data: " . $e->getMessage();
        }
    } else {
        $error = "Harap isi seluruh kolom wajib (*)!";
    }
}

include __DIR__ . '/includes/header.php';
?>

<div style="margin-bottom: 24px;">
  <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">MANAJEMEN INSIDEN / EDIT LAPORAN</div>
  <h1 style="font-size: 24px; font-weight: 800; color: #0B1E36;">Edit Data Kecelakaan (<?= htmlspecialchars($data['id_kejadian']) ?>)</h1>
  <p style="font-size: 13px; color: #64748B;">Perbarui informasi investigasi dan status insiden kecelakaan.</p>
</div>

<?php if (!empty($error)): ?>
  <div class="alert-error" style="margin-bottom: 20px;">
    <?= htmlspecialchars($error) ?>
  </div>
<?php endif; ?>

<form action="edit-data.php?id=<?= $id ?>" method="POST">
  <div class="form-card">
    <div class="form-section-title">
      Informasi Kejadian
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="id_kejadian">ID Kejadian</label>
        <input type="text" id="id_kejadian" class="form-control" value="<?= htmlspecialchars($data['id_kejadian']) ?>" readonly style="background-color:#E2E8F0; font-weight:700;">
      </div>

      <div class="form-group">
        <label for="tanggal">Tanggal *</label>
        <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
      </div>

      <div class="form-group">
        <label for="waktu">Waktu *</label>
        <input type="time" id="waktu" name="waktu" class="form-control" value="<?= htmlspecialchars($data['waktu']) ?>" required>
      </div>

      <div class="form-group">
        <label for="lokasi">Lokasi *</label>
        <input type="text" id="lokasi" name="lokasi" class="form-control" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
      </div>

      <div class="form-group">
        <label for="jenis_kecelakaan">Jenis Kecelakaan *</label>
        <select id="jenis_kecelakaan" name="jenis_kecelakaan" class="form-control" required>
          <?php
          $jenisOptions = ['Terjatuh/Tergelincir', 'Tertabrak', 'Terguling', 'Terjepit', 'Hampir tertabrak', 'Lainnya'];
          foreach ($jenisOptions as $j):
          ?>
            <option value="<?= $j ?>" <?= ($data['jenis_kecelakaan'] == $j) ? 'selected' : '' ?>><?= $j ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="klasifikasi">Klasifikasi Kecelakaan *</label>
        <select id="klasifikasi" name="klasifikasi" class="form-control" required>
          <?php
          $klasOptions = ['Fatality', 'Lost Time Injury', 'Medical Treatment', 'First Aid', 'Near Miss'];
          foreach ($klasOptions as $k):
          ?>
            <option value="<?= $k ?>" <?= ($data['klasifikasi'] == $k) ? 'selected' : '' ?>><?= $k ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-section-title" style="margin-top: 10px;">
      Informasi Korban
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="nama_korban">Nama / Jabatan Korban *</label>
        <input type="text" id="nama_korban" name="nama_korban" class="form-control" value="<?= htmlspecialchars($data['nama_korban']) ?>" required>
      </div>

      <div class="form-group">
        <label for="perusahaan">Nama Perusahaan *</label>
        <input type="text" id="perusahaan" name="perusahaan" class="form-control" value="<?= htmlspecialchars($data['perusahaan']) ?>" required>
      </div>

      <div class="form-group">
        <label for="status_korban">Status Korban *</label>
        <select id="status_korban" name="status_korban" class="form-control" required>
          <?php
          $skOptions = ['Karyawan', 'TKBM', 'Mitra', 'Tamu'];
          foreach ($skOptions as $sk):
          ?>
            <option value="<?= $sk ?>" <?= ($data['status_korban'] == $sk) ? 'selected' : '' ?>><?= $sk ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="form-section-title" style="margin-top: 10px;">
      Kronologi & Investigasi
    </div>

    <div class="form-group" style="margin-bottom: 20px;">
      <label for="kronologi">Kronologi Singkat *</label>
      <textarea id="kronologi" name="kronologi" class="form-control" required><?= htmlspecialchars($data['kronologi']) ?></textarea>
    </div>

    <div class="form-grid">
      <div class="form-group">
        <label for="penyebab_langsung">Penyebab Langsung</label>
        <input type="text" id="penyebab_langsung" name="penyebab_langsung" class="form-control" value="<?= htmlspecialchars($data['penyebab_langsung']) ?>">
      </div>

      <div class="form-group">
        <label for="tindakan_korektif">Tindakan Korektif</label>
        <input type="text" id="tindakan_korektif" name="tindakan_korektif" class="form-control" value="<?= htmlspecialchars($data['tindakan_korektif']) ?>">
      </div>

      <div class="form-group">
        <label for="status">Status Laporan *</label>
        <select id="status" name="status" class="form-control" required>
          <option value="On Progress" <?= ($data['status'] == 'On Progress') ? 'selected' : '' ?>>On Progress</option>
          <option value="Closed" <?= ($data['status'] == 'Closed') ? 'selected' : '' ?>>Closed</option>
          <option value="Open" <?= ($data['status'] == 'Open') ? 'selected' : '' ?>>Open</option>
        </select>
      </div>
    </div>

    <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
      <a href="data-kecelakaan.php" class="btn btn-outline">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
  </div>
</form>

<?php include __DIR__ . '/includes/footer.php'; ?>
