-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 11, 2026 at 10:23 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sampledb`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kategori` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kondisi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Baik',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `name`, `kategori`, `kondisi`, `status`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Arduino Uno R3', 'Mikrokontroler', 'Baik', 'available', 'Arduino Uno R3.jpg', '2026-06-02 09:13:09', '2026-06-11 03:06:11'),
(3, 'ESP32 NodeMCU Wi-Fi', 'IoT Module', 'Baik', 'available', 'ESP32 NodeMCU Wi-Fi.jpg', '2026-06-02 09:13:09', '2026-06-11 02:30:07'),
(4, 'Oscilloscope Digital 100MHz', 'Alat Ukur', 'Baik', 'borrowed', NULL, '2026-06-02 09:13:09', '2026-06-11 02:31:55'),
(5, 'Sensor Ultrasonik HC-SR04', 'Sensor', 'Baik', 'available', 'Sensor Ultrasonik HC-SR04.jpg', '2026-06-02 09:13:09', '2026-06-11 02:30:07'),
(6, 'Soldering Station Adjustable', 'Peralatan Kerja', 'Baik', 'available', 'Soldering Station Adjustable.jpg', '2026-06-02 09:13:09', '2026-06-11 02:30:07');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_labs`
--

CREATE TABLE `jadwal_labs` (
  `id` bigint UNSIGNED NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mata_kuliah` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_selesai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dosen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_labs`
--

INSERT INTO `jadwal_labs` (`id`, `hari`, `mata_kuliah`, `jam_mulai`, `jam_selesai`, `dosen`, `kelas`, `created_at`, `updated_at`) VALUES
(1, 'Senin', 'Praktikum IoT Dasar', '08:00', '10:30', 'Dr. Ir. Budi Santoso, M.T.', 'IK-3A', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(2, 'Selasa', 'Sistem Tertanam (Embedded)', '13:00', '15:30', 'Ahmad Fauzi, M.Kom.', 'IK-3B', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(3, 'Rabu', 'Proyek Penelitian Mandiri IoT', '10:00', '12:30', 'Rina Wijayanti, Ph.D.', 'Penelitian', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(4, 'Kamis', 'Jaringan Sensor Nirkabel', '08:00', '10:30', 'Dr. Ir. Budi Santoso, M.T.', 'IK-4A', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(5, 'Jumat', 'Workshop Robotika & IoT', '14:00', '16:00', 'Haryanto, M.T.', 'Lab Member', '2026-06-02 09:13:09', '2026-06-02 09:13:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporan_kerusakans`
--

CREATE TABLE `laporan_kerusakans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `laporan_kerusakans`
--

INSERT INTO `laporan_kerusakans` (`id`, `user_id`, `barang_id`, `deskripsi`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 'rusak otaknya', 'selesai', '2026-06-11 03:00:14', '2026-06-11 03:00:42'),
(2, 6, 1, 'eror', 'selesai', '2026-06-11 03:04:49', '2026-06-11 03:06:11');

-- --------------------------------------------------------

--
-- Table structure for table `log_akses`
--

CREATE TABLE `log_akses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `rfid_card_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `log_akses`
--

INSERT INTO `log_akses` (`id`, `user_id`, `rfid_card_id`, `action`, `notes`, `created_at`, `updated_at`) VALUES
(1, NULL, NULL, 'Pemesanan Ruangan', 'User ivan membooking ruangan \"Ruang Utama Lab IoT\" untuk tanggal 2026-06-02.', '2026-06-02 09:23:50', '2026-06-02 09:23:50'),
(2, 6, NULL, 'Laporan Kerusakan', 'User Dosen Budi melaporkan kerusakan alat \"Arduino Uno R3\" via portal web.', '2026-06-11 03:00:14', '2026-06-11 03:00:14'),
(3, 6, NULL, 'Laporan Kerusakan', 'User Dosen Budi melaporkan kerusakan alat \"Arduino Uno R3\" via portal web.', '2026-06-11 03:04:49', '2026-06-11 03:04:49');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '0001_01_02_000000_create_rfid_management_tables', 1),
(5, '2026_06_02_161235_create_jadwal_labs_table', 1),
(6, '2026_06_02_161752_create_peminjaman_ruangans_table', 2),
(7, '2026_06_05_000000_add_image_to_barangs', 3),
(8, '2026_06_11_095010_create_laporan_kerusakans_table', 4);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjamans`
--

CREATE TABLE `peminjamans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `tag_rfid_id` bigint UNSIGNED NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `ended_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman_ruangans`
--

CREATE TABLE `peminjaman_ruangans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `nama_ruangan` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `hari` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_mulai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jam_selesai` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'approved',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rfid_cards`
--

CREATE TABLE `rfid_cards` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_logs`
--

CREATE TABLE `riwayat_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detail` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayat_logs`
--

