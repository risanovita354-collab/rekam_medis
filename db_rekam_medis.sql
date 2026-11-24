-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 24 Nov 2026 pada 07.22
-- Versi server: 10.11.11-MariaDB
-- Versi PHP: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rekam_medis`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `spesialisasi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `nama_dokter`, `spesialisasi`) VALUES
(1, 'dr. Hendra Wijaya', 'Umum'),
(2, 'dr. Rina Kusuma, Sp.A', 'Anak'),
(3, 'dr. Intan Sari,SP.PD', 'Penyakit Dalam');

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `satuan` varchar(30) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `satuan`, `stok`) VALUES
(1, 'Paracetamol 500mg', 'Tablet', 10),
(2, 'Attapulgite', 'Tablet', 300),
(3, 'OBH Combi', 'Sirup', 30);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `nama_pasien` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `no_rm`, `nama_pasien`, `jenis_kelamin`, `tanggal_lahir`, `alamat`) VALUES
(1, 'RM-001', 'Budi Santoso', 'L', '1995-04-12', 'Jl. Basuki Rahmat No. 10, Tuban'),
(2, 'RM-002', 'Siti Fatimah', 'P', '2001-08-23', 'Jl. Dr. Wahidin Sudirohusodo No. 5, Tuban'),
(4, 'RM-003', 'Novitasari', 'P', '2000-01-22', 'Jl. Mawar No. 2, Tuban');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rekam` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `tanggal_periksa` date NOT NULL,
  `keluhan` text NOT NULL,
  `diagnosa` text NOT NULL,
  `tindakan` text NOT NULL,
  `resep` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `rekam_medis`
--

INSERT INTO `rekam_medis` (`id_rekam`, `id_pasien`, `id_dokter`, `id_obat`, `tanggal_periksa`, `keluhan`, `diagnosa`, `tindakan`, `resep`) VALUES
(1, 1, 1, 1, '2026-11-12', 'Demam dan pusing sejak 2 hari', 'Febris / Demam ringan', 'Pemeriksaan suhu tubuh dan tensi', '3x1 tablet sesudah makan'),
(4, 4, 2, 3, '2026-11-17', 'Batuk berdahak disertai pilek dan hidung tersumbat selama 3 hari.', 'ISPA (Infeksi Saluran Pernapasan Akut) ringan pada anak.', 'Pemeriksaan fisik pernapasan dan tenggorokan, pengukuran berat badan dan suhu tubuh, serta edukasi menghindari minuman dingin.', '3 x 1 sendok takar (5 ml) sehari sesudah makan.'),
(5, 2, 3, 2, '2026-11-18', 'Buang air besar cair (diare) lebih dari 4 kali sejak pagi disertai perut mulas.', 'Gastroenteritis akut (Diare non-spesifik) tanpa dehidrasi berat.', 'Pemeriksaan fisik abdomen (perut), pengecekan tanda-tanda dehidrasi, serta edukasi konsumsi cairan oralit dan makanan lunak.', '2 tablet setelah buang air besar ');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indeks untuk tabel `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`);

--
-- Indeks untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rekam`),
  ADD KEY `fk_pasien` (`id_pasien`),
  ADD KEY `fk_dokter` (`id_dokter`),
  ADD KEY `fk_obat` (`id_obat`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rekam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `fk_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_obat` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
