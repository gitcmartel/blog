-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 02 jan. 2024 à 19:59
-- Version du serveur : 8.0.31
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `creationDate` datetime NOT NULL,
  `publicationDate` datetime DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `isValid` tinyint(1) NOT NULL DEFAULT '0',
  `user` int UNSIGNED DEFAULT NULL,
  `post` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId_idx` (`user`),
  KEY `postId_idx` (`post`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `summary` varchar(150) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `imagePath` varchar(200) NOT NULL,
  `creationDate` datetime NOT NULL,
  `publicationDate` datetime DEFAULT NULL,
  `lastUpdateDate` datetime DEFAULT NULL,
  `user` int UNSIGNED DEFAULT NULL,
  `modifier` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId_idx` (`user`),
  KEY `userIdModified` (`modifier`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `summary`, `content`, `imagePath`, `creationDate`, `publicationDate`, `lastUpdateDate`, `user`, `modifier`) VALUES
(22, 'Test', 'Résumé test', 'Contenu test', 'img\\blog-post.svg', '2024-01-02 19:56:48', '2024-01-02 20:56:54', '2024-01-02 19:57:38', 19, 19);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `surname` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pseudo` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `tokenForgotPassword` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `forgotPasswordDate` datetime DEFAULT NULL,
  `creationDate` datetime DEFAULT NULL,
  `userFunction` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT 'Lecteur',
  `isValid` tinyint DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `pseudo`, `email`, `password`, `tokenForgotPassword`, `forgotPasswordDate`, `creationDate`, `userFunction`, `isValid`) VALUES
(19, 'admin', 'test', 'admintest', 'admintest@test.fr', '$2y$10$FO2RfPd8ZT5vJHeusmtYXOqRyFdHb0G8EZcjkh06ZZ.2Jz0c8nxAy', NULL, NULL, '2024-01-02 20:48:40', 'Administrateur', 1);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `postId` FOREIGN KEY (`post`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `userIdComment` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `userId` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
