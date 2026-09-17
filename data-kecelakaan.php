<?php
// data-kecelakaan.php
require_once __DIR__ . '/config/database.php';

$pageTitle = "Data Kecelakaan";
$breadcrumb = "HSSE / DATA KECELAKAAN";

include __DIR__ . '/includes/header.php';

// Filter params
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
$klasifikasi = isset($_GET['klasifikasi']) ? $_GET['klasifikasi'] : '';
$lokasi = isset($_GET['lokasi']) ? $_GET['lokasi'] : '';

// Build Query
$sql = "SELECT * FROM kecelakaan WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (id_kejadian LIKE ? OR lokasi LIKE ? OR perusahaan LIKE ? OR nama_korban LIKE ? OR jenis_kecelakaan LIKE ?)";
    $term = "%{$search}%";
    $params = array_merge($params, [$term, $term, $term, $term, $term]);
}

if (!empty($tahun)) {
    $sql .= " AND strftime('%Y', tanggal) = ?";
    $params[] = $tahun;
}

if (!empty($klasifikasi)) {
    $sql .= " AND klasifikasi = ?";
    $params[] = $klasifikasi;
}

if (!empty($lokasi)) {
    $sql .= " AND lokasi = ?";
    $params[] = $lokasi;
}

$sql .= " ORDER BY tanggal DESC, id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$kecelakaanList = $stmt->fetchAll();

// Get unique locations & classifications for dropdown filter
$lokasiList = $pdo->query("SELECT DISTINCT lokasi FROM kecelakaan ORDER BY lokasi")->fetchAll(PDO::FETCH_COLUMN);
$klasifikasiList = $pdo->query("SELECT DISTINCT klasifikasi FROM kecelakaan ORDER BY klasifikasi")->fetchAll(PDO::FETCH_COLUMN);
?>

<div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;">
  <div>
    <div style="font-size: 11px; font-weight: 700; color: #1890FF; text-transform: uppercase; letter-spacing: 0.5px;">MANAJEMEN INSIDEN</div>
    <h1 style="font-size: 24px; font-weight: 800; color: #0B1E36;">Data Kecelakaan Kerja</h1>
    <p style="font-size: 13px; color: #64748B;">Kelola, cari, dan pantau seluruh laporan kecelakaan kerja.</p>
  </div>

  <a href="tambah-data.php" class="btn btn-primary">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Tambah Data
  </a>
</div>

<!-- Table Container -->
<div class="table-card">
  <!-- Filter Control Bar -->
  <form method="GET" action="data-kecelakaan.php" class="filter-bar">
    <div class="filter-search-box">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
      <input type="text" id="tableSearchInput" name="search" class="form-control" placeholder="Cari lokasi, perusahaan, korban, atau ID..." value="<?= htmlspecialchars($search) ?>">
    </div>

    <div class="form-group" style="margin: 0; min-width: 130px;">
      <select name="tahun" class="form-control" onchange="this.form.submit()">
        <option value="">Semua Tahun</option>
        <option value="2026" <?= ($tahun == '2026') ? 'selected' : '' ?>>2026</option>
        <option value="2025" <?= ($tahun == '2025') ? 'selected' : '' ?>>2025</option>
      </select>
    </div>

    <div class="form-group" style="margin: 0; min-width: 140px;">
      <select name="lokasi" class="form-control" onchange="this.form.submit()">
        <option value="">Semua Lokasi</option>
        <?php foreach ($lokasiList as $l): ?>
          <option value="<?= htmlspecialchars($l) ?>" <?= ($lokasi == $l) ? 'selected' : '' ?>><?= htmlspecialchars($l) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group" style="margin: 0; min-width: 160px;">
      <select name="klasifikasi" class="form-control" onchange="this.form.submit()">
        <option value="">Semua Klasifikasi</option>
        <?php foreach ($klasifikasiList as $k): ?>
          <option value="<?= htmlspecialchars($k) ?>" <?= ($klasifikasi == $k) ? 'selected' : '' ?>><?= htmlspecialchars($k) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <?php if (!empty($search) || !empty($tahun) || !empty($lokasi) || !empty($klasifikasi)): ?>
      <a href="data-kecelakaan.php" class="btn btn-outline btn-sm">Reset Filter</a>
    <?php endif; ?>
  </form>

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
          <th>AKSI</th>
        </tr>
      </thead>
      <tbody id="kecelakaanTableBody">
        <?php if (count($kecelakaanList) > 0): ?>
          <?php foreach ($kecelakaanList as $row): ?>
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
              <td><?= date('d A' === 'd A' ? 'd M Y' : 'd-m-Y', strtotime($row['tanggal'])) ?></td>
              <td><?= htmlspecialchars($row['lokasi']) ?></td>
              <td><?= htmlspecialchars($row['jenis_kecelakaan']) ?></td>
              <td>
                <span class="badge-status <?= $klasClass ?>">
                  <?= htmlspecialchars($row['klasifikasi']) ?>
                </span>
              </td>
              <td><?= htmlspecialchars($row['perusahaan']) ?></td>
              <td><?= htmlspecialchars($row['nama_korban']) ?></td>
              <td>
                <span class="badge-state <?= $statusState ?>">
                  <?= htmlspecialchars($row['status']) ?>
                </span>
              </td>
              <td>
                <div style="display: flex; gap: 6px;">
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
                  <a href="edit-data.php?id=<?= $row['id'] ?>" class="btn btn-outline btn-sm" style="color: #0284C7;" title="Edit">
                    Edit
                  </a>
                  <a href="hapus-data.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data insiden ini?')" title="Hapus">
                    &times;
                  </a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="9" style="text-align: center; padding: 40px; color: #64748B;">
              Tidak ada data kecelakaan kerja yang ditemukan.
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal Detail -->
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
      <strong style="display:block; margin-bottom:4px; color:#475569;">Kronologi Singkat:</strong>
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
