-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03 نوفمبر 2026 الساعة 21:15
-- إصدار الخادم: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `billing_system`
--

-- --------------------------------------------------------

--
-- بنية الجدول `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paypal_client_id` varchar(255) DEFAULT NULL,
  `stripe_public_key` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`, `created_at`, `paypal_client_id`, `stripe_public_key`) VALUES
(1, 'ganakim2001@gmail.com', '$2y$10$DEjWcprMkG/Nitnz/zEGCeB774JjiP/Nwfu0FaSUBnI1Fyvjyy1F2', '2026-11-02 19:28:23', NULL, NULL),
(2, 'ganakim@gmail.com', '$2y$10$bRF/.m3pAuFtX5EXfARkheSPuyOmNoP1m2Prq43t5zHwpCTT.x2kW', '2026-11-02 19:29:54', 'BAAZLEm2CBOeQ_lpX79u4OSdzz1YhzXKYAZxdFaoKE6CIYQiXYWUkR45g5CT_1NQ191mKkhNgTMtBdL8s0', NULL),
(3, 'sam@gamil.com', '$2y$10$Vj3fgv2C0.EAgcvlJ2Y0g.cr5YVfvtuCEk2hEZER8QeL.h1d5RmYO', '2026-11-02 19:37:27', NULL, NULL),
(4, 'lila@gmail.com', '$2y$10$7ttU2GRfxS5pS5iRsQNwyuIgcVknYm/GTasn6QTHt6WnTCLEprf5u', '2026-11-02 20:35:04', NULL, NULL),
(5, 'lama@gmail.com', '$2y$10$fBGs6nzBdATAcFnQLYKtH.fe50aXbXKBgAQpxKI31ipv7vuGYQZtG', '2026-11-02 20:43:24', NULL, NULL),
(6, 'l@gmail.com', '$2y$10$MAP15Wr6zv0UZyQj8M/vkOrZSbQ7eYnD5oVZKQZnBT1S65hYHorXu', '2026-11-02 20:44:35', NULL, NULL),
(7, 'm@gmail.com', '$2y$10$IiXcykgSjyXYvDuNlfrWH.2wXWGuuIH4MAXKfOcO6MDry/bb.6VnS', '2026-11-02 20:54:09', NULL, NULL);

-- --------------------------------------------------------

--
-- بنية الجدول `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `clients`
--

INSERT INTO `clients` (`id`, `admin_id`, `name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(1, 0, 'سام', 'admin@store.com', NULL, '4452102333', 'القاهرة , جوارجامعة القاهرة ', '2026-11-02 11:51:11'),
(2, 0, 'سارة', 'ganakim2001@gmail.com', '$2y$10$0yKRD0jOBLVpNfGzPDn.Quffl3FINuDCgnzqjN6NPWuFXxS.SqQsa', '+96775235555', 'إب , الظهار', '2026-11-02 17:53:41'),
(4, 0, 'محمد', '231451@quni.edu.ye', '$2y$10$R9o0KotWuNnA3iGaLw8MSu3A4f9QXum2co5OO3mZ8GyKocXt482ke', '+967739234159', 'مصر', '2026-11-02 18:14:18'),
(5, 4, 'محمد', 'sam@gamil.com', '$2y$10$xvIu7izwNKViWPqrsuvP4eitWnpDAUwWH0EAx424zZLjh3UilUISm', '+967739234159', 'اليمن', '2026-11-03 19:17:24'),
(6, 4, 'سارة', 'lila@gmail.com', '$2y$10$C.BvLYYmfm5kJSZPIk0OC.WFaDqp0quCjp79xAvf2awHU4rFRB4ba', '+967739234159', 'مصر', '2026-11-03 19:22:33');

-- --------------------------------------------------------

--
-- بنية الجدول `finances`
--

CREATE TABLE `finances` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `title` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `category` varchar(100) NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `finances`
--

INSERT INTO `finances` (`id`, `admin_id`, `type`, `title`, `amount`, `category`, `date`, `created_at`) VALUES
(1, 4, 'income', 'مشروع تخرج', 200.00, 'راتب', '2026-11-03', '2026-11-03 19:45:30');

-- --------------------------------------------------------

--
-- بنية الجدول `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `reference_number` varchar(50) NOT NULL,
  `client_id` int(11) NOT NULL,
  `issue_date` date NOT NULL,
  `due_date` date NOT NULL,
  `total_amount` decimal(10,2) DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` enum('paid','pending','overdue') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `invoices`
--

INSERT INTO `invoices` (`id`, `admin_id`, `reference_number`, `client_id`, `issue_date`, `due_date`, `total_amount`, `description`, `status`, `created_at`) VALUES
(1, 0, 'INV-1001', 1, '2026-11-02', '2026-05-20', 2000.00, NULL, 'pending', '2026-11-02 11:54:41'),
(2, 0, 'INV-2026-1522', 4, '2020-02-01', '0000-00-00', 20.00, NULL, 'paid', '2026-11-02 18:35:03'),
(15, 4, 'INV-2026-3929', 6, '2026-11-03', '2026-11-01', 2000.00, NULL, 'overdue', '2026-11-03 19:32:14');

-- --------------------------------------------------------

--
-- بنية الجدول `invoice_items`
--

CREATE TABLE `invoice_items` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `invoice_items`
--

INSERT INTO `invoice_items` (`id`, `invoice_id`, `service_name`, `quantity`, `unit_price`, `subtotal`, `description`, `amount`) VALUES
(4, 15, '', 0, 0.00, 0.00, 'تأخير فاتورة الكهرباء', 2000.00);

-- --------------------------------------------------------

--
-- بنية الجدول `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT 'info',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `notifications`
--

INSERT INTO `notifications` (`id`, `admin_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(4, 4, 'info', 'فاتورة جديدة', 'تم إصدار الفاتورة رقم INV-2026-3929 بمبلغ $2,000.00', 0, '2026-11-03 19:32:14'),
(5, 2, 'info', 'تسجيل دخول ناجح', '👋أهلا بك يا ganakim في لوحة تحكم FinTech Pro  نتمنى لك تجربة ممتعة وإدارة مالية ناجحة!', 0, '2026-11-03 20:03:13');

-- --------------------------------------------------------

--
-- بنية الجدول `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- إرجاع أو استيراد بيانات الجدول `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'مدير النظام', 'admin@fintech.local', '$2y$10$UAezADQF2afMNzKxiofEd.4haZo4npJjNa8SqN3SbE5uMIZGOR3Xm', '2026-11-02 13:20:48'),
(2, 'Eman Mohammed', 'kskmsmsnsn@gmail.com', '$2y$10$XPXI7wcxFyFT4Yb3n7//MuDtmzbOIWEluCLCzsw/KsaGnQELoO9BO', '2026-11-02 16:55:09'),
(3, 'Kim San', 'ganakim2001@gmail.com', '$2y$10$/cae9OdBNnRVugl9AYqyEeCGx24EQOX93cx8Uc1NxezsDH7KFJUu2', '2026-11-02 20:45:30'),
(4, 'Sara Lima', 'lilasam736313293@gmail.com', '$2y$10$hk9ewGWPWHd5QbQxzQVEXOKoNilLRm3iXlI4vFFknaaRcTJFEnRca', '2026-11-03 18:28:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `finances`
--
ALTER TABLE `finances`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `client_id` (`client_id`);

--
-- Indexes for table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_id` (`invoice_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `finances`
--
ALTER TABLE `finances`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- قيود الجداول المُلقاة.
--

--
-- قيود الجداول `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE;

--
-- قيود الجداول `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
