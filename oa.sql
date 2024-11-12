-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 07 nov. 2024 à 09:10
-- Version du serveur : 8.3.0
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `vhs_bd`
--

-- --------------------------------------------------------

--
-- Structure de la table `oa`
--

DROP TABLE IF EXISTS `oa`;
CREATE TABLE IF NOT EXISTS `oa` (
  `idOA` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `note` int DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `description` text,
  `dateSortie` date DEFAULT NULL,
  `vo` tinyint(1) DEFAULT NULL,
  `limiteAge` int DEFAULT NULL,
  `duree` int DEFAULT NULL,
  PRIMARY KEY (`idOA`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI;

--
-- Déchargement des données de la table `oa`
--

INSERT INTO `oa` (`idOA`, `nom`, `note`, `type`, `description`, `dateSortie`, `vo`, `limiteAge`, `duree`) VALUES
(28, 'The Dark Knight', 9, 'Film', 'Batman affronte le Joker, un criminel en quête de chaos.', '2008-07-18', 1, 13, 152),
(27, 'The Shawshank Redemption', 10, 'Film', 'Un homme condamné à perpétuité pour un crime qu\'il n\'a pas commis trouve la rédemption en prison.', '1994-09-23', 1, 13, 142),
(26, 'Pulp Fiction', 9, 'Film', 'Les vies de deux gangsters, d\'une épouse de gangster, et d\'un boxeur se croisent dans des circonstances inattendues.', '1994-10-14', 1, 16, 154),
(25, 'The Godfather', 10, 'Film', 'La saga de la famille Corleone, un puissant clan mafieux.', '1972-03-24', 1, 18, 175),
(24, 'Interstellar', 9, 'Film', 'Un groupe d\'astronautes se lance dans une mission pour trouver une nouvelle planète habitable.', '2014-11-07', 1, 10, 169),
(23, 'The Matrix', 8, 'Film', 'Un pirate informatique découvre une réalité alternative contrôlée par des machines.', '1999-03-31', 1, 16, 136),
(22, 'Inception', 9, 'Film', 'Un voleur expérimenté dans l\'infiltration des rêves se voit proposer une chance de retrouver sa vie normale.', '2010-07-16', 1, 13, 148);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
