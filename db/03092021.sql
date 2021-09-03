-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versi server:                 5.7.33 - MySQL Community Server (GPL)
-- OS Server:                    Win64
-- HeidiSQL Versi:               11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Membuang struktur basisdata untuk tagihan
DROP DATABASE IF EXISTS `tagihan`;
CREATE DATABASE IF NOT EXISTS `tagihan` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `tagihan`;

-- membuang struktur untuk table tagihan.fakultas
DROP TABLE IF EXISTS `fakultas`;
CREATE TABLE IF NOT EXISTS `fakultas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.fakultas: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `fakultas` DISABLE KEYS */;
REPLACE INTO `fakultas` (`id`, `nama`) VALUES
	(1, 'Fakultas A'),
	(2, 'Fakultas B'),
	(3, 'Fakultas C');
/*!40000 ALTER TABLE `fakultas` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.jenis_tagihan
DROP TABLE IF EXISTS `jenis_tagihan`;
CREATE TABLE IF NOT EXISTS `jenis_tagihan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `biaya` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.jenis_tagihan: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `jenis_tagihan` DISABLE KEYS */;
REPLACE INTO `jenis_tagihan` (`id`, `nama`, `description`, `biaya`) VALUES
	(1, 'SPP', 'Pembayaran SPP', 1000000),
	(2, 'Pembangunan', 'Pembayaran Pembangunan', 100000);
/*!40000 ALTER TABLE `jenis_tagihan` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.mahasiswa
DROP TABLE IF EXISTS `mahasiswa`;
CREATE TABLE IF NOT EXISTS `mahasiswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nim` char(15) DEFAULT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `semester_berjalan` tinyint(4) DEFAULT NULL,
  `tahun_masuk` date DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `alamat` tinytext,
  `prodi_id` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `FK_mahasiswa_prodi` (`prodi_id`),
  CONSTRAINT `FK_mahasiswa_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.mahasiswa: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `mahasiswa` DISABLE KEYS */;
REPLACE INTO `mahasiswa` (`id`, `nim`, `nama`, `semester_berjalan`, `tahun_masuk`, `no_hp`, `alamat`, `prodi_id`, `status`) VALUES
	(21, '003', 'cc', 3, '2021-09-01', NULL, NULL, 10, 'active'),
	(22, '004', 'dd', 4, '2021-09-01', NULL, NULL, 7, 'active');
/*!40000 ALTER TABLE `mahasiswa` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.mahasiswa_payment
DROP TABLE IF EXISTS `mahasiswa_payment`;
CREATE TABLE IF NOT EXISTS `mahasiswa_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `number` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `FK_mahasiswa_payment_payment` (`payment_id`),
  KEY `FK_mahasiswa_payment_mahasiswa` (`mahasiswa_id`),
  CONSTRAINT `FK_mahasiswa_payment_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_mahasiswa_payment_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.mahasiswa_payment: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `mahasiswa_payment` DISABLE KEYS */;
REPLACE INTO `mahasiswa_payment` (`id`, `mahasiswa_id`, `payment_id`, `number`) VALUES
	(3, 21, 1, '010003'),
	(4, 21, 2, '020003');
/*!40000 ALTER TABLE `mahasiswa_payment` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.mahasiswa_periode
DROP TABLE IF EXISTS `mahasiswa_periode`;
CREATE TABLE IF NOT EXISTS `mahasiswa_periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_id` int(11) NOT NULL,
  `periode_id` int(11) NOT NULL,
  `semester` int(11) NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  KEY `FK_mahasiswa_periode_mahasiswa` (`mahasiswa_id`),
  KEY `FK_mahasiswa_periode_periode` (`periode_id`),
  CONSTRAINT `FK_mahasiswa_periode_mahasiswa` FOREIGN KEY (`mahasiswa_id`) REFERENCES `mahasiswa` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_mahasiswa_periode_periode` FOREIGN KEY (`periode_id`) REFERENCES `periode` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.mahasiswa_periode: ~4 rows (lebih kurang)
/*!40000 ALTER TABLE `mahasiswa_periode` DISABLE KEYS */;
REPLACE INTO `mahasiswa_periode` (`id`, `mahasiswa_id`, `periode_id`, `semester`, `status`) VALUES
	(137, 21, 83, 2, 'active'),
	(138, 22, 83, 3, 'active'),
	(139, 21, 84, 3, 'active'),
	(140, 22, 84, 4, 'active');
/*!40000 ALTER TABLE `mahasiswa_periode` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.payment
DROP TABLE IF EXISTS `payment`;
CREATE TABLE IF NOT EXISTS `payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `va_depan` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.payment: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `payment` DISABLE KEYS */;
REPLACE INTO `payment` (`id`, `nama`, `va_depan`) VALUES
	(1, 'OVO', '010'),
	(2, 'DANA', '020');
/*!40000 ALTER TABLE `payment` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.periode
DROP TABLE IF EXISTS `periode`;
CREATE TABLE IF NOT EXISTS `periode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `periode` enum('ganjil','genap') NOT NULL,
  `tahun` year(4) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.periode: ~1 rows (lebih kurang)
/*!40000 ALTER TABLE `periode` DISABLE KEYS */;
REPLACE INTO `periode` (`id`, `periode`, `tahun`, `waktu_mulai`) VALUES
	(83, 'ganjil', '2020', '2021-09-02 12:55:32'),
	(84, 'ganjil', '2020', '2021-09-02 12:56:43');
