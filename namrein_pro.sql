-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 26 déc. 2024 à 14:16
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `namrein_pro`
--

-- --------------------------------------------------------

--
-- Structure de la table `vhs_commentaire`
--

DROP TABLE IF EXISTS `vhs_commentaire`;
CREATE TABLE IF NOT EXISTS `vhs_commentaire` (
  `idCom` int NOT NULL AUTO_INCREMENT,
  `idTMDB` int NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `dateCommentaire` date DEFAULT NULL,
  `idUtilisateur` int DEFAULT NULL,
  PRIMARY KEY (`idCom`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_commentaire`
--

INSERT INTO `vhs_commentaire` (`idCom`, `idTMDB`, `contenu`, `dateCommentaire`, `idUtilisateur`) VALUES
(1, 123, 'Great chess content!', '2024-11-01', 1),
(2, 456, 'Monopoly is awesome!', '2024-11-02', 2),
(3, 372058, 'Une masterclass :)', '2024-11-03', 1),
(4, 372058, 'Loved the plot!', '2024-11-04', 4),
(5, 372058, 'Great acting!', '2024-11-05', 5),
(6, 789, 'Uno is a fun game!', '2024-11-06', 6),
(7, 1011, 'Scrabble is challenging!', '2024-11-07', 7),
(8, 1213, 'Poker nights are the best!', '2024-11-08', 8),
(9, 1415, 'Clue is a classic!', '2024-11-09', 9),
(10, 1617, 'Catan is strategic!', '2024-11-10', 10),
(11, 1819, 'Risk is intense!', '2024-11-11', 1),
(12, 2021, 'Go is a deep game!', '2024-11-12', 2),
(13, 2223, 'Battleship is exciting!', '2024-11-13', 3),
(14, 123, 'Chess is timeless!', '2024-11-14', 4),
(15, 456, 'Monopoly never gets old!', '2024-11-15', 5),
(16, 789, 'Uno is a family favorite!', '2024-11-16', 6),
(17, 1011, 'Scrabble expands vocabulary!', '2024-11-17', 7),
(21, 372058, 'Ce film est un chef d\'oeuvre X)', '2024-12-22', 11),
(22, 372058, '2eme commentaire écrit pour tester', '2024-12-22', 11);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_creer`
--

DROP TABLE IF EXISTS `vhs_creer`;
CREATE TABLE IF NOT EXISTS `vhs_creer` (
  `idUtilisateur` int NOT NULL,
  `idWatchlist` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idWatchlist`),
  KEY `idWatchlist` (`idWatchlist`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_creer`
--

INSERT INTO `vhs_creer` (`idUtilisateur`, `idWatchlist`) VALUES
(1, 1),
(1, 3),
(2, 2),
(2, 4),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_forum`
--

DROP TABLE IF EXISTS `vhs_forum`;
CREATE TABLE IF NOT EXISTS `vhs_forum` (
  `idForum` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `idUtilisateur` int DEFAULT NULL,
  PRIMARY KEY (`idForum`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_forum`
--

INSERT INTO `vhs_forum` (`idForum`, `nom`, `description`, `theme`, `idUtilisateur`) VALUES
(1, 'Chess Talk', 'Discussion about chess', 'Games', 1),
(2, 'Monopoly Fans', 'Discussion about monopoly', 'Games', 2),
(3, 'Uno Strategies', 'Tips for uno game', 'Games', 3),
(4, 'Scrabble Club', 'Word game tips', 'Games', 4),
(5, 'Poker Pros', 'Poker discussions', 'Games', 5),
(6, 'Clue Detectives', 'Clue game insights', 'Games', 6),
(7, 'Catan Traders', 'Catan strategies', 'Games', 7),
(8, 'Risk Masters', 'Risk game tactics', 'Games', 8),
(9, 'Go Community', 'Discussions on Go', 'Games', 9),
(10, 'Battleship League', 'Battleship game tips', 'Games', 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_jeu`
--

DROP TABLE IF EXISTS `vhs_jeu`;
CREATE TABLE IF NOT EXISTS `vhs_jeu` (
  `idJeu` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `regle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idJeu`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_jeu`
--

INSERT INTO `vhs_jeu` (`idJeu`, `nom`, `regle`) VALUES
(1, 'Chess', 'Rules for chess game'),
(2, 'Monopoly', 'Rules for monopoly game'),
(3, 'Uno', 'Rules for uno game'),
(4, 'Scrabble', 'Rules for scrabble game'),
(5, 'Poker', 'Rules for poker game'),
(6, 'Clue', 'Rules for clue game'),
(7, 'Catan', 'Rules for catan game'),
(8, 'Risk', 'Rules for risk game'),
(9, 'Go', 'Rules for go game'),
(10, 'Battleship', 'Rules for battleship game');

-- --------------------------------------------------------

--
-- Structure de la table `vhs_jouerpartie`
--

DROP TABLE IF EXISTS `vhs_jouerpartie`;
CREATE TABLE IF NOT EXISTS `vhs_jouerpartie` (
  `idJeu` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idUtilisateur2` int NOT NULL,
  `datePartie` date NOT NULL,
  `idJoueurGagnant` int DEFAULT NULL,
  `sujetDebat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idJeu`,`idUtilisateur`,`idUtilisateur2`,`datePartie`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idUtilisateur2` (`idUtilisateur2`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_jouerpartie`
--

INSERT INTO `vhs_jouerpartie` (`idJeu`, `idUtilisateur`, `idUtilisateur2`, `datePartie`, `idJoueurGagnant`, `sujetDebat`) VALUES
(1, 1, 2, '2024-11-01', 1, 'Who is better at chess?'),
(2, 3, 4, '2024-11-02', 3, 'Monopoly strategy tips'),
(3, 5, 6, '2024-11-03', 5, 'Uno game rules'),
(4, 7, 8, '2024-11-04', 7, 'Scrabble word tips'),
(5, 9, 10, '2024-11-05', 9, 'Poker hand rankings'),
(6, 1, 3, '2024-11-06', 1, 'Clue game strategies'),
(7, 2, 4, '2024-11-07', 2, 'Catan trading tips'),
(8, 5, 7, '2024-11-08', 5, 'Risk game tactics'),
(9, 6, 8, '2024-11-09', 6, 'Go game techniques'),
(10, 9, 1, '2024-11-10', 9, 'Battleship positioning'),
(1, 2, 3, '2024-11-11', 2, 'Chess opening moves'),
(2, 4, 5, '2024-11-12', 4, 'Monopoly property management');

-- --------------------------------------------------------

--
-- Structure de la table `vhs_message`
--

DROP TABLE IF EXISTS `vhs_message`;
CREATE TABLE IF NOT EXISTS `vhs_message` (
  `idMessage` int NOT NULL AUTO_INCREMENT,
  `contenu` varchar(255) NOT NULL,
  `nbLike` int DEFAULT '0',
  `nbDislike` int DEFAULT '0',
  `idUtilisateur` int NOT NULL,
  `idForum` int NOT NULL,
  PRIMARY KEY (`idMessage`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idForum` (`idForum`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_message`
--

INSERT INTO `vhs_message` (`idMessage`, `contenu`, `nbLike`, `nbDislike`, `idUtilisateur`, `idForum`) VALUES
(1, 'I love chess!', 10, 1, 1, 1),
(2, 'Monopoly is great', 5, 0, 2, 2),
(3, 'Uno is fun', 7, 2, 3, 3),
(4, 'Scrabble rocks', 3, 1, 4, 4),
(5, 'Poker night!', 8, 0, 5, 5),
(6, 'Clue is amazing', 9, 3, 6, 6),
(7, 'Catan anyone?', 4, 2, 7, 7),
(8, 'Risk challenges', 6, 2, 8, 8),
(9, 'Go strategies', 5, 1, 9, 9),
(10, 'Battleship tips', 2, 0, 10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_notification`
--

DROP TABLE IF EXISTS `vhs_notification`;
CREATE TABLE IF NOT EXISTS `vhs_notification` (
  `idNotif` int NOT NULL AUTO_INCREMENT,
  `dateNotif` date NOT NULL,
  `destinataire` varchar(150) DEFAULT NULL,
  `contenu` varchar(255) DEFAULT NULL,
  `vu` tinyint(1) DEFAULT '0',
  `idUtilisateur` int DEFAULT NULL,
  `idJeu` int DEFAULT NULL,
  `idMessage` int DEFAULT NULL,
  PRIMARY KEY (`idNotif`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idJeu` (`idJeu`),
  KEY `idMessage` (`idMessage`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_notification`
--

INSERT INTO `vhs_notification` (`idNotif`, `dateNotif`, `destinataire`, `contenu`, `vu`, `idUtilisateur`, `idJeu`, `idMessage`) VALUES
(1, '2024-11-01', 'Alice', 'New friend request', 0, 1, 1, 1),
(2, '2024-11-02', 'Bob', 'Game invite', 1, 2, 2, 2),
(3, '2024-11-03', 'Charlie', 'Forum mention', 0, 3, 3, 3),
(4, '2024-11-04', 'David', 'Message like', 1, 4, 4, 4),
(5, '2024-11-05', 'Eve', 'Profile view', 0, 5, 5, 5),
(6, '2024-11-06', 'Frank', 'Reply to your post', 1, 6, 6, 6),
(7, '2024-11-07', 'Grace', 'Friend request accepted', 0, 7, 7, 7),
(8, '2024-11-08', 'Heidi', 'Game invite', 1, 8, 8, 8),
(9, '2024-11-09', 'Ivan', 'Message like', 0, 9, 9, 9),
(10, '2024-11-10', 'Judy', 'New comment', 1, 10, 10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_participer`
--

DROP TABLE IF EXISTS `vhs_participer`;
CREATE TABLE IF NOT EXISTS `vhs_participer` (
  `idForum` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idForum`,`idUtilisateur`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_participer`
--

INSERT INTO `vhs_participer` (`idForum`, `idUtilisateur`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_portersur`
--

DROP TABLE IF EXISTS `vhs_portersur`;
CREATE TABLE IF NOT EXISTS `vhs_portersur` (
  `idQuizz` int NOT NULL,
  `idQuestion` int NOT NULL,
  PRIMARY KEY (`idQuizz`,`idQuestion`),
  KEY `idQuestion` (`idQuestion`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_portersur`
--

INSERT INTO `vhs_portersur` (`idQuizz`, `idQuestion`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_question`
--

DROP TABLE IF EXISTS `vhs_question`;
CREATE TABLE IF NOT EXISTS `vhs_question` (
  `idQuestion` int NOT NULL AUTO_INCREMENT,
  `idTMDB` int DEFAULT NULL,
  `contenu` varchar(255) NOT NULL,
  `numero` int NOT NULL,
  `nvDifficulte` varchar(25) DEFAULT NULL,
  `bonneReponse` varchar(150) DEFAULT NULL,
  `idOa` int DEFAULT NULL,
  PRIMARY KEY (`idQuestion`),
  KEY `idOa` (`idOa`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_question`
--

INSERT INTO `vhs_question` (`idQuestion`, `idTMDB`, `contenu`, `numero`, `nvDifficulte`, `bonneReponse`, `idOa`) VALUES
(1, 123, 'What is the opening move in chess?', 1, 'Medium', 'E4', NULL),
(2, 456, 'How many properties in Monopoly?', 2, 'Easy', '28', NULL),
(3, 789, 'What color cards in Uno?', 3, 'Easy', 'Red', NULL),
(4, 1011, 'What is the highest letter score in Scrabble?', 4, 'Hard', '10', NULL),
(5, 1213, 'How many cards in poker?', 5, 'Medium', '52', NULL),
(6, 1415, 'Who is Mr. Green in Clue?', 6, 'Medium', 'Suspect', NULL),
(7, 1617, 'How many players in Catan?', 7, 'Hard', '4', NULL),
(8, 1819, 'How many troops in Risk?', 8, 'Hard', '42', NULL),
(9, 2021, 'Where was Go invented?', 9, 'Medium', 'China', NULL),
(10, 2223, 'What is the grid size in Battleship?', 10, 'Easy', '10x10', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_quizz`
--

DROP TABLE IF EXISTS `vhs_quizz`;
CREATE TABLE IF NOT EXISTS `vhs_quizz` (
  `idQuizz` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `nbQuestion` int DEFAULT NULL,
  `difficulte` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`idQuizz`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_quizz`
--

INSERT INTO `vhs_quizz` (`idQuizz`, `nom`, `theme`, `nbQuestion`, `difficulte`) VALUES
(1, 'Chess Quiz', 'Games', 10, 'Medium'),
(2, 'Monopoly Quiz', 'Games', 15, 'Easy'),
(3, 'Uno Quiz', 'Games', 5, 'Easy'),
(4, 'Scrabble Quiz', 'Games', 20, 'Hard'),
(5, 'Poker Quiz', 'Games', 10, 'Medium'),
(6, 'Clue Quiz', 'Games', 15, 'Medium'),
(7, 'Catan Quiz', 'Games', 10, 'Hard'),
(8, 'Risk Quiz', 'Games', 20, 'Hard'),
(9, 'Go Quiz', 'Games', 10, 'Medium'),
(10, 'Battleship Quiz', 'Games', 15, 'Easy');

-- --------------------------------------------------------

--
-- Structure de la table `vhs_repondre`
--

DROP TABLE IF EXISTS `vhs_repondre`;
CREATE TABLE IF NOT EXISTS `vhs_repondre` (
  `idQuizz` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  `score` int DEFAULT '0',
  PRIMARY KEY (`idQuizz`,`idUtilisateur`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_repondre`
--

INSERT INTO `vhs_repondre` (`idQuizz`, `idUtilisateur`, `score`) VALUES
(1, 1, 90),
(2, 2, 85),
(3, 3, 75),
(4, 4, 80),
(5, 5, 95),
(6, 6, 70),
(7, 7, 85),
(8, 8, 90),
(9, 9, 88),
(10, 10, 92);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_utilisateur`
--

DROP TABLE IF EXISTS `vhs_utilisateur`;
CREATE TABLE IF NOT EXISTS `vhs_utilisateur` (
  `idUtilisateur` int NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `photoProfil` varchar(255) DEFAULT 'default.png',
  `banniereProfil` varchar(255) DEFAULT 'default.png',
  `adressMail` varchar(50) NOT NULL,
  `motDePasse` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`idUtilisateur`),
  UNIQUE KEY `pseudo` (`pseudo`),
  UNIQUE KEY `adressMail` (`adressMail`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_utilisateur`
--

INSERT INTO `vhs_utilisateur` (`idUtilisateur`, `pseudo`, `photoProfil`, `banniereProfil`, `adressMail`, `motDePasse`, `role`) VALUES
(1, 'elona', 'Elona.jpg', 'Elona_banniere.jpg', 'elona@gmail.com', '$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC', 'admin'),
(2, 'Bob', 'default.png', 'default.png', 'bob@example.com', 'password2', 'user'),
(3, 'Charlie', 'default.png', 'default.png', 'charlie@example.com', 'password3', 'admin'),
(4, 'David', 'default.png', 'default.png', 'david@example.com', 'password4', 'user'),
(5, 'Eve', 'default.png', 'default.png', 'eve@example.com', 'password5', 'user'),
(6, 'Frank', 'default.png', 'default.png', 'frank@example.com', 'password6', 'user'),
(7, 'Grace', 'default.png', 'default.png', 'grace@example.com', 'password7', 'user'),
(8, 'Heidi', 'default.png', 'default.png', 'heidi@example.com', 'password8', 'user'),
(9, 'Ivan', 'default.png', 'default.png', 'ivan@example.com', 'password9', 'user'),
(10, 'Judy', 'default.png', 'default.png', 'judy@example.com', 'password10', 'user'),
(11, 'jules', 'jules.jpg', 'jules_banniere.jpg', 'jules@gmail.com', '$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC', 'user');

-- --------------------------------------------------------

--
-- Structure de la table `vhs_voir`
--

DROP TABLE IF EXISTS `vhs_voir`;
CREATE TABLE IF NOT EXISTS `vhs_voir` (
  `idUtilisateur` int NOT NULL,
  `idWatchlist` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idWatchlist`),
  KEY `idWatchlist` (`idWatchlist`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_voir`
--

INSERT INTO `vhs_voir` (`idUtilisateur`, `idWatchlist`) VALUES
(1, 1),
(1, 2),
(2, 2),
(2, 3),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Structure de la table `vhs_watchlist`
--

DROP TABLE IF EXISTS `vhs_watchlist`;
CREATE TABLE IF NOT EXISTS `vhs_watchlist` (
  `idWatchlist` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `idTMDB` text,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idWatchlist`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `vhs_watchlist`
--

INSERT INTO `vhs_watchlist` (`idWatchlist`, `titre`, `genre`, `description`, `visible`, `idTMDB`, `idUtilisateur`) VALUES
(1, 'Favorites', 'Movies', 'Favorite movies to watch', 1, '12345,67890,112233', 1),
(2, 'To Watch', 'Series', 'Series to binge-watch', 1, '44556,77889', 2),
(3, 'Watched', 'Movies', 'Movies already watched', 0, '12345,67890,112233', 3),
(4, 'Series', 'Series', 'Series to watch', 1, '44556,77889', 4),
(5, 'Movies', 'Movies', 'Movies to watch', 0, '12345,67890,112233', 5),
(6, 'TV Shows', 'Series', 'TV shows to watch', 1, '44556,77889', 6),
(7, 'Documentaries', 'Movies', 'Documentaries to watch', 0, '12345,67890,112233', 7),
(8, 'Comedy', 'Series', 'Comedy series to watch', 1, '44556,77889', 8),
(9, 'Drama', 'Movies', 'Drama movies to watch', 0, '12345,67890,112233', 9),
(10, 'Sci-Fi', 'Series', 'Sci-fi series to watch', 1, '44556,77889', 10),
(11, 'ouei', 'non', 'A', 1, NULL, 11);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
