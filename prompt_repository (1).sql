-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2026 at 07:11 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `prompt_repository`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `color`, `icon`) VALUES
(2, 'Marketing', NULL, NULL, NULL),
(3, 'DevOps', NULL, NULL, NULL),
(4, 'SQL', NULL, NULL, NULL),
(5, 'Testing', NULL, NULL, NULL),
(7, 'joseph', 'ok codedddfff', 'success', 'code-slash'),
(8, 'hhhhhhh', 'okkkk', 'primary', 'gear'),
(10, 'hamdolah', 'kokoko', 'success', 'gear');

-- --------------------------------------------------------

--
-- Table structure for table `prompts`
--

CREATE TABLE `prompts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `description` text DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `visibility` enum('public','private') DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `usage_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prompts`
--

INSERT INTO `prompts` (`id`, `title`, `content`, `user_id`, `category_id`, `created_at`, `description`, `tags`, `visibility`, `updated_at`, `usage_count`) VALUES
(2, 'SEO Landing Page', 'Write a high-converting landing page for a digital product.', 2, 2, '2026-03-24 20:47:54', NULL, NULL, NULL, NULL, 0),
(4, 'SQL Optimization', 'Optimize a query using indexes and reduce execution time.', 3, 4, '2026-03-24 20:47:54', NULL, NULL, NULL, NULL, 0),
(5, 'Unit Testing PHP', 'Write PHPUnit tests for authentication logic.', 2, 5, '2026-03-24 20:47:54', NULL, NULL, NULL, NULL, 0),
(6, 'what', 'hey hooo get some prompts over here', 6, 2, '2026-03-25 10:29:45', 'dor insert data', 'SQL', 'public', '2026-03-26 01:47:40', 0),
(14, 'last one', 'ddddddd', 6, 5, '2026-03-25 13:37:20', 'good but not good', 'plan', 'public', NULL, 0),
(15, 'new update ok', 'wwwwwwwwwwwwwwwwwwwwwwwwwwww', 7, 4, '2026-03-25 13:38:07', 'eeeeeeeeeeeeeeeeeee', 'plelel', 'public', '2026-03-26 01:45:43', 0),
(18, 'new dev', 'newsssssssss\r\nsdafs\r\nasdfdsafasdfds', 8, 5, '2026-03-26 01:04:53', 'new', 'ok', 'public', NULL, 0),
(19, 'asdfffff', 'sasadsasss', 7, 5, '2026-03-26 01:14:07', 'asfba', 'dffd', 'public', NULL, 0),
(20, 'general', 'salamo', 9, 8, '2026-03-26 13:35:37', 'salam', 'api', 'public', NULL, 0),
(21, 'REST', 'A', 9, 3, '2026-03-27 08:25:38', 'SALAAAM', 'REST', 'public', NULL, 0),
(22, 'YOUFOS', 'ANA LWL', 9, 4, '2026-03-27 08:26:03', 'ANA LWL', 'ANA LWL', 'public', NULL, 0),
(23, 'WA ANA LWL', 'ANA LWLWL', 9, 5, '2026-03-27 08:26:29', 'ANA LWL', 'LWL', 'public', NULL, 0),
(24, 'FinTech Mobile Banking Experience', 'Create a fintech mobile app focused on seamless banking experience. Include onboarding, account overview, instant transfers, expense insights, and fraud alerts. Use a clean UI, biometric security, and fast interactions.', 10, 4, '2026-03-27 10:25:36', 'mobile app', '', 'public', NULL, 0),
(25, 'okoksss', 'ljoijioj', 4, 7, '2026-03-29 20:44:41', 'lkln;lk', 'ijjl', 'public', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','Developer') DEFAULT 'Developer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'youssef', 'youssef@devgenius.com', '$2y$10$replace_this_hash', 'Developer', '2026-03-24 20:47:54'),
(3, 'amine', 'amine@devgenius.com', '$2y$10$replace_this_hash', 'Developer', '2026-03-24 20:47:54'),
(4, 'joseph007', 'jdirayoussef@gmail.com', '$2y$10$fomgWbbccSJSxKwkHk1nv.HxeMT.DJX1AfXLFeeowQOvwupDqbUcG', 'admin', '2026-03-24 21:12:11'),
(6, 'Admin', 'youssefjdira@hotmail.com', '$2y$10$FxmLwo2b1e2Ld4PxzjnVr.FdseBZcg7os1zWd0iHqv097Hu4FlGVq', 'admin', '2026-03-24 21:22:29'),
(7, 'josephk', 'siradesign.contact@gmail.com', '$2y$10$4SMtUi9zSfCavwHWgsRd8.drWS1LXEnQ2YtYDtdQZeD/5o2Nf84U.', 'Developer', '2026-03-24 22:04:42'),
(8, 'NewDev', 'newdev@gmail.com', '$2y$10$7cCArH/ALTkPbOq4xN2PJOgW24JQ9InfPmBoOaU3.5Jxds9jQdCYK', 'Developer', '2026-03-26 01:03:23'),
(9, 'youss.exe', 'tkgg0117@gmail.com', '$2y$10$GrXowuIxRdIlt6IH0nWEpeG0/5H4/AVOahTxYm6Wfwm9KzD295l8y', 'admin', '2026-03-26 13:34:27'),
(10, 'zinebtalghmet', 'zinebtalghmet2022@gmail.com', '$2y$10$zlF30w1OP..MJREmyXCFd.KVGdGhuhbq9.Dhrc6LVOTMH7FXH.3GO', 'admin', '2026-03-27 10:22:51'),
(11, 'new', 'new@gmail.com', '$2y$10$8ZM8Sku.EZhvjJYIXFwNzeKU1l8wYCQV6l./zNhhhoz8k1UOd846e', 'Developer', '2026-03-29 20:49:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `prompts`
--
ALTER TABLE `prompts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prompts`
--
ALTER TABLE `prompts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prompts`
--
ALTER TABLE `prompts`
  ADD CONSTRAINT `prompts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prompts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
