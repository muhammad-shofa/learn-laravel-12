-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2025 at 02:21 AM
-- Server version: 8.0.42-0ubuntu0.24.04.1
-- PHP Version: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `emma`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `clock_in` time DEFAULT NULL,
  `clock_out` time DEFAULT NULL,
  `clock_in_status` enum('ontime','late','absent','leave') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'absent',
  `clock_out_status` enum('ontime','early','late','no_clock_out') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `work_duration` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `date`, `clock_in`, `clock_out`, `clock_in_status`, `clock_out_status`, `work_duration`, `created_at`, `updated_at`) VALUES
(166, 68, '2025-06-21', '10:29:24', '16:22:02', 'late', 'ontime', 353, '2025-06-21 03:29:24', '2025-06-21 03:34:59'),
(167, 54, '2025-06-21', '10:30:16', NULL, 'late', 'no_clock_out', NULL, '2025-06-21 03:30:16', '2025-06-21 03:30:16'),
(168, 68, '2025-06-23', '13:12:34', '13:12:36', 'late', 'early', 0, '2025-06-23 06:12:34', '2025-06-23 06:12:36'),
(169, 54, '2025-05-23', '13:12:49', '13:12:52', 'late', 'early', 0, '2025-05-23 06:12:49', '2025-05-23 06:12:52'),
(170, 72, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(171, 65, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(172, 66, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(173, 68, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(174, 54, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(175, 67, '2025-06-25', NULL, NULL, 'absent', NULL, NULL, '2025-06-25 11:08:01', '2025-06-25 11:08:01'),
(176, 72, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09'),
(177, 65, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09'),
(178, 66, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09'),
(179, 68, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09'),
(180, 54, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09'),
(181, 67, '2025-06-26', NULL, NULL, 'absent', NULL, NULL, '2025-06-26 11:17:09', '2025-06-26 11:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint UNSIGNED NOT NULL,
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `employee_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gender` enum('M','F') COLLATE utf8mb4_unicode_ci NOT NULL,
  `join_date` date NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `has_account` tinyint(1) NOT NULL DEFAULT '0',
  `time_off_quota` int NOT NULL DEFAULT '12',
  `time_off_used` int NOT NULL DEFAULT '0',
  `time_off_remaining` int NOT NULL DEFAULT '12',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `position_id`, `employee_code`, `full_name`, `email`, `phone`, `gender`, `join_date`, `status`, `has_account`, `time_off_quota`, `time_off_used`, `time_off_remaining`, `created_at`, `updated_at`) VALUES
(54, 19, 'EMP-202505276139', 'Kinanti Mastutik', 'kinantimastutik12@gmail.com', '088787652374', 'F', '2025-03-03', 'active', 1, 12, 5, 7, '2025-05-26 21:31:28', '2025-06-04 06:22:54'),
(65, 18, 'EMP-202505277447', 'Herman Wijaya', 'hermanwijaya@gmail.com', '08891112656', 'M', '2025-04-09', 'active', 1, 12, 0, 12, '2025-05-26 21:43:21', '2025-06-04 06:21:03'),
(66, 18, 'EMP-202505276379', 'Ahmad Bazuri', 'ahmadbazuri@gmail.com', '08898765478', 'M', '2025-04-01', 'active', 1, 12, 0, 12, '2025-05-26 21:44:25', '2025-06-13 19:37:09'),
(67, 19, 'EMP-202505276487', 'Bagas Praduga', 'bagasbagas87@gmail.com', '088998765576', 'M', '2025-04-10', 'active', 1, 12, 4, 8, '2025-05-26 21:44:59', '2025-06-18 11:19:35'),
(68, 18, 'EMP-202505277403', 'Andreas Al Andreas', 'andreas123@gmail.com', '08887877644666', 'M', '2025-04-09', 'active', 1, 12, 8, 4, '2025-05-26 21:46:09', '2025-06-06 21:40:23'),
(72, 17, 'EMP-202506073801', 'budi', 'budi', '0886755644673', 'M', '2025-06-02', 'active', 1, 12, 0, 12, '2025-06-06 21:49:23', '2025-06-06 21:51:00');

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
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
(1, '0001_01_01_000000_create_all_emma_tables', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_10_113033_update_attendances_add_clock_out_status_and_rename_status', 2),
(5, '2025_05_13_104032_update_clock_out_status_in_attendances_table', 3),
(6, '2025_05_15_020454_add_cooldown_until_to_users_table', 4),
(7, '2025_05_19_042048_update_has_account_in_employee_table', 5),
(8, '2025_05_20_034816_add_work_duration_to_attendances_table', 6),
(9, '2025_05_20_040150_update_work_duration_type_in_attendances_table', 7),
(10, '2025_05_22_024042_add_time_off_columns_to_employees_table', 8),
(12, '2025_05_24_022019_create_positions_table_and_add_position_id_to_employees', 9),
(13, '2025_05_24_071929_update_positions_table_status_and_timestamps', 9),
(14, '2025_05_27_023939_drop_position_column_from_employees_table', 10),
(15, '2025_06_01_073139_update_salary_settings_add_position_id', 11),
(16, '2025_06_02_030650_remove_deduction_and_bonus_from_salary_settings_table', 12),
(17, '2025_06_02_045145_rename_basic_salary_column_in_salary_settings_table', 13),
(18, '2025_06_03_033529_change_salary_month_and_delete_basuc_salary_on_salaries_table', 14),
(19, '2025_06_04_115605_add_overtime_multiplier_and_standard_monthly_minutes_to_positions_table', 15),
(20, '2025_06_04_120816_rename_standard_monthly_minutes_into_standard_monthly_hours_in_positions_table', 16),
(21, '2025_06_05_040509_add_year_to_salaries_table', 17),
(22, '2025_06_16_063211_create_weekly_holidays_table', 18),
(23, '2025_06_19_193825_add_hour_and_absent_deduction_to_salaries_table', 19);

-- --------------------------------------------------------

--
-- Table structure for table `positions`
--

CREATE TABLE `positions` (
  `id` bigint UNSIGNED NOT NULL,
  `position_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `hourly_rate` decimal(10,2) DEFAULT NULL,
  `overtime_multiplier` decimal(5,2) NOT NULL,
  `standard_monthly_hours` int NOT NULL,
  `annual_salary_increase` decimal(10,2) DEFAULT NULL,
  `base_salary` decimal(10,2) DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `positions`
--

INSERT INTO `positions` (`id`, `position_name`, `description`, `hourly_rate`, `overtime_multiplier`, `standard_monthly_hours`, `annual_salary_increase`, `base_salary`, `status`, `created_at`, `updated_at`) VALUES
(17, 'UI/UX Design', 'Merancang layout yang interaktif, estetis, dan mudah digunakan, dengan mengutamakan kenyamanan serta pengalaman pengguna yang optimal.', 25000.00, 1.50, 180, 9.00, 4500000.00, 'active', '2025-06-04 06:16:06', '2025-06-04 06:16:06'),
(18, 'Fullstack Web Developer', 'Membuat sebuah website agar berfungsi dengan benar, baik dari segi visual maupun fitur menggunakan teknologi yang dibutuhkan.', 37500.00, 1.50, 200, 12.00, 7500000.00, 'active', '2025-06-04 06:18:05', '2025-06-04 06:18:05'),
(19, 'Frontend Web Developer', 'Slicing design website yang sudah dibuat oleh tim UI/UX.', 28888.89, 1.50, 180, 10.00, 5200000.00, 'active', '2025-06-04 06:21:54', '2025-06-04 06:21:54');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `salary_setting_id` bigint UNSIGNED NOT NULL,
  `year` year NOT NULL,
  `month` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hour_deduction` decimal(15,2) NOT NULL,
  `absent_deduction` decimal(15,2) NOT NULL,
  `deduction` decimal(15,2) NOT NULL,
  `bonus` decimal(15,2) NOT NULL,
  `total_salary` decimal(15,2) NOT NULL,
  `payment_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `employee_id`, `salary_setting_id`, `year`, `month`, `hour_deduction`, `absent_deduction`, `deduction`, `bonus`, `total_salary`, `payment_date`, `created_at`, `updated_at`) VALUES
(49, 54, 18, '2025', '6', 5200000.00, 0.00, 5200000.00, 0.00, 100000.00, '2025-06-23', '2025-06-23 06:25:22', '2025-06-23 06:25:22'),
(50, 65, 19, '2025', '6', 7500000.00, 0.00, 7500000.00, 0.00, 100000.00, '2025-06-23', '2025-06-23 06:25:22', '2025-06-23 06:25:22'),
(51, 67, 16, '2025', '6', 5200000.00, 0.00, 5200000.00, 0.00, 100000.00, '2025-06-23', '2025-06-23 06:25:22', '2025-06-23 06:25:22'),
(52, 68, 17, '2025', '6', 7279375.00, 0.00, 7279375.00, 0.00, 520625.00, '2025-06-23', '2025-06-23 06:25:22', '2025-06-23 06:25:22');

-- --------------------------------------------------------

--
-- Table structure for table `salary_settings`
--

CREATE TABLE `salary_settings` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `position_id` bigint UNSIGNED DEFAULT NULL,
  `default_salary` decimal(15,2) NOT NULL,
  `effective_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_settings`
--

INSERT INTO `salary_settings` (`id`, `employee_id`, `position_id`, `default_salary`, `effective_date`, `created_at`, `updated_at`) VALUES
(16, 67, 19, 5300000.00, '2025-06-04', '2025-06-04 06:24:56', '2025-06-04 06:24:56'),
(17, 68, 19, 7800000.00, '2025-06-04', '2025-06-04 06:25:18', '2025-06-04 06:25:18'),
(18, 54, 19, 5300000.00, '2025-06-04', '2025-06-04 06:25:40', '2025-06-04 06:25:40'),
(19, 65, 19, 7600000.00, '2025-06-04', '2025-06-04 06:26:27', '2025-06-04 06:26:27');

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
('hs0LvsTSmx2IKNPsY8gJUo1NRlcsLDyetzLqamQ9', 32, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSERxMDZIdlhDcUdLS1JXOWU4cFBrMTc0dmRLMXhJb3piTHkzUzVjMyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hdHRlbmRhbmNlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MzI7fQ==', 1751336477),
('qauk6zOLMQr17qCSt9YeiSya6ESCRy7OUmpvsF8g', 32, '127.0.0.1', 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:139.0) Gecko/20100101 Firefox/139.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiYTZOQkFSM0xZaGVjQWkwUFBXWExLWjBlV3RuQjZlZVJtR3plcEh4SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC90aW1lLW9mZiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjMyO30=', 1750905013);

-- --------------------------------------------------------

--
-- Table structure for table `time_off_requests`
--

CREATE TABLE `time_off_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED NOT NULL,
  `request_date` date NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `time_off_requests`
--

INSERT INTO `time_off_requests` (`id`, `employee_id`, `request_date`, `start_date`, `end_date`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(48, 54, '2025-05-27', '2025-06-09', '2025-06-13', 'acara keluarga', 'approved', '2025-05-26 22:02:52', '2025-05-26 23:34:12'),
(52, 68, '2025-05-31', '2025-06-02', '2025-06-09', 'acara keluarga', 'approved', '2025-05-31 04:24:52', '2025-05-31 04:37:12'),
(53, 68, '2025-05-31', '2025-07-01', '2025-07-04', 'sssss', 'rejected', '2025-05-31 04:39:35', '2025-06-01 00:14:06'),
(54, 68, '2025-06-04', '2025-06-05', '2025-06-06', 'jhghj', 'rejected', '2025-06-03 21:00:54', '2025-06-18 11:18:09'),
(55, 54, '2025-06-05', '2025-06-05', '2025-06-09', 'qwqw', 'rejected', '2025-06-05 00:51:25', '2025-06-18 11:18:13'),
(56, 67, '2025-06-18', '2025-06-17', '2025-06-20', 'p', 'approved', '2025-06-18 11:18:53', '2025-06-18 11:19:35');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `employee_id` bigint UNSIGNED DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','employee') COLLATE utf8mb4_unicode_ci NOT NULL,
  `try_login` int NOT NULL DEFAULT '5',
  `status_login` enum('active','nonactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `cooldown_until` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `password`, `role`, `try_login`, `status_login`, `created_at`, `updated_at`, `cooldown_until`) VALUES
(32, NULL, 'admin', '$2y$10$QIJNveX51wIG7uZerJBpmOVgzgngshlkpKqUNh3aMvhlH8kA/DYWC', 'admin', 5, 'active', NULL, NULL, NULL),
(47, 68, 'andreas', '$2y$12$9ViUpFUEHHCZp0IaSJYkvuV/xMNBehzr9uNQS/gIxTpa1kGRQ4Ajy', 'employee', 5, 'active', '2025-05-26 21:46:27', '2025-06-18 06:36:04', NULL),
(52, 54, 'kinanti', '$2y$12$Qega922KbCWs/C.rxJdY4uquIPL7.7wD6ZZYNSGb.3uJnvhoeE5s.', 'employee', 5, 'active', '2025-05-26 22:01:34', '2025-05-31 00:26:27', NULL),
(54, 67, 'bagas', '$2y$12$sBtdOiT.S0dsS0yxUIPz9.HqfhvXPeDi1Ui2KEMXIXhncPSmH4L5C', 'employee', 5, 'active', '2025-05-31 00:37:47', '2025-05-31 00:48:06', NULL),
(55, 65, 'herman', '$2y$12$czyrWQIvHGmM2Ze1rPkMBeGVHJQPCH0xPnIyLOsmect1vwSk3/7Yq', 'employee', 5, 'active', '2025-06-02 00:16:13', '2025-06-04 05:35:26', NULL),
(58, 72, 'budi', '$2y$12$ZQumyvn9IjXPr7wsCR17r.QZedrGR.J/iOXoYGRBXNJmP/kvz9T..', 'employee', 5, 'active', '2025-06-06 21:51:00', '2025-06-06 21:51:40', NULL),
(59, 66, 'bazuri', '$2y$12$0M0iK211FzXzAO59mNjGJekarUKcknB/VUnC.aWfX0V//aGx7fCS6', 'employee', 5, 'active', '2025-06-13 19:37:09', '2025-06-13 19:37:09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `weekly_holidays`
--

CREATE TABLE `weekly_holidays` (
  `id` bigint UNSIGNED NOT NULL,
  `max_holidays_per_week` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `days` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `weekly_holidays`
--

INSERT INTO `weekly_holidays` (`id`, `max_holidays_per_week`, `days`, `created_at`, `updated_at`) VALUES
(5, 1, '[\"Sunday\"]', '2025-06-16 19:51:41', '2025-06-22 07:56:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_employee_code_unique` (`employee_code`),
  ADD UNIQUE KEY `employees_email_unique` (`email`),
  ADD KEY `employees_position_id_foreign` (`position_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salaries_employee_id_foreign` (`employee_id`),
  ADD KEY `salaries_salary_setting_id_foreign` (`salary_setting_id`);

--
-- Indexes for table `salary_settings`
--
ALTER TABLE `salary_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_settings_employee_id_foreign` (`employee_id`),
  ADD KEY `salary_settings_position_id_foreign` (`position_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `time_off_requests`
--
ALTER TABLE `time_off_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_off_requests_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD KEY `users_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `weekly_holidays`
--
ALTER TABLE `weekly_holidays`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `positions`
--
ALTER TABLE `positions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `salary_settings`
--
ALTER TABLE `salary_settings`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `time_off_requests`
--
ALTER TABLE `time_off_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `weekly_holidays`
--
ALTER TABLE `weekly_holidays`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salaries_salary_setting_id_foreign` FOREIGN KEY (`salary_setting_id`) REFERENCES `salary_settings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_settings`
--
ALTER TABLE `salary_settings`
  ADD CONSTRAINT `salary_settings_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_settings_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `time_off_requests`
--
ALTER TABLE `time_off_requests`
  ADD CONSTRAINT `time_off_requests_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
