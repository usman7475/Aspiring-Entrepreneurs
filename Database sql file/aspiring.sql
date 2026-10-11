-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2026 at 12:49 PM
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
-- Database: `aspiring`
--

-- --------------------------------------------------------

--
-- Table structure for table `discussions`
--

CREATE TABLE `discussions` (
  `id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `parent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discussions`
--

INSERT INTO `discussions` (`id`, `project_id`, `user_id`, `message`, `created_at`, `parent_id`) VALUES
(16, 6, 11, 'THIS IS A TEST MSG ON DISC PAGE ', '2025-01-11 21:47:28', NULL),
(22, 10, 14, 'good', '2025-04-21 14:30:22', NULL),
(29, 7, 2, '<p>hmmmm</p>\r\n', '2025-04-21 16:12:12', NULL),
(30, 9, 8, '<p>hmmm</p>\r\n', '2025-05-04 11:27:11', NULL),
(31, 8, 8, '<p>gfff</p>\r\n', '2025-05-04 11:51:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `feedback_from` int(11) NOT NULL,
  `feedback_to` int(11) NOT NULL,
  `feedback_text` text NOT NULL,
  `rating` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `feedback_from`, `feedback_to`, `feedback_text`, `rating`, `created_at`) VALUES
(1, 17, 7, 'its a great', 4, '2026-03-26 10:53:41');

-- --------------------------------------------------------

--
-- Table structure for table `flagged_posts`
--

CREATE TABLE `flagged_posts` (
  `id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `flagged_by` int(11) NOT NULL,
  `flagged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `flagged_posts`
--

INSERT INTO `flagged_posts` (`id`, `discussion_id`, `flagged_by`, `flagged_at`) VALUES
(5, 22, 2, '2025-04-21 16:03:25'),
(7, 29, 2, '2025-04-21 16:12:17'),
(8, 30, 8, '2025-05-04 11:49:21'),
(9, 22, 14, '2026-03-26 09:38:23');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `title`, `description`, `created_by`, `created_at`) VALUES
(4, 'The Use of social media', 'The Use of social media -This is a TEST data ', 7, '2024-10-13 16:37:06'),
(5, 'The Impact of Artificial Intelligence on Modern Healthcare', 'Discuss the potential and challenges of AI in diagnosing diseases, personalized medicine, robotic surgeries, and improving patient outcomes.', 7, '2025-01-10 23:03:07'),
(6, 'The Impact of Artificial Intelligence on Modern Healthcare', 'Discuss the potential and challenges of AI in diagnosing diseases, personalized medicine, robotic surgeries, and improving patient outcomes.', 7, '2025-01-10 23:03:07'),
(7, 'Challenges in Renewable Energy Adoption', 'Explore the barriers to adopting renewable energy sources like solar, wind, and hydropower, including cost, infrastructure, and policy challenges.', 7, '2025-01-10 23:03:27'),
(8, 'The Role of Social Media in Academic Research Dissemination', 'Analyze how social media platforms are used to share and discuss academic research and the impact this has on accessibility and collaboration.', 8, '2025-01-10 23:05:20'),
(9, 'Ethical Implications of AI-Driven Decision Making', 'Explore the ethical considerations of using AI in critical decision-making processes in areas such as law enforcement, hiring, and healthcare.', 8, '2025-01-10 23:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `role` enum('User','Agent','Admin','') NOT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `profile_picture`, `bio`, `role`, `status`, `created_at`) VALUES
(1, 'M Ali', 'M.Ali@gmail.com', '$2y$10$kftEVVbZIYTb45O6al7u5eoc4fAcWDmL9kQCFvIWTdUSD8781PzRG', 'uploads/agent-18707_1920.jpg', 'I am good', 'User', 'active', '2024-10-13 15:05:04'),
(2, 'rehan mirza', 'admin@gmail.com', '$2y$10$5YrRw.zrxwVmo70yiVlOEODsrjvrptQ9vkcWcHn2WqhgnEzYgxs8e', 'uploads/agent-18707_1920.jpg', 'Admin', 'Admin', 'active', '2024-10-13 17:55:57'),
(7, 'Musadiq Ali', 'Musadiq.office@gmail.com', '$2y$10$oz4F66ZsJ8SjVjPtDdm4Yeoool4PQNvpDKlUWV2TPq1Xby5o2huU.', 'uploads/02c4c5b718c7e55c276e11d019b4a06c.jpg', 'Test Bio', 'User', 'active', '2024-10-14 22:27:52'),
(8, 'user1', 'user1@gmail.com', '$2y$10$nsu/g5.UacB2W3QoaZQvAOuVZyxsZNDAg6WGk8shQqb8NnLW8o2Wa', NULL, NULL, 'User', 'active', '2025-01-10 23:04:30'),
(9, 'user2', 'user2@gmail.com', '$2y$10$FEVYyGycApjQdQI.gOcKuux6Uo2IwxPTkkmADvooYuqV4Gzvamlfi', NULL, NULL, 'User', 'active', '2025-01-11 21:33:52'),
(10, 'user3', 'user3@gmail.com', '$2y$10$BbwGvjBGjnSay4gqyEvBFeR9jbEbUwd9CLtEizG4Q1Q8vyWwNHyyu', 'uploads/20230610_152651.jpg', 'This is bio for the user', 'User', 'active', '2025-01-11 21:42:24'),
(11, 'user4', 'user4@gmail.com', '$2y$10$UIbsuc19fXBwj/niENc1sOc5h0DT4ykGRUO7cLqS3CUzNOU1UcAsm', NULL, NULL, 'User', 'active', '2025-01-11 21:45:58'),
(12, 'user5', 'user5@gmail.com', '$2y$10$Qd3KulMqYlVSgV2UrOA/A.RLiuIvxjFRaYPZ.0.dKVTRseDtZ9uZm', NULL, NULL, 'User', 'active', '2025-01-11 21:49:42'),
(13, 'rehan g', 'rehan@gmail.com', '$2y$10$3aP3ezbqhFel0466J61KeOXt4t3U0cBUn.MgX6vA5i5rSwT4GK7Rm', NULL, NULL, 'User', 'active', '2025-01-14 16:35:10'),
(14, 'rehan', 's@gmail.com', '$2y$10$4YhF/bv0n43FWZiRvY1I.ur8VKFCT78yd6ccne0s2DKvq4HefbVai', 'uploads/agent-18707_1920.jpg', 'I am developer', 'User', 'active', '2025-04-21 12:17:00'),
(16, 'rehan', 'rehan2@gmail.com', '$2y$10$Gt0p2KVNKDpBk7icQr65NuHuIcXLrqhOTZfDX7ahdjdHaHqYDnwKy', NULL, NULL, 'User', 'active', '2026-03-26 09:30:43'),
(17, 'mirza', 'mirzau@gmail.com', '$2y$10$dJVsxBzdlLCQFAESZmjKKOF79m7YjBPgMbLfNTGxdsZcYIH9VdOxy', NULL, NULL, 'User', 'active', '2026-03-26 10:09:51'),
(18, 'agent', 'agent25@gmail.com', '$2y$10$oz4F66ZsJ8SjVjPtDdm4Yeoool4PQNvpDKlUWV2TPq1Xby5o2huU.', NULL, NULL, 'Agent', 'active', '2026-03-26 10:10:38');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE `votes` (
  `id` int(11) NOT NULL,
  `discussion_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `vote_type` enum('upvote','downvote') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`id`, `discussion_id`, `user_id`, `vote_type`) VALUES
