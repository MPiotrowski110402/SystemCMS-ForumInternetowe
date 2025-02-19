-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Wersja serwera:               11.6.2-MariaDB - mariadb.org binary distribution
-- Serwer OS:                    Win64
-- HeidiSQL Wersja:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Zrzut struktury bazy danych cms_project
CREATE DATABASE IF NOT EXISTS `cms_project` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `cms_project`;

-- Zrzut struktury tabela cms_project.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli cms_project.category: ~5 rows (około)
INSERT INTO `category` (`id`, `category`) VALUES
	(1, 'IT'),
	(2, 'sprzet komputerowy'),
	(3, 'helpdesk'),
	(4, 'Programowanie'),
	(5, 'Sieci komputerowe');

-- Zrzut struktury tabela cms_project.comments
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli cms_project.comments: ~7 rows (około)
INSERT INTO `comments` (`id`, `post_id`, `user_id`, `content`, `created_at`) VALUES
	(16, 2, 1, 'hlkjjk', '2025-02-05 15:47:13'),
	(17, 5, 1, ',mn.,m', '2025-02-05 15:47:45'),
	(18, 4, 1, 'dfsdfafdsfa', '2025-02-05 16:13:39'),
	(19, 2, 1, 'sadsad', '2025-02-05 16:14:52'),
	(24, 10, 1, 'Dobre!\r\n', '2025-02-19 10:26:08'),
	(25, 10, 1, 'aa', '2025-02-19 10:26:46'),
	(26, 10, 14, 'Witam', '2025-02-19 10:47:57');

-- Zrzut struktury tabela cms_project.likes
CREATE TABLE IF NOT EXISTS `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_id` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli cms_project.likes: ~8 rows (około)
INSERT INTO `likes` (`id`, `post_id`, `user_id`) VALUES
	(2, 2, 1),
	(4, 2, 2),
	(6, 3, 2),
	(9, 4, 1),
	(5, 5, 2),
	(10, 6, 1),
	(16, 10, 1),
	(17, 10, 14);

-- Zrzut struktury tabela cms_project.posts
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `views_count` int(11) DEFAULT 0,
  `likes_count` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `fk_category` (`category_id`),
  CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli cms_project.posts: ~6 rows (około)
INSERT INTO `posts` (`id`, `user_id`, `title`, `content`, `created_at`, `views_count`, `likes_count`, `category_id`) VALUES
	(2, 2, 'Drugie podejście do tematu', 'Cześć wszystkim! W tym poście będę omawiać jak wykorzystać bazy danych w aplikacjach internetowych. Zapraszam do dyskusji!', '2025-01-25 18:29:36', 1058, 2, 4),
	(3, 1, 'Zasady bezpieczeństwa w aplikacjach webowych', 'Bezpieczeństwo w aplikacjach internetowych jest bardzo ważne. W tym poście omówię najlepsze praktyki i techniki ochrony danych użytkowników.', '2025-01-25 18:29:36', 161, 1, 4),
	(4, 2, 'Nowe funkcjonalności w PHP 8', 'PHP 8 wprowadził wiele nowych funkcjonalności. Jeśli chcesz poznać te zmiany, przeczytaj ten post. Opiszę tu kilka nowości, które mogą zmienić Twoje podejście do programowania.', '2025-01-25 18:29:36', 124, 1, 1),
	(5, 1, 'Podstawy SQL dla początkujących', 'Chcesz nauczyć się SQL? Zacznij od podstaw! Ten post pomoże Ci zrozumieć, jak działają zapytania, tabele i klucze obce w bazach danych.', '2025-01-25 18:29:36', 223, 1, 2),
	(6, 2, 'Co to jest JavaScript i jak go wykorzystać?', 'JavaScript to język, który jest podstawą nowoczesnych aplikacji webowych. Zobacz, jak go używać w połączeniu z HTML i CSS, aby tworzyć dynamiczne strony internetowe.', '2025-01-25 18:29:36', 125, 1, 5),
	(10, 1, 'Dodano funkcjonalność sortowania', 'Od teraz posty sortują się po dacie dodania posta', '2025-02-19 10:02:05', 32, 2, 1);

-- Zrzut struktury tabela cms_project.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- Zrzucanie danych dla tabeli cms_project.users: ~3 rows (około)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`, `role`, `status`, `last_login`, `profile_picture`, `bio`) VALUES
	(1, 'JohnDoe', '1', 'admin@admin.com', '2015-01-25 18:24:36', 'user', 'active', '2025-01-25 18:24:36', 'https://image.spreadshirtmedia.net/image-server/v1/products/T1459A839PA4459PT28D16059761W7129H10000/views/1,width=1200,height=630,appearanceId=839,backgroundColor=F2F2F2/pytajnik-naklejka.jpg', 'Avid reader and tech enthusiast.'),
	(2, 'Steve', '1', 'steve@steve.com', '2025-01-25 18:24:36', 'user', 'active', '2025-01-25 18:24:36', 'https://cdn.pixabay.com/photo/2017/06/13/12/54/profile-2398783_1280.png', 'Lover of nature and coding.'),
	(14, 'test', '1', 'test@test.pl', '2025-01-25 18:24:36', 'admin', 'active', '2025-01-25 18:24:36', 'https://img.buzzfeed.com/buzzfeed-static/static/2020-01/24/15/asset/a4a439fc5e1f/sub-buzz-1096-1579879662-3.jpg?downsize=700%3A%2A&output-quality=auto&output-format=auto', 'Avid reader and tech enthusiast.');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
