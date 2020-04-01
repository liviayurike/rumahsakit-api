-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 16 Mar 2020 pada 17.35
-- Versi server: 8.0.18
-- Versi PHP: 7.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rumahsakit`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `dokter`
--

CREATE TABLE `dokter` (
  `id_dok` int(10) NOT NULL,
  `nama_dok` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_dok` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_telp_dok` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `spesialis_dok` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `dokter`
--

INSERT INTO `dokter` (`id_dok`, `nama_dok`, `alamat_dok`, `no_telp_dok`, `spesialis_dok`) VALUES
(1, 'Dika Putra', 'Probolinggo', '085247003680', 'Dokter Umum'),
(2, 'Bella Hesty', 'Kota Batu', '09878767766', 'Otak'),
(5, 'Sabrina Eka', 'Jl Piranha Atas', '082228773286', 'THT');

-- --------------------------------------------------------

--
-- Struktur dari tabel `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(10) NOT NULL,
  `nama_obat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_obat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stok_obat` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `jenis_obat`, `stok_obat`) VALUES
(1, 'paracetamol', 'sirup', 12),
(3, 'Ultraflu', 'Tablet', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `periksa`
--

CREATE TABLE `periksa` (
  `id_periksa` int(10) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_dok` int(11) NOT NULL,
  `keluhan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `tglperiksa` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `periksa`
--

INSERT INTO `periksa` (`id_periksa`, `id_user`, `id_dok`, `keluhan`, `tglperiksa`) VALUES
(1, 5, 2, 'Demam', '2020-03-11'),
(2, 6, 1, 'Pusing', '2020-03-14'),
(3, 5, 5, 'Pusingg', '2020-03-03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id_transaksi` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_periksa` int(11) NOT NULL,
  `id_dok` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `biaya` int(100) NOT NULL,
  `tgl_transaksi` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id_transaksi`, `id_user`, `id_periksa`, `id_dok`, `id_obat`, `biaya`, `tgl_transaksi`) VALUES
(1, 5, 1, 2, 1, 50000, '2020-03-11'),
(2, 5, 2, 1, 3, 30000, '2020-03-14');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `level` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `nama`, `username`, `email`, `password`, `level`, `status`) VALUES
(2, 'admin', 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', 'admin', 'Aktif'),
(5, 'Livia Yurike', 'Livia', 'kliviayurike@gmail.com', '3fb4b2291f7bf8c4835b8a11f1cf199f', 'user', 'Aktif'),
(6, 'Husnul Hotimah', 'husnul', 'husnul@gmail.com', 'f468f7176cf2bb5504bc2f04db1e3b18', 'user', 'Tidak Aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dok`);

--
-- Indeks untuk tabel `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indeks untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD PRIMARY KEY (`id_periksa`),
  ADD KEY `id_dok` (`id_dok`),
  ADD KEY `id_user` (`id_user`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_dok` (`id_dok`),
  ADD KEY `id_periksa` (`id_periksa`),
  ADD KEY `id_obat` (`id_obat`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dok` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `periksa`
--
ALTER TABLE `periksa`
  MODIFY `id_periksa` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id_transaksi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `periksa`
--
ALTER TABLE `periksa`
  ADD CONSTRAINT `periksa_ibfk_1` FOREIGN KEY (`id_dok`) REFERENCES `dokter` (`id_dok`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `periksa_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_dok`) REFERENCES `dokter` (`id_dok`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transaksi_ibfk_3` FOREIGN KEY (`id_periksa`) REFERENCES `periksa` (`id_periksa`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `transaksi_ibfk_4` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