(8, 16, 11, 'upvote'),
(12, 16, 10, 'upvote'),
(19, 22, 2, 'downvote'),
(21, 16, 2, 'upvote'),
(22, 30, 8, 'downvote'),
(23, 31, 8, 'downvote'),
(24, 29, 14, 'upvote');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discussions`
--
ALTER TABLE `discussions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_from` (`feedback_from`),
  ADD KEY `feedback_to` (`feedback_to`);

--
-- Indexes for table `flagged_posts`
--
ALTER TABLE `flagged_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `discussion_id` (`discussion_id`),
  ADD KEY `flagged_by` (`flagged_by`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discussion_id` (`discussion_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discussions`
--
ALTER TABLE `discussions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `flagged_posts`
--
ALTER TABLE `flagged_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `discussions`
--
ALTER TABLE `discussions`
  ADD CONSTRAINT `discussions_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`feedback_from`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedback_ibfk_2` FOREIGN KEY (`feedback_to`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `flagged_posts`
--
ALTER TABLE `flagged_posts`
  ADD CONSTRAINT `flagged_posts_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`),
  ADD CONSTRAINT `flagged_posts_ibfk_2` FOREIGN KEY (`flagged_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `votes`
--
ALTER TABLE `votes`
  ADD CONSTRAINT `votes_ibfk_1` FOREIGN KEY (`discussion_id`) REFERENCES `discussions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `votes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
