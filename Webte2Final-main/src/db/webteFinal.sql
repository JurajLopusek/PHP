-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+jammy2
-- https://www.phpmyadmin.net/
--
-- Hostiteľ: localhost:3306
-- Čas generovania: Út 30.Apr 2024, 09:45
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
(1, 3, 'a'),
(2, 3, 'aa'),
(3, 4, 'b'),
(4, 4, 'bbb'),
(5, 4, 'bbbbb'),
(6, 5, 'c'),
(7, 5, 'ccc'),
(8, 5, 'ccccc');

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
  `active` tinyint(1) NOT NULL,
  `question_code` varchar(5) NOT NULL,
  `options_count` enum('single','multiple') DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  `note_at_close` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Sťahujem dáta pre tabuľku `Questions`
--

INSERT INTO `Questions` (`question_id`, `user_id`, `question_text`, `question_type`, `subject`, `active`, `question_code`, `options_count`, `created_at`, `updated_at`, `note_at_close`) VALUES
(1, 7, 'otvorena1', 'open_ended', 'Predmet1', 1, '70DGU', NULL, '2024-04-30 09:36:42', '2024-04-30 09:36:42', NULL),
(2, 7, 'otvorena2', 'open_ended', 'Predmet2', 1, 'MJ289', NULL, '2024-04-30 09:36:49', '2024-04-30 09:36:49', NULL),
(3, 7, 'UzavaretaSingle1', 'multiple_choice', 'Predmet1', 1, 'IREOL', 'single', '2024-04-30 09:37:24', '2024-04-30 09:37:24', NULL),
(4, 7, 'UzavaretaSingle1', 'multiple_choice', 'Predmet1', 1, 'VSJXC', 'multiple', '2024-04-30 09:37:39', '2024-04-30 09:37:39', NULL),
(5, 7, 'UzavaretaSingle2', 'multiple_choice', 'Predmet2', 1, 'SFJZI', 'single', '2024-04-30 09:38:01', '2024-04-30 09:38:01', NULL),
(6, 9, 'otvorenaR1', 'open_ended', 'predmet1', 1, 'MWDP9', NULL, '2024-04-30 09:39:27', '2024-04-30 09:39:27', NULL),
(7, 9, 'otvorenaR2', 'open_ended', 'Predmet1', 1, 'BAECH', NULL, '2024-04-30 09:39:41', '2024-04-30 09:39:41', NULL);

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
(10, 'jurkoheslorado1', '$2y$10$cy7YF0zahZbA9OFjZOHJ5Oj1DOGlNbKgBYsA3CGoY38ABsYqyaIbm', 'radoslav.kubalec17@gmail.com', 'user', '2024-04-30 09:41:47', '2024-04-30 09:41:47');

--
-- Kľúče pre exportované tabuľky
--

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
-- AUTO_INCREMENT pre tabuľku `QuestionOptions`
--
ALTER TABLE `QuestionOptions`
  MODIFY `option_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pre tabuľku `Questions`
--
ALTER TABLE `Questions`
  MODIFY `question_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pre tabuľku `Responses`
--
ALTER TABLE `Responses`
  MODIFY `response_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Sessions`
--
ALTER TABLE `Sessions`
  MODIFY `session_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pre tabuľku `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Obmedzenie pre exportované tabuľky
--

--
-- Obmedzenie pre tabuľku `QuestionOptions`
--
ALTER TABLE `QuestionOptions`
  ADD CONSTRAINT `QuestionOptions_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `Questions`
--
ALTER TABLE `Questions`
  ADD CONSTRAINT `Questions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Obmedzenie pre tabuľku `Responses`
--
ALTER TABLE `Responses`
  ADD CONSTRAINT `OPTION` FOREIGN KEY (`option_id`) REFERENCES `QuestionOptions` (`option_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `QUESTION` FOREIGN KEY (`question_id`) REFERENCES `Questions` (`question_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
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
