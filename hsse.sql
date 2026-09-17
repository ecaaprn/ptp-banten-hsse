-- hsse.sql
-- MySQL / MariaDB Schema Export for PTP Banten HSSE Database Kecelakaan Kerja

CREATE DATABASE IF NOT EXISTS `db_hsse_banten` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `db_hsse_banten`;

-- --------------------------------------------------------

-- Table structure for `users`
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` varchar(50) DEFAULT 'Admin HSSE',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed default admin user (Password: admin123)
INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`) VALUES
(1, 'admin', '$2y$10$e.wS/7Tkg3sWvUfG3G64y.qGfRj8jHn7.sYvG02OqSg7YtT5o1v6K', 'Administrator HSSE', 'Admin HSSE');

-- --------------------------------------------------------

-- Table structure for `kecelakaan`
CREATE TABLE IF NOT EXISTS `kecelakaan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_kejadian` varchar(20) NOT NULL UNIQUE,
  `tanggal` date NOT NULL,
  `waktu` time DEFAULT '08:00:00',
  `lokasi` varchar(100) NOT NULL,
  `jenis_kecelakaan` varchar(100) NOT NULL,
  `klasifikasi` varchar(50) NOT NULL,
  `nama_korban` varchar(150) NOT NULL,
  `perusahaan` varchar(100) NOT NULL,
  `status_korban` varchar(50) NOT NULL,
  `kronologi` text DEFAULT NULL,
  `penyebab_langsung` text DEFAULT NULL,
  `tindakan_korektif` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'On Progress',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Data matching Canva Mockups
INSERT INTO `kecelakaan` (`id_kejadian`, `tanggal`, `waktu`, `lokasi`, `jenis_kecelakaan`, `klasifikasi`, `nama_korban`, `perusahaan`, `status_korban`, `kronologi`, `penyebab_langsung`, `tindakan_korektif`, `status`) VALUES
('INC-2026-001', '2026-08-01', '09:30:00', 'Dermaga 07', 'Terguling', 'Fatality', 'Budi Santoso / Operator Excavator', 'PTP Banten', 'Karyawan', 'Excavator mengalami kehilangan keseimbangan di tepi dermaga saat memindahkan material berat.', 'Permukaan kerja licin dan beban melebihi kapasitas standar aman.', 'Memperketat SOP pemindahan material dan inspeksi rutin alat berat.', 'On Progress'),
('INC-2026-002', '2026-07-29', '14:15:00', 'Gudang 03', 'Terjepit', 'Lost Time Injury', 'Ahmad Rifa\'i / Helper', 'PT Mitra Karya', 'TKBM', 'Tangan korban terjepit pintu geser otomatis gudang saat proses pembongkaran muatan.', 'Sensor pintu otomatis tidak berfungsi dengan baik dan kurang koordinasi.', 'Perbaikan sensor pintu gudang 03 dan penyegaran K3 operasional pintu.', 'On Progress'),
('INC-2026-003', '2026-07-18', '11:00:00', 'Lapangan Operasional', 'Tertabrak', 'Medical Treatment', 'Dede Kurniawan / Checker', 'PTP Banten', 'Karyawan', 'Checker tersenggol bagian belakang forklift saat sedang mencatat peti kemas.', 'Blindspot operator forklift dan tidak tersedianya jalur khusus pejalan kaki di depo.', 'Pemasangan marka jalur pejalan kaki dan cermin cembung di sudut blindspot.', 'Closed'),
('INC-2026-004', '2026-06-28', '16:45:00', 'Dermaga 05A', 'Terjatuh/Tergelincir', 'First Aid', 'Eko Prasetyo / Foreman', 'PT Logistik', 'Mitra', 'Terpeleset di geladak kapal akibat tumpahan oli tipis yang belum dibersihkan.', 'Tumpahan cairan pelumas dan penggunaan sepatu keselamatan yang sudah aus.', 'Penyediaan spill kit darurat di setiap dermaga dan inspeksi standar APD.', 'Closed'),
('INC-2026-005', '2026-06-11', '08:20:00', 'Jalur Operasional', 'Hampir tertabrak', 'Near Miss', 'Fajar Nugraha / Pengemudi', 'PTP Banten', 'Karyawan', 'Truk kontainer rem mendadak saat pejalan kaki melintas di luar batas zona penyeberangan.', 'Kurang konsentrasi pejalan kaki dan kecepatan kendaraan melebihi batas 20km/jam.', 'Pemasangan polisi tidur tambahan dan pembatasan kecepatan tegas di zona pelabuhan.', 'Open');
