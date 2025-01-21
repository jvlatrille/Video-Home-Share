-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: tchipy_pro
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `vhs_commentaire`
--

DROP TABLE IF EXISTS `vhs_commentaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_commentaire` (
  `idCom` int NOT NULL AUTO_INCREMENT,
  `idTMDB` int NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `dateCommentaire` date DEFAULT NULL,
  `idUtilisateur` int DEFAULT NULL,
  PRIMARY KEY (`idCom`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_commentaire`
--

LOCK TABLES `vhs_commentaire` WRITE;
/*!40000 ALTER TABLE `vhs_commentaire` DISABLE KEYS */;
INSERT INTO `vhs_commentaire` VALUES (1,123,'Great chess content!','2024-11-01',1),(2,456,'Monopoly is awesome!','2024-11-02',2),(3,372058,'Une masterclass :)','2024-11-03',1),(4,372058,'Loved the plot!','2024-11-04',4),(5,372058,'Great acting!','2024-11-05',5),(6,789,'Uno is a fun game!','2024-11-06',6),(7,1011,'Scrabble is challenging!','2024-11-07',7),(8,1213,'Poker nights are the best!','2024-11-08',8),(9,1415,'Clue is a classic!','2024-11-09',9),(10,1617,'Catan is strategic!','2024-11-10',10),(11,1819,'Risk is intense!','2024-11-11',1),(12,2021,'Go is a deep game!','2024-11-12',2),(13,2223,'Battleship is exciting!','2024-11-13',3),(14,123,'Chess is timeless!','2024-11-14',4),(15,456,'Monopoly never gets old!','2024-11-15',5),(16,789,'Uno is a family favorite!','2024-11-16',6),(17,1011,'Scrabble expands vocabulary!','2024-11-17',7),(21,372058,'Ce film est un chef d\'oeuvre X)','2024-12-22',11),(22,372058,'2eme commentaire écrit pour tester','2024-12-22',11),(23,372058,'trop bien :)','2025-01-13',17),(25,5174,'p','2025-01-13',1);
/*!40000 ALTER TABLE `vhs_commentaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_creer`
--

DROP TABLE IF EXISTS `vhs_creer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_creer` (
  `idUtilisateur` int NOT NULL,
  `idWatchlist` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idWatchlist`),
  KEY `idWatchlist` (`idWatchlist`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_creer`
--

LOCK TABLES `vhs_creer` WRITE;
/*!40000 ALTER TABLE `vhs_creer` DISABLE KEYS */;
INSERT INTO `vhs_creer` VALUES (1,1),(2,2),(1,3),(3,3),(2,4),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10);
/*!40000 ALTER TABLE `vhs_creer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_forum`
--

DROP TABLE IF EXISTS `vhs_forum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_forum` (
  `idForum` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `idUtilisateur` int DEFAULT NULL,
  PRIMARY KEY (`idForum`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_forum`
--

LOCK TABLES `vhs_forum` WRITE;
/*!40000 ALTER TABLE `vhs_forum` DISABLE KEYS */;
INSERT INTO `vhs_forum` VALUES (1,'Le Jeu de la dame','Ici on parles d\'echec','Games',1),(2,'Gran turismo','Vroum vroum','Games',2),(3,'Star Wars','Que la force soit avec toi','Games',3),(4,'Ghibli','UwU','Games',4),(5,'Poker Pros','Poker discussions','Games',5),(6,'Clue Detectives','Clue game insights','Games',6),(7,'Catan Traders','Catan strategies','Games',7),(8,'Risk Masters','Risk game tactics','Games',8),(9,'Go Community','Discussions on Go','Games',9),(10,'Battleship League','Battleship game tips','Games',10),(13,'a','a','a',1);
/*!40000 ALTER TABLE `vhs_forum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_jeu`
--

DROP TABLE IF EXISTS `vhs_jeu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_jeu` (
  `idJeu` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `regle` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idJeu`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_jeu`
--

LOCK TABLES `vhs_jeu` WRITE;
/*!40000 ALTER TABLE `vhs_jeu` DISABLE KEYS */;
INSERT INTO `vhs_jeu` VALUES (1,'Chess','Rules for chess game'),(2,'Monopoly','Rules for monopoly game'),(3,'Uno','Rules for uno game'),(4,'Scrabble','Rules for scrabble game'),(5,'Poker','Rules for poker game'),(6,'Clue','Rules for clue game'),(7,'Catan','Rules for catan game'),(8,'Risk','Rules for risk game'),(9,'Go','Rules for go game'),(10,'Battleship','Rules for battleship game');
/*!40000 ALTER TABLE `vhs_jeu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_jouerpartie`
--

DROP TABLE IF EXISTS `vhs_jouerpartie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_jouerpartie` (
  `idJeu` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  `idUtilisateur2` int NOT NULL,
  `datePartie` date NOT NULL,
  `idJoueurGagnant` int DEFAULT NULL,
  `sujetDebat` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idJeu`,`idUtilisateur`,`idUtilisateur2`,`datePartie`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idUtilisateur2` (`idUtilisateur2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_jouerpartie`
--

LOCK TABLES `vhs_jouerpartie` WRITE;
/*!40000 ALTER TABLE `vhs_jouerpartie` DISABLE KEYS */;
INSERT INTO `vhs_jouerpartie` VALUES (1,1,2,'2024-11-01',1,'Who is better at chess?'),(1,2,3,'2024-11-11',2,'Chess opening moves'),(2,3,4,'2024-11-02',3,'Monopoly strategy tips'),(2,4,5,'2024-11-12',4,'Monopoly property management'),(3,5,6,'2024-11-03',5,'Uno game rules'),(4,7,8,'2024-11-04',7,'Scrabble word tips'),(5,9,10,'2024-11-05',9,'Poker hand rankings'),(6,1,3,'2024-11-06',1,'Clue game strategies'),(7,2,4,'2024-11-07',2,'Catan trading tips'),(8,5,7,'2024-11-08',5,'Risk game tactics'),(9,6,8,'2024-11-09',6,'Go game techniques'),(10,9,1,'2024-11-10',9,'Battleship positioning');
/*!40000 ALTER TABLE `vhs_jouerpartie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_message`
--

DROP TABLE IF EXISTS `vhs_message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_message` (
  `idMessage` int NOT NULL AUTO_INCREMENT,
  `contenu` varchar(255) NOT NULL,
  `nbLike` int DEFAULT '0',
  `nbDislike` int DEFAULT '0',
  `idUtilisateur` int NOT NULL,
  `idForum` int NOT NULL,
  PRIMARY KEY (`idMessage`),
  KEY `idUtilisateur` (`idUtilisateur`),
  KEY `idForum` (`idForum`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_message`
--

LOCK TABLES `vhs_message` WRITE;
/*!40000 ALTER TABLE `vhs_message` DISABLE KEYS */;
INSERT INTO `vhs_message` VALUES (1,'I love chess!',34,9,1,1),(2,'Monopoly is great',5,0,2,2),(3,'Uno is fun',7,2,3,3),(4,'Scrabble rocks',3,1,4,4),(5,'Poker night!',8,0,5,5),(6,'Clue is amazing',9,3,6,6),(7,'Catan anyone?',4,2,7,7),(8,'Risk challenges',6,2,8,8),(9,'Go strategies',5,1,9,9),(10,'Battleship tips',2,0,10,10),(11,'I love chess!',17,8,1,1),(12,'Monopoly is great',5,0,13,2),(13,'Uno is fun',7,2,11,3),(14,'Scrabble rocks',3,1,18,4),(15,'Poker night!',8,0,18,5),(16,'Clue is amazing',9,3,13,6),(17,'Catan anyone?',4,2,1,7),(18,'Risk challenges',6,2,11,8),(19,'Go strategies',5,1,1,9),(20,'Battleship tips',2,0,18,10),(21,'Battleship tips',2,0,12,10),(22,'I love chess!',17,8,16,1),(23,'Monopoly is great',5,0,17,2),(24,'Uno is fun',7,2,12,3),(25,'Scrabble rocks',3,1,16,4),(26,'Poker night!',8,0,17,5),(27,'Clue is amazing',9,3,12,6),(28,'Catan anyone?',4,2,16,7),(29,'Risk challenges',6,2,17,8),(30,'Go games!',5,1,1,9),(31,'Battleship tips',2,0,18,10);
/*!40000 ALTER TABLE `vhs_message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_notification`
--

DROP TABLE IF EXISTS `vhs_notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_notification` (
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_notification`
--

LOCK TABLES `vhs_notification` WRITE;
/*!40000 ALTER TABLE `vhs_notification` DISABLE KEYS */;
INSERT INTO `vhs_notification` VALUES (2,'2024-11-02','Bob','Game invite',1,13,2,2),(3,'2024-11-03','Charlie','Game invite',0,11,3,3),(4,'2024-11-04','David','Game invite',1,18,4,4),(5,'2024-11-05','Eve','Message like',1,1,5,5),(6,'2024-11-06','Frank','Message dislike',1,6,6,6),(8,'2024-11-08','Heidi','Game invite',1,18,8,8),(9,'2024-11-09','Ivan','Message like',0,13,9,9),(10,'2024-11-10','Judy','Message dislike',1,10,10,10),(12,'2024-11-08','Heidi','Game invite',1,13,8,8),(13,'2024-11-09','Ivan','Message like',0,13,9,9),(14,'2024-11-10','Judy','Message dislike',1,18,10,10),(15,'2024-11-03','Charlie','Game invite',0,11,3,3),(16,'2024-11-05','Eve','Message like',0,13,5,5),(17,'2024-11-07','Grace','Game invite',1,1,7,7),(18,'2024-11-01','Alice','Game invite',1,1,1,1),(20,'2024-11-10','Judy','Message dislike',1,12,10,10),(21,'2024-11-08','Heidi','Game invite',1,16,8,8),(22,'2024-11-09','Ivan','Message like',0,17,9,9),(23,'2024-11-10','Judy','Message dislike',1,12,10,10),(24,'2024-11-03','Charlie','Game invite',0,16,3,3),(25,'2024-11-05','Eve','Message like',0,17,5,5),(26,'2024-11-07','Grace','Game invite',0,12,7,7),(27,'2024-11-01','Alice','Game invite',1,16,1,1),(28,'2024-11-01','Léa','Game invite',1,17,1,1);
/*!40000 ALTER TABLE `vhs_notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_participer`
--

DROP TABLE IF EXISTS `vhs_participer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_participer` (
  `idForum` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idForum`,`idUtilisateur`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_participer`
--

LOCK TABLES `vhs_participer` WRITE;
/*!40000 ALTER TABLE `vhs_participer` DISABLE KEYS */;
INSERT INTO `vhs_participer` VALUES (1,1),(1,2),(2,2),(2,3),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10);
/*!40000 ALTER TABLE `vhs_participer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_portersur`
--

DROP TABLE IF EXISTS `vhs_portersur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_portersur` (
  `idQuizz` int NOT NULL,
  `idQuestion` int NOT NULL,
  PRIMARY KEY (`idQuizz`,`idQuestion`),
  KEY `idQuestion` (`idQuestion`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_portersur`
--

LOCK TABLES `vhs_portersur` WRITE;
/*!40000 ALTER TABLE `vhs_portersur` DISABLE KEYS */;
INSERT INTO `vhs_portersur` VALUES (1,1),(2,2),(2,3),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10),(1,11),(11,12),(11,13),(11,14),(11,15),(11,16),(11,17),(11,18),(11,19),(11,20),(11,21);
/*!40000 ALTER TABLE `vhs_portersur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_question`
--

DROP TABLE IF EXISTS `vhs_question`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_question` (
  `idQuestion` int NOT NULL AUTO_INCREMENT,
  `idTMDB` int DEFAULT NULL,
  `contenu` varchar(255) NOT NULL,
  `numero` int NOT NULL,
  `nvDifficulte` varchar(25) DEFAULT NULL,
  `cheminImage` varchar(255) NOT NULL,
  `bonneReponse` varchar(150) DEFAULT NULL,
  `mauvaiseReponse1` varchar(255) NOT NULL,
  `mauvaiseReponse2` varchar(255) NOT NULL,
  `mauvaiseReponse3` varchar(255) NOT NULL,
  `idOa` int DEFAULT NULL,
  PRIMARY KEY (`idQuestion`),
  KEY `idOa` (`idOa`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_question`
--

LOCK TABLES `vhs_question` WRITE;
/*!40000 ALTER TABLE `vhs_question` DISABLE KEYS */;
INSERT INTO `vhs_question` VALUES (1,123,'What is the opening move in chess?',1,'Medium','','E4','A5','C12','E9',NULL),(2,456,'How many properties in Monopoly?',2,'Easy','','28','12','1','3',NULL),(3,789,'What color cards in Uno?',3,'Easy','','Red','Orange','fuschia','caramel',NULL),(4,1011,'What is the highest letter score in Scrabble?',4,'Hard','','10','12','11','10',NULL),(5,1213,'How many cards in poker?',5,'Medium','','52','53','51','4',NULL),(6,1415,'Who is Mr. Green in Clue?',6,'Medium','','Suspect','Innocent','Coupable','Kind',NULL),(7,1617,'How many players in Catan?',7,'Hard','','4','8','6','5',NULL),(8,1819,'How many troops in Risk?',8,'Hard','','42','3','1','5',NULL),(9,2021,'Where was Go invented?',9,'Medium','','China','','','',NULL),(10,2223,'What is the grid size in Battleship?',10,'Easy','','10x10','','','',NULL),(11,123,'What is the chess piece that does an L?',2,'Medium','','The knight','The rook','The king','the queen',NULL),(12,1234,'Qui est le leader des Avengers',1,'Easy',' ','Captain America','Iron Man','Thor','Hulk',NULL),(13,1234,'Quel est le nom du marteau de Thor',2,'Easy',' ','Mjolnir','Stormbreaker','Gungnir','Excalibur',NULL),(14,1234,'Quel est le nom de l assistant IA d Iron Man',3,'Medium',' ','JARVIS','JEAN','ALFRED','KAREN',NULL),(15,1234,'Quel est le vrai nom de Black Widow',4,'Medium',' ','Natasha Romanoff','Wanda Maximoff','Carol Danvers','Peggy Carter',NULL),(16,1234,'Quel est le nom du méchant violet dans Avengers',5,'Easy',' ','Thanos','Loki','Ultron','Ronan',NULL),(17,1234,'Quelle Pierre d Infinité possède Vision',6,'Medium',' ','Pierre de l Esprit','Pierre de l Espace','Pierre de la Réalité','Pierre de la Puissance',NULL),(18,1234,'Quelle ville a été détruite dans Avengers L ère d Ultron',7,'Hard',' ','Sokovia','New York','Wakanda','Londres',NULL),(19,1234,'Qui a réalisé le premier film Avengers',8,'Medium',' ','Joss Whedon','Frères Russo','Jon Favreau','James Gunn',NULL),(20,1234,'Quel est le nom de l épouse de Hawkeye',9,'Hard',' ','Laura','Wanda','Kate','Natasha',NULL),(21,1234,'En quel métal est fabriqué le bouclier de Captain America',10,'Easy',' ','Vibranium','Adamantium','Titane','Carbonadium',NULL),(22,NULL,'Qui est le plus beau ?',1,'Facile','','O','a','a','a',NULL);
/*!40000 ALTER TABLE `vhs_question` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_quizz`
--

DROP TABLE IF EXISTS `vhs_quizz`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_quizz` (
  `idQuizz` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `theme` varchar(50) DEFAULT NULL,
  `nbQuestion` int DEFAULT NULL,
  `difficulte` varchar(25) DEFAULT NULL,
  PRIMARY KEY (`idQuizz`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_quizz`
--

LOCK TABLES `vhs_quizz` WRITE;
/*!40000 ALTER TABLE `vhs_quizz` DISABLE KEYS */;
INSERT INTO `vhs_quizz` VALUES (1,'Chess Quiz','Games',10,'Medium'),(2,'Monopoly Quiz','Games',15,'Easy'),(3,'Uno Quiz','Games',5,'Easy'),(4,'Scrabble Quiz','Games',20,'Hard'),(5,'Poker Quiz','Games',10,'Medium'),(6,'Clue Quiz','Games',15,'Medium'),(7,'Catan Quiz','Games',10,'Hard'),(8,'Risk Quiz','Games',20,'Hard'),(9,'Go Quiz','Games',10,'Medium'),(10,'Battleship Quiz','Games',15,'Easy'),(11,'Avengers','Games',10,'Easy'),(12,'b','b',1,'1');
/*!40000 ALTER TABLE `vhs_quizz` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_repondre`
--

DROP TABLE IF EXISTS `vhs_repondre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_repondre` (
  `idQuizz` int NOT NULL,
  `idUtilisateur` int NOT NULL,
  `score` int DEFAULT '0',
  PRIMARY KEY (`idQuizz`,`idUtilisateur`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_repondre`
--

LOCK TABLES `vhs_repondre` WRITE;
/*!40000 ALTER TABLE `vhs_repondre` DISABLE KEYS */;
INSERT INTO `vhs_repondre` VALUES (1,1,90),(2,2,85),(3,3,75),(4,4,80),(5,5,95),(6,6,70),(7,7,85),(8,8,90),(9,9,88),(10,10,92);
/*!40000 ALTER TABLE `vhs_repondre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_utilisateur`
--

DROP TABLE IF EXISTS `vhs_utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_utilisateur` (
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
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_utilisateur`
--

LOCK TABLES `vhs_utilisateur` WRITE;
/*!40000 ALTER TABLE `vhs_utilisateur` DISABLE KEYS */;
INSERT INTO `vhs_utilisateur` VALUES (1,'elona','1_elona.jpg','Elona_banniere.jpg','elona@gmail.com','$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC','admin'),(2,'Bob','default.png','default.png','bob@example.com','password2','admin'),(3,'Charlie','default.png','default.png','charlie@example.com','password3','admin'),(5,'Eve','default.png','default.png','eve@example.com','password5','user'),(6,'Frank','default.png','default.png','frank@example.com','password6','user'),(7,'Grace','default.png','default.png','grace@example.com','password7','user'),(8,'Heidi','default.png','default.png','heidi@example.com','password8','user'),(9,'Ivan','default.png','default.png','ivan@example.com','password9','user'),(10,'Judy','default.png','default.png','judy@example.com','password10','user'),(11,'jules','jules.jpg','jules_banniere.jpg','jules@gmail.com','$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC','user'),(12,'Robott64','robott64.jpg','default.png','robott@sfr.fr','$2y$10$beQjQypdv4Nc8WI3qsWEIux1U8tkTUCGi.nWMlyj62xwoIUBbKN12','utilisateur'),(13,'Thibault','default.png','default.png','test@test.fr','$2y$10$beNnVJ5NgdMiQ5h3XEAwz.UIzgDp3GVY25M4vF5Rrpf26q4jdumK6','utilisateur'),(16,'Nathan','default.png','default.png','nathan@moi.fr','$2y$10$zROtDbAjjDdb.KQbdeGtIujslacQly.CtKNVZzQpMT53ecyFwpEkS','utilisateur'),(17,'Lloyd33','lumity !!!!!!!!!!!!!!!!!!!!!!!!!!.jpg','ft.jpg','arobasegmailpointcom@gmail.com','$2y$10$C01IDunMBpt1yqWEPQ9BpeHBcpEAN1PXrXEBTLjCygbYPqD/2T31q','utilisateur'),(18,'Léa','default.png','default.png','lea@mail.com','$2y$10$xR7KnAQUi5mOQuPRXsOdgu7gF7/dQe30R5S5EvxiBK7zgqxc1iksK','utilisateur');
/*!40000 ALTER TABLE `vhs_utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_voir`
--

DROP TABLE IF EXISTS `vhs_voir`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_voir` (
  `idUtilisateur` int NOT NULL,
  `idWatchlist` int NOT NULL,
  PRIMARY KEY (`idUtilisateur`,`idWatchlist`),
  KEY `idWatchlist` (`idWatchlist`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_voir`
--

LOCK TABLES `vhs_voir` WRITE;
/*!40000 ALTER TABLE `vhs_voir` DISABLE KEYS */;
INSERT INTO `vhs_voir` VALUES (1,1),(1,2),(2,2),(2,3),(3,3),(4,4),(5,5),(6,6),(7,7),(8,8),(9,9),(10,10);
/*!40000 ALTER TABLE `vhs_voir` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vhs_watchlist`
--

DROP TABLE IF EXISTS `vhs_watchlist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vhs_watchlist` (
  `idWatchlist` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `genre` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `visible` tinyint(1) DEFAULT '0',
  `idTMDB` text,
  `idUtilisateur` int NOT NULL,
  PRIMARY KEY (`idWatchlist`),
  KEY `idUtilisateur` (`idUtilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vhs_watchlist`
--

LOCK TABLES `vhs_watchlist` WRITE;
/*!40000 ALTER TABLE `vhs_watchlist` DISABLE KEYS */;
INSERT INTO `vhs_watchlist` VALUES (29,'Sci-Fi','Series','Sci-fi series to watch',1,'77889,372058',12),(31,'Sci-Fi','Series','Sci-fi series to watch',1,'77889,372058,278,424',13),(32,'Sci-Fi','Series','Sci-fi series to watch',1,'372058,278',1),(45,'r','f','f',1,'1159311',12),(46,'j','u','j',1,'44115',12),(48,'j','u','j',1,'44115',1),(50,'Sci-Fi','Series','Sci-fi series to watch',1,'77889,372058',1),(51,'Animes','Animes favorits','Mes animés préférés à voir et revoir',1,'10515,51739,8392,4935,128,129,16859,12429,916224,81,37933',1),(52,'j','u','j',1,'44115',1);
/*!40000 ALTER TABLE `vhs_watchlist` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-21  9:19:35
