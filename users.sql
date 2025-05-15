-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 01:43 PM
-- Server version: 10.6.19-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `specialization` text DEFAULT NULL,
  `qualification` text DEFAULT NULL,
  `blood_group` varchar(255) DEFAULT NULL,
  `emergency_contact` text DEFAULT NULL,
  `meta_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_data`)),
  `status` varchar(255) NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `address`, `date_of_birth`, `gender`, `department_id`, `employee_id`, `is_active`, `specialization`, `qualification`, `blood_group`, `emergency_contact`, `meta_data`, `status`) VALUES
(1, 2, 'Admin User', 'admin@admin.com', NULL, NULL, '$2y$12$1GT/43AXVOrw5zaxLaFfiOtraipqijtZdCGXYL96qO2G06p0F7MsC', NULL, '2025-05-14 01:42:11', '2025-05-14 01:42:11', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(2, 1, 'Super Admin User', 'super-admin@admin.com', NULL, NULL, '$2y$12$zzam6kfAlxXxscNfEFE/fO3KGXdyANKGWGf1hu8lrLe6lsjW1SM1.', NULL, '2025-05-15 04:25:54', '2025-05-15 04:25:54', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(3, 3, 'Doctor User', 'doctor@admin.com', NULL, NULL, '$2y$12$EVoGhfFZurkh7mtltXd3Kuy3jZHv2CwIlbX1lmrUWVPc6SYCihoTa', NULL, '2025-05-15 04:25:54', '2025-05-15 04:25:54', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(4, 4, 'Patient User', 'patient@admin.com', NULL, NULL, '$2y$12$PdofW0ojbc3rbVKKtakgq.FtwDeNw8EDW0KTvmAzZYJfK/S0w.kC.', NULL, '2025-05-15 04:25:55', '2025-05-15 04:25:55', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(5, 5, 'Receptionist User', 'receptionist@admin.com', NULL, NULL, '$2y$12$/t/SC/eIpCUwRoNO.AuRiecolQSCAnWA9kQVAXQQCzhscE/sPhAEG', NULL, '2025-05-15 04:25:55', '2025-05-15 04:25:55', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(6, 6, 'Nurse User', 'nurse@admin.com', NULL, NULL, '$2y$12$Dr.gD0TSJcBy9OXPNQOCyeTGmWit768J.5OsSoT5leXrYYvTdgPFe', NULL, '2025-05-15 04:25:55', '2025-05-15 04:25:55', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(7, 7, 'Pharmacist User', 'pharmacist@admin.com', NULL, NULL, '$2y$12$7ZinTBgA3rwXh1S1JcjPEuIqum2XbwwyP.ZUxC7DGloXeztY.ZZfW', NULL, '2025-05-15 04:25:55', '2025-05-15 04:25:55', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(8, 8, 'Lab Technician User', 'lab-technician@admin.com', NULL, NULL, '$2y$12$LE9/.IMLzifYNuKP.UAexu7CiShbhWGS7RxgD/eQDxWrmBe1FlAmy', NULL, '2025-05-15 04:25:56', '2025-05-15 04:25:56', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(9, 9, 'Accountant User', 'accountant@admin.com', NULL, NULL, '$2y$12$56voxXiCgd5OW1k7uLCsCOetrPgf6kUHkhTCZE5AOvJxjcWjfO8fi', NULL, '2025-05-15 04:25:56', '2025-05-15 04:25:56', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(10, 10, 'Radiologist User', 'radiologist@admin.com', NULL, NULL, '$2y$12$Bo7GErVaqdE/fcVT1Qtr2eqZjMKFAJH78Q22SP1XbD2I4Cm9abQWa', NULL, '2025-05-15 04:25:56', '2025-05-15 04:25:56', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(11, 11, 'Department Head User', 'department-head@admin.com', NULL, NULL, '$2y$12$ndwp4rAr9MO2wQuh8M666ufYUcQ8n2hyn7xR5Cc01z9GYjL3Bz2YW', NULL, '2025-05-15 04:25:57', '2025-05-15 04:25:57', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(12, 12, 'Insurance Agent User', 'insurance-agent@admin.com', NULL, NULL, '$2y$12$7iFuI1nA/DhKf3Ys//dQfOX9AE4Emhp23goItixHBets2HCy4s4N2', NULL, '2025-05-15 04:25:57', '2025-05-15 04:25:57', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(13, 13, 'Maintenance Staff User', 'maintenance@admin.com', NULL, NULL, '$2y$12$a6iV.mimfsqJ0i7wNU8vL.fqYvZWdny.EdwYWCB3StjDn9g7E0Z.a', NULL, '2025-05-15 04:25:57', '2025-05-15 04:25:57', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active'),
(14, 14, 'IT Support User', 'it-support@admin.com', NULL, NULL, '$2y$12$7VM3qvUhJCpTX3TUTeOkS.FS15fyrUQ50EX.KHey5r/2hg8LifGkO', NULL, '2025-05-15 04:25:57', '2025-05-15 04:25:57', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  ADD KEY `users_role_id_foreign` (`role_id`),
  ADD KEY `users_department_id_foreign` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
