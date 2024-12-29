-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 23 Okt 2024 pada 06.45
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jalanrusak`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `jalan`
--

CREATE TABLE `jalan` (
  `id` int(11) NOT NULL,
  `nm_jalan` varchar(70) NOT NULL,
  `kampung` varchar(30) NOT NULL,
  `panjang` varchar(30) NOT NULL,
  `lebar` varchar(34) NOT NULL,
  `jl_bagus` varchar(30) NOT NULL,
  `jl_sedang` varchar(30) NOT NULL,
  `jl_rusak` varchar(30) NOT NULL,
  `lat` varchar(40) NOT NULL,
  `lng` varchar(40) NOT NULL,
  `foto` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `jalan`
--

INSERT INTO `jalan` (`id`, `nm_jalan`, `kampung`, `panjang`, `lebar`, `jl_bagus`, `jl_sedang`, `jl_rusak`, `lat`, `lng`, `foto`) VALUES
(1, 'Jl.Kampung Marga Mulya', '3', '90', '11', '12', '4', '4', '-8.4729765616337', '140.39953237859024', '6714bbf4a535a-erd-laporan.png'),
(2, 'Jl. Ruas Kampung Urumb', '4', '30', '50', '20', '10', '10', '-8.460279995771144', '140.33481794745992', '6715bda8196ef-Ice cream butter');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kampung`
--

CREATE TABLE `kampung` (
  `id` int(11) NOT NULL,
  `kampung` varchar(70) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `kampung`
--

INSERT INTO `kampung` (`id`, `kampung`) VALUES
(1, 'Semangga Jaya'),
(2, 'Muram Sari'),
(3, 'Matara'),
(4, 'Urumb'),
(5, 'Sidomulyo'),
(6, 'Kuprik'),
(7, 'Kuper'),
(8, 'Marga Mulya'),
(9, 'Waninggap Kai'),
(10, 'Waninggap Nanggo');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuan`
--

CREATE TABLE `pengajuan` (
  `id` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `alamat` varchar(60) NOT NULL,
  `aju_jln` varchar(70) NOT NULL,
  `kampung` varchar(50) NOT NULL,
  `jln_rusak` varchar(30) NOT NULL,
  `lat` varchar(250) NOT NULL,
  `lng` varchar(250) NOT NULL,
  `foto` varchar(30) NOT NULL,
  `status` enum('Menunggu','Disetujui','Ditolak') NOT NULL DEFAULT 'Menunggu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `pengajuan`
--

INSERT INTO `pengajuan` (`id`, `nama`, `alamat`, `aju_jln`, `kampung`, `jln_rusak`, `lat`, `lng`, `foto`, `status`) VALUES
(1, 'ardianus', 'Jl. Biankuk, RT.02/RW.02, Karang Indah, Kec. Merauke, Kabupa', 'Jl.Kampung Buti', '2', '123', '-8.529958016416957', '140.41952869255306', 'hexohm.jpg', 'Disetujui'),
(3, 'Maulana Aulia', 'Jl Timur', 'Jl.Kampung Muram', '2', '12', '-8.493001562654946', '140.3700370439328', 'Drawing1.jpg', 'Ditolak'),
(5, 'budi', 'jl.Noari', 'Kalimaro', '8', '123', '-8.489738060219377', '140.40004039648042', 'hexohm.jpg', 'Menunggu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_log`
--

CREATE TABLE `tbl_log` (
  `id` int(11) NOT NULL,
  `nama` varchar(40) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `foto` varchar(255) NOT NULL,
  `level` enum('admin','kabag') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tbl_log`
--

INSERT INTO `tbl_log` (`id`, `nama`, `username`, `password`, `foto`, `level`) VALUES
(1, 'admin', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'terima-kasih.jpg', 'admin'),
(2, 'kabag', 'kabag', '1a50ef14d0d75cd795860935ee0918af', 'hexohm.jpg', 'kabag');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `jalan`
--
ALTER TABLE `jalan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kampung`
--
ALTER TABLE `kampung`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tbl_log`
--
ALTER TABLE `tbl_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `jalan`
--
ALTER TABLE `jalan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `kampung`
--
ALTER TABLE `kampung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `tbl_log`
--
ALTER TABLE `tbl_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
