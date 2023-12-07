-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 07 déc. 2023 à 13:52
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
  `user` int UNSIGNED DEFAULT NULL,
  `post` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId_idx` (`user`),
  KEY `postId_idx` (`post`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `creationDate`, `publicationDate`, `comment`, `user`, `post`) VALUES
(1, '2023-11-16 10:21:46', '2023-11-19 17:42:43', 'Commentaire post Nvidia', 1, 2),
(2, '2023-11-16 10:22:16', '2023-11-19 17:42:43', 'Commentaire post Robots', 1, 1),
(3, '2023-11-16 10:29:32', '2023-11-20 19:02:49', 'Les robots sont super !', 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `summary` text NOT NULL,
  `content` mediumtext NOT NULL,
  `imagePath` varchar(200) NOT NULL,
  `creationDate` datetime NOT NULL,
  `publicationDate` datetime DEFAULT NULL,
  `lastUpdateDate` datetime DEFAULT NULL,
  `user` int UNSIGNED DEFAULT NULL,
  `modifier` int UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userId_idx` (`user`),
  KEY `userIdModified` (`modifier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `summary`, `content`, `imagePath`, `creationDate`, `publicationDate`, `lastUpdateDate`, `user`, `modifier`) VALUES
(1, 'Robots', 'Iam ipsum ais illius habetote facta enim cum laudantur ipsum.', 'Iis igitur est difficilius satis facere, qui se Latina scripta dicunt contemnere. in quibus hoc primum est in quo admirer, cur in gravissimis rebus non delectet eos sermo patrius, cum idem fabellas Latinas ad verbum e Graecis expressas non inviti legant. quis enim tam inimicus paene nomini Romano est, qui Ennii Medeam aut Antiopam Pacuvii spernat aut reiciat, quod se isdem Euripidis fabulis delectari dicat, Latinas litteras oderit?', 'img\\img-posts\\120231116084225.png', '2023-11-16 08:42:25', '2023-11-16 09:45:48', '2023-11-16 08:42:25', 1, NULL),
(2, 'Nvidia', 'Hierapoli vetere clementer quam Osdroenam Samosata civitatibus ut civitatibus descriptione.', 'Vita est illis semper in fuga uxoresque mercenariae conductae ad tempus ex pacto atque, ut sit species matrimonii, dotis nomine futura coniunx hastam et tabernaculum offert marito, post statum diem si id elegerit discessura, et incredibile est quo ardore apud eos in venerem uterque solvitur sexus.', 'img\\img-posts\\220231116084308.png', '2023-11-16 08:43:08', '2023-11-16 09:45:48', '2023-11-16 08:43:08', 1, NULL),
(3, 'AI', 'Veteres operatur Adrastia tradunt facinorum definiunt substantialis et veteres definiunt.', 'Verum ad istam omnem orationem brevis est defensio. Nam quoad aetas M. Caeli dare potuit isti suspicioni locum, fuit primum ipsius pudore, deinde etiam patris diligentia disciplinaque munita. Qui ut huic virilem togam deditšnihil dicam hoc loco de me; tantum sit, quantum vos existimatis; hoc dicam, hunc a patre continuo ad me esse deductum; nemo hunc M. Caelium in illo aetatis flore vidit nisi aut cum patre aut mecum aut in M. Crassi castissima domo, cum artibus honestissimis erudiretur.', 'img\\img-posts\\320231116084431.png', '2023-11-16 08:44:31', NULL, '2023-11-16 08:45:38', 1, 1),
(4, 'Apple', 'Perduceret est vinculis membra discreta est fluminis Constanti longe multiplices.', 'Nam sole orto magnitudine angusti gurgitis sed profundi a transitu arcebantur et dum piscatorios quaerunt lenunculos vel innare temere contextis cratibus parant, effusae legiones, quae hiemabant tunc apud Siden, isdem impetu occurrere veloci. et signis prope ripam locatis ad manus comminus conserendas denseta scutorum conpage semet scientissime praestruebant, ausos quoque aliquos fiducia nandi vel cavatis arborum truncis amnem permeare latenter facillime trucidarunt est.', 'img\\img-posts\\420231116095856.png', '2023-11-16 09:58:56', NULL, '2023-11-20 18:02:17', 1, 1),
(5, 'Bootstrap', 'Coeptantem occultius militum consulta quo concitores et cubiculi consulta tumor.', 'Quis enim aut eum diligat quem metuat, aut eum a quo se metui putet? Coluntur tamen simulatione dumtaxat ad tempus. Quod si forte, ut fit plerumque, ceciderunt, tum intellegitur quam fuerint inopes amicorum. Quod Tarquinium dixisse ferunt, tum exsulantem se intellexisse quos fidos amicos habuisset, quos infidos, cum iam neutris gratiam referre posset.', 'img\\img-posts\\520231116154600.png', '2023-11-16 15:46:00', '2023-11-19 17:40:41', '2023-11-20 19:22:58', 1, 1);

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
  `isValid` tinyint DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `pseudo` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `pseudo`, `email`, `password`, `tokenForgotPassword`, `forgotPasswordDate`, `creationDate`, `userFunction`, `isValid`) VALUES
(1, 'Christophe', 'MARTEL', 'cmartel', 'martel.c@orange.fr', '$2y$10$wb.Ckafp/Xvj/7w9XyrbQ.ecnzeknSELlOZjQYTHI0vlBvaMMcfuW', '', NULL, '2023-10-01 10:01:05', 'Administrateur', 1),
(2, 'Robert', 'Smith', 'rsmith', 'robert.smith@google.com', 'QyRrn7tjaX@&amp;gDxi', NULL, NULL, '2023-11-16 09:40:56', 'Lecteur', 1),
(3, 'Albert', 'Einstein', 'aeinstein', 'albert.einstein@orange.fr', '$2y$10$9ZlTea43WVcAnRNljMnW6uS5KtgvvZVNfQgw1rZH/O10Wc5Uvem16', '', NULL, '2023-11-16 10:23:36', 'Createur', 0);

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