/*!40000 ALTER TABLE `periode` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.prodi
DROP TABLE IF EXISTS `prodi`;
CREATE TABLE IF NOT EXISTS `prodi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fakultas_id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_prodi_fakultas` (`fakultas_id`),
  CONSTRAINT `FK_prodi_fakultas` FOREIGN KEY (`fakultas_id`) REFERENCES `fakultas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.prodi: ~5 rows (lebih kurang)
/*!40000 ALTER TABLE `prodi` DISABLE KEYS */;
REPLACE INTO `prodi` (`id`, `fakultas_id`, `nama`) VALUES
	(7, 1, '1A'),
	(8, 1, '1B'),
	(9, 2, '2A'),
	(10, 2, '2B'),
	(11, 3, '3A'),
	(12, 3, '3B');
/*!40000 ALTER TABLE `prodi` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.riwayat_bayar
DROP TABLE IF EXISTS `riwayat_bayar`;
CREATE TABLE IF NOT EXISTS `riwayat_bayar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tagihan_id` int(11) NOT NULL,
  `mahasiswa_payment_id` int(11) NOT NULL,
  `waktu_bayar` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_riwayat_bayar_tagihan` (`tagihan_id`),
  KEY `FK_riwayat_bayar_mahasiswa_payment` (`mahasiswa_payment_id`),
  CONSTRAINT `FK_riwayat_bayar_mahasiswa_payment` FOREIGN KEY (`mahasiswa_payment_id`) REFERENCES `mahasiswa_payment` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_riwayat_bayar_tagihan` FOREIGN KEY (`tagihan_id`) REFERENCES `tagihan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.riwayat_bayar: ~2 rows (lebih kurang)
/*!40000 ALTER TABLE `riwayat_bayar` DISABLE KEYS */;
REPLACE INTO `riwayat_bayar` (`id`, `tagihan_id`, `mahasiswa_payment_id`, `waktu_bayar`) VALUES
	(8, 58, 3, '2021-09-02 13:18:06'),
	(9, 58, 4, '2021-09-02 13:18:36');
/*!40000 ALTER TABLE `riwayat_bayar` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.role
DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(50) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `model` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.role: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
REPLACE INTO `role` (`id`, `nama`, `description`, `model`) VALUES
	(1, 'pimpinan', 'Pimpinan', ''),
	(2, 'bendahara', 'Bendahara', ''),
	(3, 'mahasiswa', 'Mahasiswa', 'MahasiswaModel');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.tagihan
DROP TABLE IF EXISTS `tagihan`;
CREATE TABLE IF NOT EXISTS `tagihan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mahasiswa_periode_id` int(11) NOT NULL,
  `jenis_tagihan_id` int(11) NOT NULL,
  `biaya` int(11) NOT NULL,
  `status` enum('lunas','belum') NOT NULL DEFAULT 'belum',
  PRIMARY KEY (`id`),
  KEY `FK_tagihan_mahasiswa_periode` (`mahasiswa_periode_id`),
  KEY `FK_tagihan_jenis_tagihan` (`jenis_tagihan_id`),
  CONSTRAINT `FK_tagihan_jenis_tagihan` FOREIGN KEY (`jenis_tagihan_id`) REFERENCES `jenis_tagihan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_tagihan_mahasiswa_periode` FOREIGN KEY (`mahasiswa_periode_id`) REFERENCES `mahasiswa_periode` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.tagihan: ~3 rows (lebih kurang)
/*!40000 ALTER TABLE `tagihan` DISABLE KEYS */;
REPLACE INTO `tagihan` (`id`, `mahasiswa_periode_id`, `jenis_tagihan_id`, `biaya`, `status`) VALUES
	(56, 138, 1, 1000000, 'belum'),
	(57, 137, 1, 1000000, 'belum'),
	(58, 139, 1, 1000000, 'lunas');
/*!40000 ALTER TABLE `tagihan` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.transaksi
DROP TABLE IF EXISTS `transaksi`;
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tagihan_id` int(11) NOT NULL,
  `mahasiswa_periode_id` int(11) NOT NULL,
  `biaya` int(11) NOT NULL,
  `status` enum('lunas','belum') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_transaksi_mahasiswa_periode` (`mahasiswa_periode_id`),
  KEY `FK_transaksi_jenis_tagihan` (`jenis_tagihan_id`),
  CONSTRAINT `FK_transaksi_jenis_tagihan` FOREIGN KEY (`jenis_tagihan_id`) REFERENCES `jenis_tagihan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_transaksi_mahasiswa_periode` FOREIGN KEY (`mahasiswa_periode_id`) REFERENCES `mahasiswa_periode` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.transaksi: ~0 rows (lebih kurang)
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;

-- membuang struktur untuk table tagihan.user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `foreign_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_user_role` (`role_id`),
  CONSTRAINT `FK_user_role` FOREIGN KEY (`role_id`) REFERENCES `role` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Membuang data untuk tabel tagihan.user: ~1 rows (lebih kurang)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
REPLACE INTO `user` (`id`, `username`, `password`, `role_id`, `foreign_id`) VALUES
	(6, 'bendahara', 'asd', 2, NULL),
	(7, 'mhs1', 'asd', 3, 21),
	(8, 'pimpinan', 'asd', 1, NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
