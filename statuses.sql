-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 26 fév. 2024 à 21:33
-- Version du serveur : 5.7.24
-- Version de PHP : 8.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bougteba`
--

-- --------------------------------------------------------

--
-- Structure de la table `statuses`
--

CREATE TABLE `statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `status_id` int(11) NOT NULL,
  `status_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `statuses`
--

INSERT INTO `statuses` (`id`, `status_id`, `status_name`, `status_badge`, `created_at`, `updated_at`) VALUES
(1, 10, 'Annulé', 'danger', '2024-02-25 00:29:46', '2024-02-25 00:30:10'),
(2, 13, 'Annulé ( SUIVI )', 'danger', '2024-02-25 00:30:15', '2024-02-25 00:30:19'),
(3, 4, 'Livré', 'success', '2024-02-25 00:30:22', '2024-02-25 00:30:25'),
(4, 3, 'Mise en distribution', 'primary', '2024-02-25 00:36:48', '2024-02-25 00:36:58'),
(5, 7, 'Erreur Numero', 'danger', '2024-02-25 00:37:02', '2024-02-25 00:37:05'),
(6, 14, 'client intéressé', 'info', '2024-02-25 00:37:08', '2024-02-25 00:37:12'),
(7, 15, 'En cours', 'info', '2024-02-25 00:37:14', '2024-02-25 00:37:17'),
(8, 5, 'Pas de réponse + SMS', 'danger', '2024-02-25 00:37:20', '2024-02-25 00:37:22'),
(9, 16, 'Pas de reponse ( SUIVI )', 'danger', '2024-02-25 00:37:28', '2024-02-25 00:37:30'),
(10, 17, 'En Voyage', 'warning', '2024-02-25 00:37:35', '2024-02-25 00:37:38'),
(11, 18, 'Hors-zone', 'danger', '2024-02-25 00:37:40', '2024-02-25 00:37:43'),
(12, 1, 'Ramassé', 'warning', '2024-02-25 00:37:46', '2024-02-25 00:37:49'),
(13, 8, 'Reporté', 'danger', '2024-02-25 00:37:55', '2024-02-25 00:37:57'),
(14, 19, 'Reporté ( SUIVI )', 'danger', '2024-02-25 00:37:59', '2024-02-25 00:38:01'),
(15, 9, 'Programmé', 'danger', '2024-02-25 00:38:05', '2024-02-25 00:38:07'),
(16, 20, 'Reçu', 'info', '2024-02-25 00:38:09', '2024-02-25 00:38:12'),
(17, 11, 'Refusé', 'danger', '2024-02-25 00:38:13', '2024-02-25 00:38:16'),
(18, 12, 'Retourné', 'danger', '2024-02-25 00:38:17', '2024-02-25 00:38:19'),
(19, 21, 'En retour par AMANA', 'danger', '2024-02-25 00:38:21', '2024-02-25 00:38:23'),
(20, 22, 'reporté aujourd hui', 'danger', '2024-02-25 00:38:26', '2024-02-25 00:38:29'),
(21, 2, 'Expédié', 'info', '2024-02-25 00:38:30', '2024-02-25 00:38:32'),
(22, 23, 'expédier par AMANA', 'danger', '2024-02-25 00:38:35', '2024-02-25 00:38:37'),
(23, 6, 'Injoignable', 'danger', '2024-02-25 00:38:40', '2024-02-25 00:38:42'),
(24, 24, 'Injoignable ( SUIVI )', 'danger', '2024-02-25 00:38:45', '2024-02-25 00:38:47'),
(25, 25, 'Boite Vocal', 'danger', '2024-02-25 00:38:52', '2024-02-25 00:38:55'),
(26, 26, 'Boite Vocal ( SUIVI )', 'danger', '2024-02-25 00:39:00', '2024-02-25 00:39:03'),
(27, 27, 'Nouveau Colis', 'primary', '2024-02-25 00:40:00', '2024-02-25 00:40:03'),
(28, 28, 'Attente De Ramassage', 'primary', '2024-02-25 00:41:00', '2024-02-25 00:41:03');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `statuses`
--
ALTER TABLE `statuses`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `statuses`
--
ALTER TABLE `statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
