-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 09 Nov 2026 pada 16.29
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `deteksi_game`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan`
--

CREATE TABLE `aturan` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `tingkat_id` tinyint(3) UNSIGNED NOT NULL,
  `min_gejala` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `max_gejala` tinyint(3) UNSIGNED DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `aturan`
--

INSERT INTO `aturan` (`id`, `kode`, `tingkat_id`, `min_gejala`, `max_gejala`, `keterangan`, `aktif`) VALUES
(1, 'R1', 1, 1, 4, 'Jumlah gejala 1 sampai 4', 1),
(2, 'R2', 2, 5, 8, 'Jumlah gejala 5 sampai 8', 1),
(3, 'R3', 3, 9, NULL, 'Jumlah gejala 9 atau lebih', 1),
(4, 'R4', 3, 0, NULL, 'G05, G06, dan G07 muncul bersamaan', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aturan_gejala`
--

CREATE TABLE `aturan_gejala` (
  `aturan_id` smallint(5) UNSIGNED NOT NULL,
  `gejala_id` smallint(5) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `aturan_gejala`
--

INSERT INTO `aturan_gejala` (`aturan_id`, `gejala_id`) VALUES
(4, 5),
(4, 6),
(4, 7);

-- --------------------------------------------------------

--
-- Struktur dari tabel `gejala`
--

CREATE TABLE `gejala` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `dasar` varchar(60) NOT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `gejala`
--

INSERT INTO `gejala` (`id`, `kode`, `nama`, `dasar`, `aktif`) VALUES
(1, 'G01', 'Sering memikirkan game sepanjang hari', 'GAS: salience', 1),
(2, 'G02', 'Waktu bermain makin lama dari sebelumnya', 'GAS: tolerance', 1),
(3, 'G03', 'Bermain untuk melupakan masalah atau memperbaiki suasana hati', 'GAS: mood modification', 1),
(4, 'G04', 'Gagal mengurangi jam bermain walau sudah ditegur atau mencoba', 'GAS: relapse', 1),
(5, 'G05', 'Gelisah atau marah saat tidak bisa bermain', 'GAS: withdrawal', 1),
(6, 'G06', 'Bertengkar dengan keluarga atau teman karena waktu bermain', 'GAS: conflict', 1),
(7, 'G07', 'Mengabaikan sekolah atau kegiatan lain demi game', 'GAS: problems', 1),
(8, 'G08', 'Mata lelah, sakit kepala, atau sering begadang', 'Aspek kesehatan', 1),
(9, 'G09', 'Nilai turun atau telat mengumpulkan tugas', 'Aspek akademik', 1),
(10, 'G10', 'Jarang bertemu teman secara langsung', 'Aspek sosial', 1),
(11, 'G11', 'Sering memakai uang saku untuk top up atau membeli item', 'Aspek keuangan', 1),
(12, 'G12', 'Mudah emosi atau cemas di luar waktu bermain', 'Aspek psikologis', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `skrining`
--

CREATE TABLE `skrining` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_hasil` char(12) NOT NULL,
  `usia` tinyint(3) UNSIGNED DEFAULT NULL,
  `inisial` varchar(5) DEFAULT NULL,
  `jumlah_gejala` tinyint(3) UNSIGNED NOT NULL,
  `tingkat_id` tinyint(3) UNSIGNED NOT NULL,
  `aturan_id` smallint(5) UNSIGNED DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `skrining`
--

INSERT INTO `skrining` (`id`, `kode_hasil`, `usia`, `inisial`, `jumlah_gejala`, `tingkat_id`, `aturan_id`, `created_at`) VALUES
(1, '52a489e14d88', 20, 'tesa', 7, 2, 2, '2026-11-09 20:57:13'),
(2, 'e5cdcd152007', 18, 'linta', 5, 2, 2, '2026-11-09 21:50:27'),
(3, '4b16381fe0c8', 20, 'tesa', 5, 2, 2, '2026-11-09 21:52:00'),
(4, '4962e8922aed', 20, 'rahma', 7, 2, 2, '2026-11-09 21:53:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tingkat`
--

CREATE TABLE `tingkat` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(30) NOT NULL,
  `urutan` tinyint(3) UNSIGNED NOT NULL,
  `deskripsi` text NOT NULL,
  `saran` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tingkat`
--

INSERT INTO `tingkat` (`id`, `kode`, `nama`, `urutan`, `deskripsi`, `saran`) VALUES
(1, 'T1', 'Rendah', 1, 'Gejala yang muncul masih sedikit dan belum banyak mengganggu aktivitas.', 'Pertahankan kebiasaan, atur batas jam bermain, dan tetap aktif secara fisik.'),
(2, 'T2', 'Sedang', 2, 'Beberapa gejala sudah muncul dan mulai mempengaruhi aktivitas sehari-hari.', 'Buat jadwal bermain dan alarm, cari kegiatan pengganti, dan ceritakan ke orang tua atau guru.'),
(3, 'T3', 'Tinggi', 3, 'Banyak gejala muncul dan sudah mengganggu kesehatan, belajar, atau hubungan sosial.', 'Dianjurkan berkonsultasi dengan psikolog atau konselor.');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_aturan_kode` (`kode`),
  ADD KEY `fk_aturan_tingkat` (`tingkat_id`);

--
-- Indeks untuk tabel `aturan_gejala`
--
ALTER TABLE `aturan_gejala`
  ADD PRIMARY KEY (`aturan_id`,`gejala_id`),
  ADD KEY `fk_ag_gejala` (`gejala_id`);

--
-- Indeks untuk tabel `gejala`
--
ALTER TABLE `gejala`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_gejala_kode` (`kode`);

--
-- Indeks untuk tabel `skrining`
--
ALTER TABLE `skrining`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_skrining_kode` (`kode_hasil`),
  ADD KEY `fk_skrining_tingkat` (`tingkat_id`);

--
-- Indeks untuk tabel `tingkat`
--
ALTER TABLE `tingkat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tingkat_kode` (`kode`),
  ADD UNIQUE KEY `uq_tingkat_urutan` (`urutan`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aturan`
--
ALTER TABLE `aturan`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `gejala`
--
ALTER TABLE `gejala`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `skrining`
--
ALTER TABLE `skrining`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `tingkat`
--
ALTER TABLE `tingkat`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aturan`
--
ALTER TABLE `aturan`
  ADD CONSTRAINT `fk_aturan_tingkat` FOREIGN KEY (`tingkat_id`) REFERENCES `tingkat` (`id`);

--
-- Ketidakleluasaan untuk tabel `aturan_gejala`
--
ALTER TABLE `aturan_gejala`
  ADD CONSTRAINT `fk_ag_aturan` FOREIGN KEY (`aturan_id`) REFERENCES `aturan` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ag_gejala` FOREIGN KEY (`gejala_id`) REFERENCES `gejala` (`id`);

--
-- Ketidakleluasaan untuk tabel `skrining`
--
ALTER TABLE `skrining`
  ADD CONSTRAINT `fk_skrining_tingkat` FOREIGN KEY (`tingkat_id`) REFERENCES `tingkat` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
