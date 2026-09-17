<?php
// config/database.php
// SQLite PDO Automatic database initializer & connection

$db_file = __DIR__ . '/../database.sqlite';

try {
    $pdo = new PDO("sqlite:" . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        nama_lengkap TEXT NOT NULL,
        role TEXT DEFAULT 'Admin HSSE',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create kecelakaan table
    $pdo->exec("CREATE TABLE IF NOT EXISTS kecelakaan (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        id_kejadian TEXT UNIQUE NOT NULL,
        tanggal DATE NOT NULL,
        waktu TIME DEFAULT '08:00',
        lokasi TEXT NOT NULL,
        jenis_kecelakaan TEXT NOT NULL,
        klasifikasi TEXT NOT NULL,
        nama_korban TEXT NOT NULL,
        perusahaan TEXT NOT NULL,
        status_korban TEXT NOT NULL,
        kronologi TEXT,
        penyebab_langsung TEXT,
        tindakan_korektif TEXT,
        status TEXT DEFAULT 'On Progress',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Seed default user if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        // Default admin / admin123
        $stmtUser = $pdo->prepare("INSERT INTO users (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
        $stmtUser->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT), 'Administrator HSSE', 'Admin HSSE']);
    }

    // Seed default data matching Canva mockups if empty
    $stmtData = $pdo->query("SELECT COUNT(*) FROM kecelakaan");
    if ($stmtData->fetchColumn() == 0) {
        $sampleData = [
            [
                'id_kejadian' => 'INC-2026-001',
                'tanggal' => '2026-08-01',
                'waktu' => '09:30',
                'lokasi' => 'Dermaga 07',
                'jenis_kecelakaan' => 'Terguling',
                'klasifikasi' => 'Fatality',
                'nama_korban' => 'Budi Santoso / Operator Excavator',
                'perusahaan' => 'PTP Banten',
                'status_korban' => 'Karyawan',
                'kronologi' => 'Excavator mengalami kehilangan keseimbangan di tepi dermaga saat memindahkan material berat.',
                'penyebab_langsung' => 'Permukaan kerja licin dan beban melebihi kapasitas standar aman.',
                'tindakan_korektif' => 'Memperketat SOP pemindahan material dan inspeksi rutin alat berat.',
                'status' => 'On Progress'
            ],
            [
                'id_kejadian' => 'INC-2026-002',
                'tanggal' => '2026-07-29',
                'waktu' => '14:15',
                'lokasi' => 'Gudang 03',
                'jenis_kecelakaan' => 'Terjepit',
                'klasifikasi' => 'Lost Time Injury',
                'nama_korban' => 'Ahmad Rifa\'i / Helper',
                'perusahaan' => 'PT Mitra Karya',
                'status_korban' => 'TKBM',
                'kronologi' => 'Tangan korban terjepit pintu geser otomatis gudang saat proses pembongkaran muatan.',
                'penyebab_langsung' => 'Sensor pintu otomatis tidak berfungsi dengan baik dan kurang koordinasi.',
                'tindakan_korektif' => 'Perbaikan sensor pintu gudang 03 dan penyegaran K3 operasional pintu.',
                'status' => 'On Progress'
            ],
            [
                'id_kejadian' => 'INC-2026-003',
                'tanggal' => '2026-07-18',
                'waktu' => '11:00',
                'lokasi' => 'Lapangan Operasional',
                'jenis_kecelakaan' => 'Tertabrak',
                'klasifikasi' => 'Medical Treatment',
                'nama_korban' => 'Dede Kurniawan / Checker',
                'perusahaan' => 'PTP Banten',
                'status_korban' => 'Karyawan',
                'kronologi' => 'Checker tersenggol bagian belakang forklift saat sedang mencatat peti kemas.',
                'penyebab_langsung' => 'Blindspot operator forklift dan tidak tersedianya jalur khusus pejalan kaki di depo.',
                'tindakan_korektif' => 'Pemasangan marka jalur pejalan kaki dan cermin cembung di sudut blindspot.',
                'status' => 'Closed'
            ],
            [
                'id_kejadian' => 'INC-2026-004',
                'tanggal' => '2026-06-28',
                'waktu' => '16:45',
                'lokasi' => 'Dermaga 05A',
                'jenis_kecelakaan' => 'Terjatuh/Tergelincir',
                'klasifikasi' => 'First Aid',
                'nama_korban' => 'Eko Prasetyo / Foreman',
                'perusahaan' => 'PT Logistik',
                'status_korban' => 'Mitra',
                'kronologi' => 'Terpeleset di geladak kapal akibat tumpahan oli tipis yang belum dibersihkan.',
                'penyebab_langsung' => 'Tumpahan cairan pelumas dan penggunaan sepatu keselamatan yang sudah aus.',
                'tindakan_korektif' => 'Penyediaan spill kit darurat di setiap dermaga dan inspeksi standar APD.',
                'status' => 'Closed'
            ],
            [
                'id_kejadian' => 'INC-2026-005',
                'tanggal' => '2026-06-11',
                'waktu' => '08:20',
                'lokasi' => 'Jalur Operasional',
                'jenis_kecelakaan' => 'Hampir tertabrak',
                'klasifikasi' => 'Near Miss',
                'nama_korban' => 'Fajar Nugraha / Pengemudi',
                'perusahaan' => 'PTP Banten',
                'status_korban' => 'Karyawan',
                'kronologi' => 'Truk kontainer rem mendadak saat pejalan kaki melintas di luar batas zona penyeberangan.',
                'penyebab_langsung' => 'Kurang konsentrasi pejalan kaki dan kecepatan kendaraan melebihi batas 20km/jam.',
                'tindakan_korektif' => 'Pemasangan polisi tidur tambahan dan pembatasan kecepatan tegas di zona pelabuhan.',
                'status' => 'Open'
            ],
            [
                'id_kejadian' => 'INC-2026-006',
                'tanggal' => '2026-05-15',
                'waktu' => '10:30',
                'lokasi' => 'Dermaga 01',
                'jenis_kecelakaan' => 'Terjepit',
                'klasifikasi' => 'Lost Time Injury',
                'nama_korban' => 'Hendra Wijaya / Rigger',
                'perusahaan' => 'PT Mitra Karya',
                'status_korban' => 'TKBM',
                'kronologi' => 'Tali sling derek menghimpit jemari tangan saat pengangkatan pipa baja.',
                'penyebab_langsung' => 'Posisi tangan korban berada di pinch point beban.',
                'tindakan_korektif' => 'Pelatihan ulang rigger K3 pengangkatan barang berat.',
                'status' => 'Closed'
            ]
        ];

        // Additional generated entries for total statistics to hit 32 items total for full realism
        $locs = ['Dermaga 01', 'Dermaga 07', 'Gudang 03', 'Lapangan Operasional', 'Dermaga 05A', 'Jalur Operasional'];
        $types = ['Terjatuh/Tergelincir', 'Tertabrak', 'Terguling', 'Terjepit', 'Hampir tertabrak', 'Lainnya'];
        $classifs = ['Lost Time Injury', 'Medical Treatment', 'First Aid', 'Near Miss'];
        
        for ($i = 7; $i <= 32; $i++) {
            $numStr = str_pad($i, 3, '0', STR_PAD_LEFT);
            $month = str_pad(rand(1, 7), 2, '0', STR_PAD_LEFT);
            $day = str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
            $c = $classifs[rand(0, count($classifs) - 1)];
            
            $sampleData[] = [
                'id_kejadian' => "INC-2026-{$numStr}",
                'tanggal' => "2026-{$month}-{$day}",
                'waktu' => sprintf("%02d:%02d", rand(7, 17), rand(0, 59)),
                'lokasi' => $locs[rand(0, count($locs) - 1)],
                'jenis_kecelakaan' => $types[rand(0, count($types) - 1)],
                'klasifikasi' => $c,
                'nama_korban' => 'Pekerja Lapangan #' . $i,
                'perusahaan' => (rand(0, 1) ? 'PTP Banten' : 'PT Logistik / Mitra'),
                'status_korban' => (rand(0, 1) ? 'Karyawan' : 'TKBM'),
                'kronologi' => 'Insiden operasional ringan saat aktivitas kerja rutin pelabuhan.',
                'penyebab_langsung' => 'Kurang kehati-hatian atau kondisi lingkungan kerja.',
                'tindakan_korektif' => 'Evaluasi K3 dan pengawasan area.',
                'status' => (rand(0, 2) == 0 ? 'On Progress' : 'Closed')
            ];
        }

        $stmtInsert = $pdo->prepare("INSERT INTO kecelakaan (
            id_kejadian, tanggal, waktu, lokasi, jenis_kecelakaan, klasifikasi,
            nama_korban, perusahaan, status_korban, kronologi, penyebab_langsung, tindakan_korektif, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($sampleData as $item) {
            $stmtInsert->execute([
                $item['id_kejadian'], $item['tanggal'], $item['waktu'], $item['lokasi'],
                $item['jenis_kecelakaan'], $item['klasifikasi'], $item['nama_korban'],
                $item['perusahaan'], $item['status_korban'], $item['kronologi'],
                $item['penyebab_langsung'], $item['tindakan_korektif'], $item['status']
            ]);
        }
    }

} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
