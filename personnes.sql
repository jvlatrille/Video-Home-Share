-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 10 nov. 2024 à 10:15
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
-- Structure de la table `personne`
--

DROP TABLE IF EXISTS `personne`;
CREATE TABLE IF NOT EXISTS `personne` (
  `idPersonne` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `dateNaiss` date DEFAULT NULL,
  `genre` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`idPersonne`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=UTF8 COLLATE=UTF8_GENERAL_CI;

--
-- Déchargement des données de la table `personne`
--

-- Exemple d'insertion, à ajuster selon les besoins
INSERT INTO `personne` (`idPersonne`, `nom`, `prenom`, `dateNaiss`, `genre`) VALUES
(1, 'Dupont', 'Jean', '1990-01-01', 'Homme'),
(2, 'Martin', 'Marie', '1985-05-12', 'Femme'),
(3, 'Durand', 'Paul', '1978-07-22', 'Homme'),
(4, 'Petit', 'Lucie', '1995-03-30', 'Femme');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
