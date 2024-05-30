-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Út 07.Máj 2024, 19:31
-- Verzia serveru: 8.0.36-0ubuntu0.22.04.1
-- Verzia PHP: 8.3.3-1+ubuntu22.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáza: `webteFinal`
--

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Backup`
--

CREATE TABLE `Backup` (
  `id` int NOT NULL,
  `question_id` int NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Sťahujem dáta pre tabuľku `Backup`
--

INSERT INTO `Backup` (`id`, `question_id`, `note`, `created_at`) VALUES
(1, 5, 'backup1', '2024-05-06 18:06:14'),
(2, 5, 'backup2', '2024-05-06 18:07:00'),
(3, 6, 'backupo1', '2024-05-06 18:11:07'),
(4, 6, 'backupo2', '2024-05-06 18:12:27');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `BackupResponses`
--

CREATE TABLE `BackupResponses` (
  `backup_response_id` int NOT NULL,
  `backup_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `option_id` int DEFAULT NULL,
  `response_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `BackupResponses`
--

INSERT INTO `BackupResponses` (`backup_response_id`, `backup_id`, `user_id`, `option_id`, `response_text`) VALUES
(1, 1, 7, 1, ''),
(2, 1, 7, 1, ''),
(3, 1, 7, 2, ''),
(4, 2, 7, 2, ''),
(5, 2, 7, 2, ''),
(6, 2, 7, 2, ''),
(7, 2, 7, 2, ''),
(8, 3, 7, NULL, 'ano ano'),
(9, 3, 7, NULL, 'ano ano'),
(10, 3, 7, NULL, 'juchuchu'),
(11, 4, 7, NULL, 'nie nie'),
(12, 4, 7, NULL, 'nie'),
(13, 4, 7, NULL, 'jj'),
(14, 4, 7, NULL, 'ok'),
(15, 4, 7, NULL, 'ok');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `QuestionOptions`
--

CREATE TABLE `QuestionOptions` (
  `option_id` int NOT NULL,
  `question_id` int NOT NULL,
  `option_text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `QuestionOptions`
--

INSERT INTO `QuestionOptions` (`option_id`, `question_id`, `option_text`) VALUES
(1, 5, 'ano funguje'),
(2, 5, 'nefunguje'),
(3, 8, 'ano funguje'),
(4, 8, 'nefunguje');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Questions`
--

CREATE TABLE `Questions` (
  `question_id` int NOT NULL,
  `user_id` int NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','open_ended') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `wordcloud` tinyint(1) NOT NULL DEFAULT '1',
  `question_code` varchar(5) NOT NULL,
  `options_count` enum('single','multiple') DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `note_at_close` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `Questions`
--

INSERT INTO `Questions` (`question_id`, `user_id`, `question_text`, `question_type`, `subject`, `active`, `wordcloud`, `question_code`, `options_count`, `created_at`, `updated_at`, `note_at_close`) VALUES
(1, 7, 'aaaaa:?', 'open_ended', 'skuska1', 1, 0, '9YNAH', NULL, '2024-05-06 19:34:07', '2024-05-06 19:34:07', NULL),
(2, 7, 'BBB?', 'open_ended', 'bbbb', 1, 1, '6VU5F', NULL, '2024-05-02 12:23:33', '2024-05-02 12:23:33', NULL),
(3, 7, 'CSCS', 'open_ended', 'ccc', 1, 1, 'BJDQP', NULL, '2024-05-02 12:42:32', '2024-05-02 12:42:32', NULL),
(4, 9, 'skuska aaaaaaaaaaaaa', 'open_ended', 'ada', 1, 1, 'H2Y1B', NULL, '2024-05-06 12:54:21', '2024-05-06 12:54:21', NULL),
(5, 7, 'funguje ten backup?', 'multiple_choice', 'skusam backup', 1, 1, 'C39TB', 'single', '2024-05-06 18:05:25', '2024-05-06 18:05:25', NULL),
(6, 7, 'backup skuska otvorena', 'open_ended', 'backup skuska 2', 1, 1, 'BZVHW', NULL, '2024-05-06 18:10:22', '2024-05-06 18:10:22', NULL),
(7, 7, 'funguje ten backup?', 'multiple_choice', 'skusam backup', 1, 1, 'H3IZO', 'single', '2024-05-07 19:08:42', '2024-05-07 19:08:42', NULL),
(8, 7, 'funguje ten backup?', 'multiple_choice', 'skusam backup', 1, 1, 'BR7ES', 'single', '2024-05-07 19:16:02', '2024-05-07 19:16:02', NULL);

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Responses`
--

