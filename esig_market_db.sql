-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 28 avr. 2026 à 01:14
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `esig_market_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `condition` enum('neuf','tres_bon','bon','acceptable') NOT NULL DEFAULT 'bon',
  `status` enum('disponible','vendu','suspendu') NOT NULL DEFAULT 'disponible',
  `views` int(11) NOT NULL DEFAULT 0,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `user_id`, `category_id`, `title`, `slug`, `description`, `price`, `condition`, `status`, `views`, `location`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'phone', 'phone-1776595688', 'azertyu', 800.00, 'bon', 'disponible', 7, NULL, '2026-04-19 10:48:08', '2026-04-19 11:59:26'),
(2, 1, 3, 'phone', 'phone-1776595851', 'azertyu', 800.00, 'bon', 'disponible', 1, NULL, '2026-04-19 10:50:51', '2026-04-21 16:37:13'),
(3, 1, 3, 'phone', 'phone-1776596137', 'azertyu', 800.00, 'bon', 'disponible', 1, NULL, '2026-04-19 10:55:37', '2026-04-23 10:21:24'),
(4, 1, 3, 'phone', 'phone-1776596288', 'azertyu', 800.00, 'bon', 'disponible', 7, NULL, '2026-04-19 10:58:08', '2026-04-24 16:30:53'),
(9, 1, 5, 'kjhg', 'kjhg-1776600776', 'kjhg', 55.00, 'neuf', 'vendu', 3, 'gfvccccccccc', '2026-04-19 12:12:56', '2026-04-19 12:14:31'),
(12, 10, 5, 'Paire de basket Edwards1 en bonne etat', 'paire-de-basket-edwards1-en-bonne-etat-1776873235', 'gomme bien résistante mm sur les terrain glissant', 8000.00, 'acceptable', 'disponible', 31, NULL, '2026-04-22 15:53:55', '2026-04-27 21:27:07');

-- --------------------------------------------------------

--
-- Structure de la table `article_images`
--

CREATE TABLE `article_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `path` varchar(255) NOT NULL,
  `is_main` tinyint(1) NOT NULL DEFAULT 0,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article_images`
--

INSERT INTO `article_images` (`id`, `article_id`, `path`, `is_main`, `order`, `created_at`, `updated_at`) VALUES
(1, 4, 'articles/JTyorW14drsOCjTkgmsyHaTDKxFmIBDdlIzBDNDw.jpg', 0, 0, '2026-04-19 10:58:08', '2026-04-19 10:58:08'),
(6, 9, 'articles/VMZQeCRRDnsYaD65nDpxJqrTyqU42Fy33yoWOLHm.png', 0, 0, '2026-04-19 12:12:56', '2026-04-19 12:12:56'),
(7, 9, 'articles/knlKcU3KIKgsguqILqxuBCqSyrswjz2LdplEMSeI.jpg', 0, 0, '2026-04-19 12:12:56', '2026-04-19 12:12:56'),
(13, 12, 'articles/CvzW43mW70RFYdKhnPhf1fBxqRGLkcFP8Fg1ggVG.png', 0, 0, '2026-04-22 15:53:57', '2026-04-22 15:53:57');

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Vêtements', 'vetements', '👗', '2026-04-19 10:46:14', '2026-04-19 10:46:14'),
(2, 'Livres & Cours', 'livres', '📚', '2026-04-19 10:46:14', '2026-04-19 10:46:14'),
(3, 'Électronique', 'electronique', '💻', '2026-04-19 10:46:15', '2026-04-19 10:46:15'),
(4, 'Accessoires', 'accessoires', '👜', '2026-04-19 10:46:15', '2026-04-19 10:46:15'),
(5, 'Sport', 'sport', '⚽', '2026-04-19 10:46:15', '2026-04-19 10:46:15'),
(6, 'Maison', 'maison', '🏠', '2026-04-19 10:46:15', '2026-04-19 10:46:15'),
(7, 'Autre', 'autre', '📦', '2026-04-19 10:46:15', '2026-04-19 10:46:15');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `article_id`, `created_at`, `updated_at`) VALUES
(6, 1, 1, '2026-04-23 10:11:33', '2026-04-23 10:11:33'),
(7, 1, 12, '2026-04-23 10:31:25', '2026-04-23 10:31:25'),
(8, 2, 12, '2026-04-27 21:27:06', '2026-04-27 21:27:06');

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `receiver_id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `article_id`, `body`, `read_at`, `created_at`, `updated_at`) VALUES
(21, 10, 1, 4, 'hjklm', '2026-04-22 15:56:05', '2026-04-22 15:55:25', '2026-04-22 15:56:05'),
(22, 1, 10, 4, 'hvbjnk,;', '2026-04-22 15:58:46', '2026-04-22 15:56:14', '2026-04-22 15:58:46'),
(23, 11, 1, 4, 'Hello', '2026-04-24 16:31:51', '2026-04-24 16:31:13', '2026-04-24 16:31:51'),
(24, 1, 11, 4, 'qadeefr', '2026-04-24 16:32:09', '2026-04-24 16:32:00', '2026-04-24 16:32:09');

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_04_16_085208_add_profile_fields_to_users_table', 1),
(5, '2026_04_16_114433_create_categories_table', 1),
(6, '2026_04_16_164620_create_articles_table', 1),
(7, '2026_04_16_233338_create_article_images_table', 1),
(8, '2026_04_16_234250_create_messages_table', 1),
(9, '2026_04_16_234532_create_reviews_table', 1),
(10, '2026_04_16_235010_create_favorites_table', 1),
(11, '2026_04_16_235346_create_reports_table', 1),
(12, '2026_04_17_013624_update_articles_status_enum', 1),
(13, '2026_04_18_163545_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0261bf30-44a3-415c-9a16-652f6c2cd820', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 7, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":17}', NULL, '2026-04-20 10:09:57', '2026-04-20 10:09:57'),
('20ba2aa1-8117-4126-9fde-e3f7c0aca123', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"kokou\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":12}', '2026-04-20 07:49:43', '2026-04-20 07:49:26', '2026-04-20 07:49:43'),
('246f3cb9-cb30-4d99-9465-18b0dee24d15', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"parfait\",\"article_id\":\"5\",\"article_title\":\"vv\",\"message_id\":20}', '2026-04-21 22:25:34', '2026-04-21 17:00:23', '2026-04-21 22:25:34'),
('2afd6962-21e8-4c9b-8066-eca7e98958f2', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":6}', '2026-04-20 08:06:19', '2026-04-20 07:41:34', '2026-04-20 08:06:19'),
('2dc7a324-5d8a-4eef-91e3-6127bf0dd884', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Am\\u00e9d\\u00e9\",\"article_id\":6,\"article_title\":\"kjhg\",\"message_id\":4}', '2026-04-19 21:35:16', '2026-04-19 21:31:23', '2026-04-19 21:35:16'),
('3b665904-522a-4da4-9863-624296cfc387', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Anani ZANDOR\",\"article_id\":\"6\",\"article_title\":\"kjhg\",\"message_id\":15}', NULL, '2026-04-20 08:07:19', '2026-04-20 08:07:19'),
('44b21455-581f-4f92-a6ef-42dc92998b9b', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 11, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Anani ZANDOR\",\"article_id\":\"4\",\"article_title\":\"phone\",\"message_id\":24}', NULL, '2026-04-24 16:32:00', '2026-04-24 16:32:00'),
('4bd2cca4-e1b1-4c16-a6f2-3f5607e8e39b', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"6\",\"article_title\":\"kjhg\",\"message_id\":14}', '2026-04-20 22:28:16', '2026-04-20 08:05:43', '2026-04-20 22:28:16'),
('551fcde1-8ae6-442f-8641-03f693b77e30', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":11}', '2026-04-20 08:06:19', '2026-04-20 07:44:05', '2026-04-20 08:06:19'),
('5c7f21f6-24b6-441c-bb71-b09b1efba5ed', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"parfait\",\"article_id\":\"5\",\"article_title\":\"vv\",\"message_id\":19}', '2026-04-21 22:25:34', '2026-04-21 17:00:23', '2026-04-21 22:25:34'),
('6170d56e-ce33-43a8-a237-ad88e836256f', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":13}', '2026-04-20 08:06:19', '2026-04-20 07:49:55', '2026-04-20 08:06:19'),
('6eb04ca5-83c3-418c-bb88-01c3f2ed8a88', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"kokou\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":7}', '2026-04-20 07:49:43', '2026-04-20 07:42:10', '2026-04-20 07:49:43'),
('8cbc6dc8-9e23-4594-b231-fc5195cd6dce', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Miacu\",\"article_id\":5,\"article_title\":\"vv\",\"message_id\":3}', '2026-04-19 21:35:17', '2026-04-19 16:29:33', '2026-04-19 21:35:17'),
('8cffac44-32bb-465d-b32f-b39306adc160', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 10, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Anani ZANDOR\",\"article_id\":\"4\",\"article_title\":\"phone\",\"message_id\":22}', NULL, '2026-04-22 15:56:14', '2026-04-22 15:56:14'),
('9837cccd-d836-43a1-b876-7c86eda6c2fe', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":10}', '2026-04-20 08:06:19', '2026-04-20 07:44:03', '2026-04-20 08:06:19'),
('9caea9e7-e0a0-425f-aa9b-d8a89fbc1e69', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Miacu\",\"article_id\":5,\"article_title\":\"vv\",\"message_id\":2}', '2026-04-19 21:35:17', '2026-04-19 16:28:24', '2026-04-19 21:35:17'),
('a3621dbe-68d2-4da9-a969-63eeb00bb247', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"SEMEGLO edouardo\",\"article_id\":\"4\",\"article_title\":\"phone\",\"message_id\":21}', '2026-04-22 15:56:55', '2026-04-22 15:55:30', '2026-04-22 15:56:55'),
('aa968952-27f8-42af-a6fb-b54c84555462', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"BENISSAN-MESSAN D\\u00e9d\\u00e9 Georstelle Sirad Graciella\",\"article_id\":\"5\",\"article_title\":\"vv\",\"message_id\":18}', '2026-04-21 11:05:02', '2026-04-21 11:03:17', '2026-04-21 11:05:02'),
('ac47c01c-d236-49df-996c-3392f707a7fa', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"AGBASSAH D\\u00e9bi K\\u00e9k\\u00e9li Judith\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":16}', NULL, '2026-04-20 10:08:30', '2026-04-20 10:08:30'),
('b1602f52-b7e2-46d4-b057-22c6979f7377', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 5, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"kokou\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":5}', '2026-04-20 07:37:15', '2026-04-20 07:35:55', '2026-04-20 07:37:15'),
('b70b0640-1488-4f7e-8700-91f8d430c447', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"Miacu\",\"article_id\":5,\"article_title\":\"vv\",\"message_id\":1}', '2026-04-19 21:35:17', '2026-04-19 16:26:52', '2026-04-19 21:35:17'),
('db511283-a42a-47a1-97a9-19fd971cc7f4', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 1, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"AKOUMEY DESSI\",\"article_id\":\"4\",\"article_title\":\"phone\",\"message_id\":23}', '2026-04-27 19:39:48', '2026-04-24 16:31:19', '2026-04-27 19:39:48'),
('e7f790f1-f0e3-45d9-944b-2fe837b1c78b', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":8}', '2026-04-20 08:06:19', '2026-04-20 07:42:31', '2026-04-20 08:06:19'),
('fd4f349a-a3ba-44a6-9723-8caee74b96c4', 'App\\Notifications\\NewMessageNotification', 'App\\Models\\User', 6, '{\"message\":\"Vous avez re\\u00e7u un nouveau message\",\"sender_name\":\"joas\",\"article_id\":\"11\",\"article_title\":\"POST1\",\"message_id\":9}', '2026-04-20 08:06:19', '2026-04-20 07:44:02', '2026-04-20 08:06:19');

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reports`
--

CREATE TABLE `reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','reviewed','resolved') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `article_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 12, 'Contenu inapproprié', 'pending', '2026-04-23 11:06:29', '2026-04-23 11:06:29'),
(2, 1, 12, 'Article déjà vendu', 'pending', '2026-04-23 11:06:48', '2026-04-23 11:06:48');

-- --------------------------------------------------------

--
-- Structure de la table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_id` bigint(20) UNSIGNED NOT NULL,
  `reviewed_id` bigint(20) UNSIGNED NOT NULL,
  `article_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewer_id`, `reviewed_id`, `article_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(1, 1, 10, 12, 5, NULL, '2026-04-23 10:56:40', '2026-04-23 10:56:40'),
(2, 2, 10, 12, 1, NULL, '2026-04-23 11:26:20', '2026-04-23 11:26:20');

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('QO9XfgaBQZ9VyQOBX67Oq3m6fbVMmgeZ05jZUZas', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRkdaa3dscXJzNFZFYmZsTWthZWVNT3lsZ1ZPNDVUSDFNT1ZDcGpQQiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcnRpY2xlcy8xMiI7czo1OiJyb3V0ZSI7czoxMzoiYXJ0aWNsZXMuc2hvdyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1777325227);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `filiere` varchar(255) DEFAULT NULL,
  `numero_etudiant` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `bio` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `avatar`, `filiere`, `numero_etudiant`, `role`, `bio`, `is_active`) VALUES
(1, 'Anani ZANDOR', 'ananizandor@gmail.com', NULL, '$2y$12$S25dFdiRvlzybuoGaWQG4u5NPRwJIbptpJJPJlvd1l00Q0xXZht3S', NULL, '2026-04-19 10:47:37', '2026-04-21 22:48:42', 'avatars/o1Wyp3cnKF2TmksUTWa4kqOWo34YXeA6arOw0Xwj.png', NULL, NULL, 'user', NULL, 1),
(2, 'Admin ESIG', 'admin@esig.tg', NULL, '$2y$12$CjnuK9Kn.y1pw23UoKsI3.SnohzWoDGoq6UiEPN8z879JBg2LmZse', NULL, '2026-04-19 12:28:46', '2026-04-19 12:28:46', NULL, 'Administration', NULL, 'admin', NULL, 1),
(3, 'Miacu', 'test8@test.com', NULL, '$2y$12$wQR24H9KQl4i/HRFxr8KRO1p7VWdpBjsaadpan9Qk0BqXVLTc5fHy', NULL, '2026-04-19 16:25:49', '2026-04-21 22:43:26', NULL, NULL, NULL, 'user', NULL, 1),
(4, 'Amédé', 'test5test@esig.tg', NULL, '$2y$12$vOJrgLy2I1pAwfJ8y2Na3.SB2iP0M/CJM/wruPilBTUIYvEM4yDdC', NULL, '2026-04-19 21:27:04', '2026-04-19 21:27:04', NULL, NULL, NULL, 'user', NULL, 1),
(5, 'joas', 'joas@esig.tg', NULL, '$2y$12$VY3bDXOWZ8/W8NjRV2ahf.lURVDDYdDwb15LNchwA1qYpizn46M/K', NULL, '2026-04-20 07:27:01', '2026-04-20 07:27:01', NULL, NULL, NULL, 'user', NULL, 1),
(6, 'kokou', 'kokou@esig.tg', NULL, '$2y$12$o5YTkK7u1Yw6ROiYuMtsbOaV5bYMQqc0fgpX77z7N0K8WmmjCRrGe', NULL, '2026-04-20 07:34:41', '2026-04-20 07:34:41', NULL, NULL, NULL, 'user', NULL, 1),
(7, 'AGBASSAH Débi Kékéli Judith', 'judithagbassah@gmail.com', NULL, '$2y$12$N/rIy7wPM73tihBPJj8pu.qTpU7yePOWeJydNJliN07lM6gk.gtwS', NULL, '2026-04-20 10:04:01', '2026-04-20 10:04:01', NULL, NULL, NULL, 'user', NULL, 1),
(8, 'BENISSAN-MESSAN Dédé Georstelle Sirad Graciella', 'stellagraciellabenissan@gmail.com', NULL, '$2y$12$rTJwL.wcOWUaocyipZ.EOO0WuW1WzusAmw0iU.woScnBJCyiHrBsq', NULL, '2026-04-21 11:00:47', '2026-04-21 11:00:47', NULL, NULL, NULL, 'user', NULL, 1),
(10, 'SEMEGLO edouardo', 'edouardo@esig.tg', NULL, '$2y$12$2iC7zFWFlkYL8MvurIlCE.PkKx0iyuQLRt1Kh83pq06Z7sHLH/uFu', NULL, '2026-04-22 15:42:31', '2026-04-22 15:42:31', NULL, NULL, NULL, 'user', NULL, 1),
(11, 'AKOUMEY DESSI', 'akoumey@esig.tg', NULL, '$2y$12$OSrEChNdtcwgpsA7j2GHOOuzvz4ndoxoTNhyVXkwRTXo4SmiDgM3m', 'vVgwdBse5JEJIK49ZHTW1tq6GxVKqetZ3eaJVNjyBsYiROH763ypv7xKA1u4', '2026-04-24 16:29:04', '2026-04-24 16:29:04', NULL, NULL, NULL, 'user', NULL, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_user_id_foreign` (`user_id`),
  ADD KEY `articles_category_id_foreign` (`category_id`);

--
-- Index pour la table `article_images`
--
ALTER TABLE `article_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `article_images_article_id_foreign` (`article_id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_article_id_unique` (`user_id`,`article_id`),
  ADD KEY `favorites_article_id_foreign` (`article_id`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `messages_sender_id_foreign` (`sender_id`),
  ADD KEY `messages_receiver_id_foreign` (`receiver_id`),
  ADD KEY `messages_article_id_foreign` (`article_id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `reports_article_id_foreign` (`article_id`);

--
-- Index pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reviews_reviewer_id_reviewed_id_article_id_unique` (`reviewer_id`,`reviewed_id`,`article_id`),
  ADD KEY `reviews_reviewed_id_foreign` (`reviewed_id`),
  ADD KEY `reviews_article_id_foreign` (`article_id`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `article_images`
--
ALTER TABLE `article_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `articles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `article_images`
--
ALTER TABLE `article_images`
  ADD CONSTRAINT `article_images_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_receiver_id_foreign` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewed_id_foreign` FOREIGN KEY (`reviewed_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_reviewer_id_foreign` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