INSERT INTO `riwayat_logs` (`id`, `event`, `detail`, `created_at`, `updated_at`) VALUES
(1, 'Booking Ruang', 'User ivan membooking ruang Ruang Utama Lab IoT.', '2026-06-02 09:23:50', '2026-06-02 09:23:50'),
(2, 'Update Barang', 'Barang \"Arduino Uno R3\" diperbarui oleh user.', '2026-06-05 05:11:35', '2026-06-05 05:11:35'),
(3, 'Hapus Barang', 'Barang \"Raspberry Pi 4 Model B (4GB)\" dihapus oleh user.', '2026-06-05 05:22:11', '2026-06-05 05:22:11'),
(4, 'Update Barang', 'Barang \"ESP32 NodeMCU Wi-Fi\" diperbarui oleh user.', '2026-06-11 01:27:06', '2026-06-11 01:27:06'),
(5, 'Update Barang', 'Barang \"Arduino Uno R3\" diperbarui oleh user.', '2026-06-11 01:32:58', '2026-06-11 01:32:58'),
(6, 'Update Barang', 'Barang \"Sensor Ultrasonik HC-SR04\" diperbarui oleh user.', '2026-06-11 01:33:59', '2026-06-11 01:33:59'),
(7, 'Update Barang', 'Barang \"Soldering Station Adjustable\" diperbarui oleh user.', '2026-06-11 01:34:17', '2026-06-11 01:34:17'),
(8, 'Laporan Kerusakan', 'User Dosen Budi melaporkan kerusakan barang Arduino Uno R3: \"rusak otaknya\".', '2026-06-11 03:00:14', '2026-06-11 03:00:14'),
(9, 'Update Status Kerusakan', 'Admin memperbarui status laporan kerusakan #1 (Arduino Uno R3) menjadi proses.', '2026-06-11 03:00:33', '2026-06-11 03:00:33'),
(10, 'Update Status Kerusakan', 'Admin memperbarui status laporan kerusakan #1 (Arduino Uno R3) menjadi selesai.', '2026-06-11 03:00:42', '2026-06-11 03:00:42'),
(11, 'Laporan Kerusakan', 'User Dosen Budi melaporkan kerusakan barang Arduino Uno R3: \"eror\".', '2026-06-11 03:04:49', '2026-06-11 03:04:49'),
(12, 'Update Status Kerusakan', 'Admin memperbarui status laporan kerusakan #2 (Arduino Uno R3) menjadi proses.', '2026-06-11 03:06:00', '2026-06-11 03:06:00'),
(13, 'Update Status Kerusakan', 'Admin memperbarui status laporan kerusakan #2 (Arduino Uno R3) menjadi proses.', '2026-06-11 03:06:02', '2026-06-11 03:06:02'),
(14, 'Update Status Kerusakan', 'Admin memperbarui status laporan kerusakan #2 (Arduino Uno R3) menjadi selesai.', '2026-06-11 03:06:11', '2026-06-11 03:06:11');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('49WbBLjuqWL3JHDjzdbBDBlU0cxvY1pdNhdHqZQg', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5TXlweUNZbkhTbkg0SDA2T0dqamJLNlR3aFFLd01Ub2EwQ0JCbWQxIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDAiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781173349),
('esrCRruKh5GNLfyYv7zE75LrxTz1oZosjrYafu7j', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ5Q250VEpjVk9SRmdUVnB5NjJIamowSFlZOXZwVlJBUThvblpVM044IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9yZWdpc3RlciIsInJvdXRlIjoicmVnaXN0ZXIifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781171624),
('N7JwSgBdPD5BhlYdNjUoqCynFyKGhBHVL0XpuAc4', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJJN3NjZDNaVW1MVUdTVUlBRjFhMnRNNENUcmVZSDdkd0FLOWh1VDRDIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoibG9naW4ifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1781164181),
('PIsQrx5f45a69RD8DnMRJNTB7OWSv1bZgXWCIa91', 6, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJPRVNVbmMxT01Wb2g1NE5MOXh6VWlPM1FwOE5JU0sxUVByZm1NREFtIiwiX2ZsYXNoIjp7Im5ldyI6W10sIm9sZCI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDAiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6Nn0=', 1781172290);

-- --------------------------------------------------------

--
-- Table structure for table `tag_rfids`
--

CREATE TABLE `tag_rfids` (
  `id` bigint UNSIGNED NOT NULL,
  `uid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barang_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@simplelab.com', '2026-06-02 09:13:08', '$2y$12$DB8EWqRK470IjxZLPN1OOez/wI/y6f1zbSzsMgIXLuVtaeDRwkuB6', 'admin', 'WgyBqyHFJoZy9bJSVIwfBhxDYehO1A4jo12BTN3ZwHbSQSQA1Z8jM4FMiJgv', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(2, 'Test User', 'test@example.com', '2026-06-02 09:13:09', '$2y$12$SwFCYfwv3OQNMY7/dPE/OeT4.NQZ2tNseendjwpq5Eq5inTV4wF8u', 'user', 'KBXi7RWAN5', '2026-06-02 09:13:09', '2026-06-02 09:13:09'),
(4, 'Budi', 'Budi@gmail.com', NULL, '$2y$12$WyA0EZxOOPZBMX.iPZBp5O2fJzppFOevsDYHqf5t2T01xEtc91yt.', 'user', NULL, '2026-06-02 10:05:35', '2026-06-02 10:05:35'),
(5, 'Budi', 'Budikompani@gmail.com', NULL, '$2y$12$GvaG4zmr7WiqWp4fwLigeudF2tG8/DwqGGe5abkEBQ1HzsRr6nzGi', 'dosen', NULL, '2026-06-02 10:10:05', '2026-06-02 10:14:49'),
(6, 'Dosen Budi', 'DosenBudi@gmail.com', NULL, '$2y$12$TFN1rXlQNyp4FqqTlkQypOoAW6KWee0Wy1JE0pnRxFMgQoSGQVhKq', 'dosen', NULL, '2026-06-02 10:19:52', '2026-06-02 10:19:52'),
(7, 'Adib Pratama', 'adibpratama157@gmail.com', NULL, '$2y$12$PltdWeA9GSZq4fL5NyvEAuMUwJ.m7twg9Tv4FCGp0iRXKme0ehl0y', 'user', NULL, '2026-06-02 10:46:15', '2026-06-02 10:46:15'),
(8, 'kokoh', 'jojok@gmail.com', NULL, '$2y$12$Zcgq3NxZbjxj5DmJzN/rTecIZ/dSoxX5LbnWamyfLWW1GHS8DqXiO', 'user', NULL, '2026-06-06 20:12:35', '2026-06-06 20:12:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal_labs`
--
ALTER TABLE `jadwal_labs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporan_kerusakans`
--
ALTER TABLE `laporan_kerusakans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `laporan_kerusakans_user_id_foreign` (`user_id`),
  ADD KEY `laporan_kerusakans_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `log_akses`
--
ALTER TABLE `log_akses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `log_akses_user_id_foreign` (`user_id`),
  ADD KEY `log_akses_rfid_card_id_foreign` (`rfid_card_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjamans_user_id_foreign` (`user_id`),
  ADD KEY `peminjamans_barang_id_foreign` (`barang_id`),
  ADD KEY `peminjamans_tag_rfid_id_foreign` (`tag_rfid_id`);

--
-- Indexes for table `peminjaman_ruangans`
--
ALTER TABLE `peminjaman_ruangans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `peminjaman_ruangans_user_id_foreign` (`user_id`);

--
-- Indexes for table `rfid_cards`
--
ALTER TABLE `rfid_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rfid_cards_uid_unique` (`uid`),
  ADD KEY `rfid_cards_user_id_foreign` (`user_id`);

--
-- Indexes for table `riwayat_logs`
--
ALTER TABLE `riwayat_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tag_rfids`
--
ALTER TABLE `tag_rfids`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tag_rfids_uid_unique` (`uid`),
  ADD KEY `tag_rfids_barang_id_foreign` (`barang_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jadwal_labs`
--
ALTER TABLE `jadwal_labs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporan_kerusakans`
--
ALTER TABLE `laporan_kerusakans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `log_akses`
--
ALTER TABLE `log_akses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `peminjamans`
--
ALTER TABLE `peminjamans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `peminjaman_ruangans`
--
ALTER TABLE `peminjaman_ruangans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rfid_cards`
--
ALTER TABLE `rfid_cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `riwayat_logs`
--
ALTER TABLE `riwayat_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tag_rfids`
--
ALTER TABLE `tag_rfids`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `laporan_kerusakans`
--
ALTER TABLE `laporan_kerusakans`
  ADD CONSTRAINT `laporan_kerusakans_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `laporan_kerusakans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `log_akses`
--
ALTER TABLE `log_akses`
  ADD CONSTRAINT `log_akses_rfid_card_id_foreign` FOREIGN KEY (`rfid_card_id`) REFERENCES `rfid_cards` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `log_akses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `peminjamans`
--
ALTER TABLE `peminjamans`
  ADD CONSTRAINT `peminjamans_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjamans_tag_rfid_id_foreign` FOREIGN KEY (`tag_rfid_id`) REFERENCES `tag_rfids` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `peminjamans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `peminjaman_ruangans`
--
ALTER TABLE `peminjaman_ruangans`
  ADD CONSTRAINT `peminjaman_ruangans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rfid_cards`
--
ALTER TABLE `rfid_cards`
  ADD CONSTRAINT `rfid_cards_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tag_rfids`
--
ALTER TABLE `tag_rfids`
  ADD CONSTRAINT `tag_rfids_barang_id_foreign` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