CREATE TABLE `Responses` (
  `response_id` int NOT NULL,
  `question_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `option_id` int DEFAULT NULL,
  `response_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `Responses`
--

INSERT INTO `Responses` (`response_id`, `question_id`, `user_id`, `option_id`, `response_text`, `created_at`) VALUES
(13, 5, 7, 1, '', '2024-05-06 18:07:07'),
(14, 5, 7, 2, '', '2024-05-06 18:07:12'),
(23, 6, 7, NULL, 'aaaa', '2024-05-06 18:12:38'),
(24, 6, 7, NULL, 'abab', '2024-05-06 18:12:46'),
(25, 6, 7, NULL, 'avav', '2024-05-06 18:12:54'),
(26, 6, 7, NULL, 'avav', '2024-05-06 18:13:01'),
(27, 6, 7, NULL, 'aa', '2024-05-06 19:54:31');

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Sessions`
--

CREATE TABLE `Sessions` (
  `session_id` int NOT NULL,
  `session_code` varchar(5) NOT NULL,
  `question_id` int NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL,
  `ended_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Štruktúra tabuľky pre tabuľku `Users`
--

CREATE TABLE `Users` (
  `user_id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(7, 'admin', '$2y$10$SL/RO2YeUqV6DrIAxolfL.YQOhlCX35W.AhlIY4n19k2qm2Y.kioO', 'admin@admin.sk', 'admin', '2024-04-29 14:28:13', '2024-04-29 14:28:13'),
(9, 'rado', '$2y$10$cy7YF0zahZbA9OFjZOHJ5Oj1DOGlNbKgBYsA3CGoY38ABsYqyaIbm', 'radoslav.kubalec17@gmail.com', 'user', '2024-04-29 14:28:37', '2024-04-29 14:28:37'),
(10, 'jurkoheslorado1', '$2y$10$cy7YF0zahZbA9OFjZOHJ5Oj1DOGlNbKgBYsA3CGoY38ABsYqyaIbm', 'radoslav.kubalec17@gmail.com', 'user', '2024-04-30 09:41:47', '2024-04-30 09:41:47'),
(11, 'juko', '$2y$10$XvDLu8N2IcL1PVLV7ox6VetPSWfLizJLHbqlcdHSUKbxDvMfDzuXu', 'jurajlopusek@gmail.com', 'user', '2024-05-01 10:51:39', '2024-05-01 10:51:39'),
(12, 'ferko', '$2y$10$zRXLkd2SPa1OCurCe86Qs.PNBIeD8GB0zPh/wX.qHVkywlJeDAWtW', 'ferko@sk.sk', 'user', '2024-05-01 10:51:59', '2024-05-01 10:51:59');

--
-- Kľúče pre exportované tabuľky
--

--
-- Indexy pre tabuľku `Backup`
--
ALTER TABLE `Backup`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Backup_ibfk_1` (`question_id`);

--
-- Indexy pre tabuľku `BackupResponses`
--
ALTER TABLE `BackupResponses`
  ADD PRIMARY KEY (`backup_response_id`),
  ADD KEY `USER` (`user_id`),
  ADD KEY `OPTION` (`option_id`),
  ADD KEY `BackupResponses_ibfk_1` (`backup_id`);

--
-- Indexy pre tabuľku `QuestionOptions`
--
ALTER TABLE `QuestionOptions`
  ADD PRIMARY KEY (`option_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `Questions`
--
ALTER TABLE `Questions`
  ADD PRIMARY KEY (`question_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexy pre tabuľku `Responses`
--
ALTER TABLE `Responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `USER` (`user_id`),
  ADD KEY `QUESTION` (`question_id`),
  ADD KEY `OPTION` (`option_id`);

--
-- Indexy pre tabuľku `Sessions`
--
ALTER TABLE `Sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexy pre tabuľku `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT pre exportované tabuľky
--

--
-- AUTO_INCREMENT pre tabuľku `Backup`
--
ALTER TABLE `Backup`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pre tabuľku `BackupResponses`
--
ALTER TABLE `BackupResponses`
  MODIFY `backup_response_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pre tabuľku `QuestionOptions`
--
ALTER TABLE `QuestionOptions`
  MODIFY `option_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pre tabuľku `Questions`
--
ALTER TABLE `Questions`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pre tabuľku `Responses`
--
ALTER TABLE `Responses`
  MODIFY `response_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pre tabuľku `Sessions`
--
ALTER TABLE `Sessions`
  MODIFY `session_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `Backup`
--
ALTER TABLE `Backup`
  ADD CONSTRAINT `Backup_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `BackupResponses`
--
ALTER TABLE `BackupResponses`
  ADD CONSTRAINT `BackupResponses_ibfk_1` FOREIGN KEY (`backup_id`) REFERENCES `Backup` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `BackupResponses_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `BackupResponses_ibfk_3` FOREIGN KEY (`option_id`) REFERENCES `QuestionOptions` (`option_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `QuestionOptions`
--
ALTER TABLE `QuestionOptions`
  ADD CONSTRAINT `QuestionOptions_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `Questions`
--
ALTER TABLE `Questions`
  ADD CONSTRAINT `Questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `Responses`
--
ALTER TABLE `Responses`
  ADD CONSTRAINT `OPTION` FOREIGN KEY (`option_id`) REFERENCES `QuestionOptions` (`option_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `QUESTION` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `USER` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `Sessions`
--
ALTER TABLE `Sessions`
  ADD CONSTRAINT `Sessions_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
