-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2021 at 02:25 AM
-- Server version: 10.4.14-MariaDB-log
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pwpb_myspp`
--
CREATE DATABASE IF NOT EXISTS `pwpb_myspp` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `pwpb_myspp`;

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(10) UNSIGNED NOT NULL,
  `nama_kelas` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_tingkatan` int(10) UNSIGNED NOT NULL,
  `id_kompetensi_keahlian` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`, `id_tingkatan`, `id_kompetensi_keahlian`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Default', 1, 1, '2021-04-06 12:47:54', '2021-04-06 12:47:54', NULL),
(2, 'Prima C', 2, 3, '2021-04-06 14:19:32', '2021-04-06 14:19:48', NULL),
(3, 'studio 8', 1, 4, '2021-04-07 02:33:21', '2021-04-07 02:33:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `keranjang_transaksi`
--

CREATE TABLE `keranjang_transaksi` (
  `id_keranjang` int(10) UNSIGNED NOT NULL,
  `id_spp` int(10) UNSIGNED NOT NULL,
  `id_petugas` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `bulan_dibayar` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kompetensi_keahlian`
--

CREATE TABLE `kompetensi_keahlian` (
  `id_kompetensi_keahlian` int(10) UNSIGNED NOT NULL,
  `kompetensi_keahlian` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kompetensi_keahlian`
--

INSERT INTO `kompetensi_keahlian` (`id_kompetensi_keahlian`, `kompetensi_keahlian`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'RPL 1', '2021-04-06 12:47:54', '2021-04-06 13:07:57', NULL),
(2, 'RPL 2', '2021-04-06 13:08:02', '2021-04-06 13:08:20', '2021-04-06 13:08:20'),
(3, 'RPL 3', '2021-04-06 13:08:15', '2021-04-06 13:08:15', NULL),
(4, 'BC 1', '2021-04-07 02:31:12', '2021-04-07 02:31:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2021_02_28_060149_create_petugas_table', 1),
(3, '2021_02_28_062700_create_tingkatan_table', 1),
(4, '2021_02_28_062702_create_spp_table', 1),
(5, '2021_02_28_062730_create_kompetensi_keahlian_table', 1),
(6, '2021_02_28_062800_create_kelas_table', 1),
(7, '2021_02_28_072025_create_siswa_table', 1),
(8, '2021_02_28_074045_create_pembayaran_table', 1),
(9, '2021_03_19_115030_create_keranjang_transaksi_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `id_pembayaran` int(10) UNSIGNED NOT NULL,
  `kode_pembayaran` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_petugas` int(10) UNSIGNED NOT NULL,
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `tgl_bayar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `bulan_dibayar` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tahun_dibayar` varchar(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_spp` int(10) UNSIGNED NOT NULL,
  `jumlah_bayar` int(11) NOT NULL,
  `jenis_pembayaran` enum('cash','transfer','gopay','ovo','indomaret','alfamart') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`id_pembayaran`, `kode_pembayaran`, `id_petugas`, `id_siswa`, `tgl_bayar`, `bulan_dibayar`, `tahun_dibayar`, `id_spp`, `jumlah_bayar`, `jenis_pembayaran`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 't3buZ0400', 1, 1, '2021-04-06 16:12:38', '2', '2021', 2, 160000, 'cash', '2021-04-06 16:12:38', '2021-04-06 16:12:38', NULL),
(2, '02aVd0Xr0', 2, 1, '2021-04-06 16:13:41', '1', '2021', 2, 160000, 'cash', '2021-04-06 16:13:41', '2021-04-06 16:13:41', NULL),
(3, '02aVd0Xr1', 2, 1, '2021-04-06 16:13:41', '3', '2021', 2, 100000, 'cash', '2021-04-06 16:13:41', '2021-04-06 16:13:41', NULL),
(4, 'uiptRmAT0', 4, 3, '2021-04-07 02:43:54', '1', '2021', 1, 20000, 'cash', '2021-04-07 02:43:54', '2021-04-07 02:43:54', NULL),
(5, 'Fp3Ngzhu0', 4, 1, '2021-04-08 01:42:32', '2', '2021', 1, 120000, 'cash', '2021-04-08 01:42:32', '2021-04-08 01:42:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `petugas`
--

CREATE TABLE `petugas` (
  `id_petugas` int(10) UNSIGNED NOT NULL,
  `nama_petugas` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_of` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `petugas`
--

INSERT INTO `petugas` (`id_petugas`, `nama_petugas`, `data_of`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Hulyo Firmansyah', 1, '2021-04-06 12:47:53', '2021-04-06 12:47:53', NULL),
(2, 'Dodit Iskandar r', 2, '2021-04-06 12:49:50', '2021-04-06 13:06:37', NULL),
(4, 'Anton', 6, '2021-04-07 02:29:46', '2021-04-07 02:29:46', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(10) UNSIGNED NOT NULL,
  `nisn` char(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nis` char(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(35) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_kelas` int(10) UNSIGNED NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_telp` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `data_of` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nisn`, `nis`, `nama`, `id_kelas`, `alamat`, `no_telp`, `data_of`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '312425', '124', 'Yusuf Suprianto', 2, 'Candirenggo asasdasdasd', '034563454', 4, '2021-04-06 14:20:59', '2021-04-06 14:20:59', NULL),
(2, '3124', '3908', 'Dono Pradana', 2, 'Adhahsd kasdk ash dkahs kdjashk hdaks j', '0234525523', 5, '2021-04-06 14:24:10', '2021-04-06 14:24:10', NULL),
(3, '123', '123', 'Anton Siswa', 3, 'Jl. Tejosari RT 01 RW 09 Candirenggo, Singosari, Malang, Jawa Timur, Indonesia\nSebelah Barat Gopur Motor', '08', 7, '2021-04-07 02:36:56', '2021-04-07 02:36:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `spp`
--

CREATE TABLE `spp` (
  `id_spp` int(10) UNSIGNED NOT NULL,
  `tahun` varchar(9) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nominal` int(11) NOT NULL,
  `id_tingkatan` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spp`
--

INSERT INTO `spp` (`id_spp`, `tahun`, `nominal`, `id_tingkatan`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '2019/2020', 1440000, 1, '2021-04-06 16:06:38', '2021-04-06 16:06:38', NULL),
(2, '2019/2020', 1920000, 2, '2021-04-06 16:06:59', '2021-04-06 16:12:02', NULL),
(3, '2021/2022', 1477476, 1, '2021-04-07 02:39:08', '2021-04-07 02:39:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tingkatan`
--

CREATE TABLE `tingkatan` (
  `id_tingkatan` int(10) UNSIGNED NOT NULL,
  `tingkatan` enum('10','11','12','13') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tingkatan`
--

INSERT INTO `tingkatan` (`id_tingkatan`, `tingkatan`, `created_at`, `updated_at`) VALUES
(1, '10', '2021-04-06 12:47:53', '2021-04-06 12:47:53'),
(2, '11', '2021-04-06 12:47:53', '2021-04-06 12:47:53'),
(3, '12', '2021-04-06 12:47:53', '2021-04-06 12:47:53'),
(4, '13', '2021-04-06 12:47:53', '2021-04-06 12:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id_user` int(10) UNSIGNED NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','worker','student') COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id_user`, `username`, `password`, `email`, `role`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '$2y$10$PgL1TexZQ4qAgXQVE5LybONF3g2AXiUjfFvGruOgb5xjbB7ZHhdBW', 'admin@gmail.com', 'admin', '2021-04-06 12:47:53', NULL, '2021-04-06 12:47:53', '2021-04-06 12:47:53', NULL),
(2, 'petugas', '$2y$10$XR4A4MZC0OKQn/yPdF4DrOr7sbkP1fiIfQeJsgoVQNT0zCNDWQRka', 'dodit@gmail.com12', 'worker', '2021-04-06 12:49:50', NULL, '2021-04-06 12:49:50', '2021-04-06 13:06:37', NULL),
(4, 'siswa', '$2y$10$GFb2/SYGJY4guOPjqvEPI.u7RHyq6g5yhbamNAlqVhWo6PLFtzgD.', 'yuf@suf.sd', 'student', '2021-04-06 14:20:59', NULL, '2021-04-06 14:20:59', '2021-04-06 14:20:59', NULL),
(5, 'dono213', '$2y$10$6kQNFGYXjHQYohSe9BAIYeEax9joynN5CuSC38mG63Nr4K/OuQhW2', 'dono@gmail.com', 'student', '2021-04-06 14:24:10', NULL, '2021-04-06 14:24:10', '2021-04-06 14:24:10', NULL),
(6, 'anton', '$2y$10$EUpAMyLx5WNi0aCKWfvbCOxrEZvGRTp6FVeG1SQqVLv5sC4jtDgxi', 'anton@mail.ru', 'worker', '2021-04-07 02:29:46', NULL, '2021-04-07 02:29:46', '2021-04-07 02:29:46', NULL),
(7, 'antonsiswa', '$2y$10$uCcOG4SVWDfWbvqvgPf3/uEf3PlQPhTNmPKE1v.cDnz3plakmMFmm', 'antonsiswa@gmail.com', 'student', '2021-04-07 02:36:56', NULL, '2021-04-07 02:36:56', '2021-04-07 02:36:56', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`),
  ADD KEY `kelas_id_tingkatan_foreign` (`id_tingkatan`),
  ADD KEY `kelas_id_kompetensi_keahlian_foreign` (`id_kompetensi_keahlian`);

--
-- Indexes for table `keranjang_transaksi`
--
ALTER TABLE `keranjang_transaksi`
  ADD PRIMARY KEY (`id_keranjang`),
  ADD KEY `keranjang_transaksi_id_spp_foreign` (`id_spp`),
  ADD KEY `keranjang_transaksi_id_petugas_foreign` (`id_petugas`),
  ADD KEY `keranjang_transaksi_id_siswa_foreign` (`id_siswa`);

--
-- Indexes for table `kompetensi_keahlian`
--
ALTER TABLE `kompetensi_keahlian`
  ADD PRIMARY KEY (`id_kompetensi_keahlian`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD UNIQUE KEY `pembayaran_kode_pembayaran_unique` (`kode_pembayaran`),
  ADD KEY `pembayaran_id_petugas_foreign` (`id_petugas`),
  ADD KEY `pembayaran_id_siswa_foreign` (`id_siswa`),
  ADD KEY `pembayaran_id_spp_foreign` (`id_spp`);

--
-- Indexes for table `petugas`
--
ALTER TABLE `petugas`
  ADD PRIMARY KEY (`id_petugas`),
  ADD KEY `petugas_data_of_foreign` (`data_of`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`),
  ADD UNIQUE KEY `siswa_nisn_unique` (`nisn`),
  ADD KEY `siswa_id_kelas_foreign` (`id_kelas`),
  ADD KEY `siswa_data_of_foreign` (`data_of`);

--
-- Indexes for table `spp`
--
ALTER TABLE `spp`
  ADD PRIMARY KEY (`id_spp`),
  ADD KEY `spp_id_tingkatan_foreign` (`id_tingkatan`);

--
-- Indexes for table `tingkatan`
--
ALTER TABLE `tingkatan`
  ADD PRIMARY KEY (`id_tingkatan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `keranjang_transaksi`
--
ALTER TABLE `keranjang_transaksi`
  MODIFY `id_keranjang` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kompetensi_keahlian`
--
ALTER TABLE `kompetensi_keahlian`
  MODIFY `id_kompetensi_keahlian` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `id_pembayaran` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `petugas`
--
ALTER TABLE `petugas`
  MODIFY `id_petugas` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spp`
--
ALTER TABLE `spp`
  MODIFY `id_spp` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tingkatan`
--
ALTER TABLE `tingkatan`
  MODIFY `id_tingkatan` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `kelas_id_kompetensi_keahlian_foreign` FOREIGN KEY (`id_kompetensi_keahlian`) REFERENCES `kompetensi_keahlian` (`id_kompetensi_keahlian`) ON DELETE CASCADE,
  ADD CONSTRAINT `kelas_id_tingkatan_foreign` FOREIGN KEY (`id_tingkatan`) REFERENCES `tingkatan` (`id_tingkatan`);

--
-- Constraints for table `keranjang_transaksi`
--
ALTER TABLE `keranjang_transaksi`
  ADD CONSTRAINT `keranjang_transaksi_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_transaksi_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `keranjang_transaksi_id_spp_foreign` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_id_petugas_foreign` FOREIGN KEY (`id_petugas`) REFERENCES `petugas` (`id_petugas`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_id_siswa_foreign` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `pembayaran_id_spp_foreign` FOREIGN KEY (`id_spp`) REFERENCES `spp` (`id_spp`) ON DELETE CASCADE;

--
-- Constraints for table `petugas`
--
ALTER TABLE `petugas`
  ADD CONSTRAINT `petugas_data_of_foreign` FOREIGN KEY (`data_of`) REFERENCES `users` (`id_user`) ON DELETE CASCADE;

--
-- Constraints for table `siswa`
--
ALTER TABLE `siswa`
  ADD CONSTRAINT `siswa_data_of_foreign` FOREIGN KEY (`data_of`) REFERENCES `users` (`id_user`) ON DELETE CASCADE,
  ADD CONSTRAINT `siswa_id_kelas_foreign` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`) ON DELETE CASCADE;

--
-- Constraints for table `spp`
--
ALTER TABLE `spp`
  ADD CONSTRAINT `spp_id_tingkatan_foreign` FOREIGN KEY (`id_tingkatan`) REFERENCES `tingkatan` (`id_tingkatan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
