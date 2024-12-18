CREATE TABLE vhs_utilisateur
(
    idUtilisateur INT PRIMARY KEY AUTO_INCREMENT,
    pseudo VARCHAR(50) NOT NULL UNIQUE,
    photoProfil VARCHAR(255) DEFAULT 'default.png',
    banniereProfil VARCHAR(255) DEFAULT 'default.png',
    adressMail VARCHAR(50) NOT NULL UNIQUE,
    motDePasse VARCHAR(200) NOT NULL,
    role VARCHAR(50) NOT NULL
);



CREATE TABLE vhs_jeu
(
    idJeu INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    regle VARCHAR(255)
);



CREATE TABLE vhs_forum
(
    idForum INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    description VARCHAR(255),
    theme VARCHAR(50),
    idUtilisateur INT,
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE SET NULL
);



CREATE TABLE vhs_watchlist
(
    idWatchlist INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(50) NOT NULL,
    genre VARCHAR(100),
    description VARCHAR(255),
    visible BOOLEAN DEFAULT FALSE,
    idTMDB TEXT,
    idUtilisateur INT NOT NULL,
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE
);



CREATE TABLE vhs_message
(
    idMessage INT PRIMARY KEY AUTO_INCREMENT,
    contenu VARCHAR(255) NOT NULL,
    nbLike INT DEFAULT 0,
    nbDislike INT DEFAULT 0,
    idUtilisateur INT NOT NULL,
    idForum INT NOT NULL,
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY(idForum) REFERENCES vhs_forum(idForum) ON DELETE CASCADE
);



CREATE TABLE vhs_notification
(
    idNotif INT PRIMARY KEY AUTO_INCREMENT,
    dateNotif DATE NOT NULL,
    destinataire VARCHAR(150),
    contenu VARCHAR(255),
    vu BOOLEAN DEFAULT FALSE,
    idUtilisateur INT,
    idJeu INT,
    idMessage INT,
    FOREIGN KEY (idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY (idJeu) REFERENCES vhs_jeu(idJeu) ON DELETE CASCADE,
    FOREIGN KEY (idMessage) REFERENCES vhs_message (idMessage) ON DELETE CASCADE
);


CREATE TABLE vhs_quizz
(
    idQuizz INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    theme VARCHAR(50),
    nbQuestion INT,
    difficulte VARCHAR(25)
);


CREATE TABLE vhs_question
(
    idQuestion INT PRIMARY KEY AUTO_INCREMENT,
    idTMDB INT,
    contenu VARCHAR(255) NOT NULL,
    numero INT NOT NULL,
    nvDifficulte VARCHAR(25),
    bonneReponse VARCHAR(150),
    idOa INT,
    FOREIGN KEY (idOa) REFERENCES vhs_oa(idOa) ON DELETE SET NULL
);


CREATE TABLE vhs_commentaire
(
    idCom INT PRIMARY KEY AUTO_INCREMENT,
    idTMDB INT NOT NULL,
    contenu VARCHAR(255) NOT NULL,
    dateCommentaire DATE,
    idUtilisateur INT,
    FOREIGN KEY (idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE
);


CREATE TABLE vhs_repondre 
( 
    idQuizz INT, 
    idUtilisateur INT, 
    score INT DEFAULT 0, 
    PRIMARY KEY (idQuizz, idUtilisateur), 
    FOREIGN KEY (idQuizz) REFERENCES vhs_quizz(idQuizz) ON DELETE CASCADE, 
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE 
);



CREATE TABLE vhs_jouerpartie
(
    idJeu INT,
    idUtilisateur INT,
    idUtilisateur2 INT,
    datePartie DATE NOT NULL,
    idJoueurGagnant INT,
    sujetDebat VARCHAR(255),
    PRIMARY KEY(idJeu, idUtilisateur, idUtilisateur2, datePartie),
    FOREIGN KEY(idJeu) REFERENCES vhs_jeu(idJeu) ON DELETE CASCADE,
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY(idUtilisateur2) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE
);



CREATE TABLE vhs_participer
(
    idForum INT,
    idUtilisateur INT,
    PRIMARY KEY(idForum, idUtilisateur),
    FOREIGN KEY(idForum) REFERENCES vhs_forum(idForum) ON DELETE CASCADE,
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE
);



CREATE TABLE vhs_creer
(
    idUtilisateur INT,
    idWatchlist INT,
    PRIMARY KEY(idUtilisateur, idWatchlist),
    FOREIGN KEY(idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY(idWatchlist) REFERENCES vhs_watchlist(idWatchlist) ON DELETE CASCADE
);


CREATE TABLE vhs_porterSur
(

    idQuizz INT,
    idQuestion INT,
    PRIMARY KEY (idQuizz, idQuestion),
    FOREIGN KEY(idQuizz) REFERENCES vhs_quizz(idQuizz) ON DELETE CASCADE,
    FOREIGN KEY(idQuestion) REFERENCES vhs_question(idQuestion) ON DELETE CASCADE
); 


CREATE TABLE vhs_voir (
    idUtilisateur INT,
    idWatchlist INT,
    PRIMARY KEY (idUtilisateur, idWatchlist),
    FOREIGN KEY (idUtilisateur) REFERENCES vhs_utilisateur(idUtilisateur) ON DELETE CASCADE,
    FOREIGN KEY (idWatchlist) REFERENCES vhs_watchlist(idWatchlist) ON DELETE CASCADE
);





-- Table: vhs_utilisateur 
INSERT INTO vhs_utilisateur (pseudo, photoProfil, banniereProfil, adressMail, motDePasse, role) VALUES 
('elona', 'Elona.jpg', 'Elona_banniere.jpg', 'elona@gmail.com', '$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC', 'admin'),
('Bob', 'default.png', 'default.png', 'bob@example.com', 'password2', 'user'), 
('Charlie', 'default.png', 'default.png', 'charlie@example.com', 'password3', 'admin'), 
('David', 'default.png', 'default.png', 'david@example.com', 'password4', 'user'), 
('Eve', 'default.png', 'default.png', 'eve@example.com', 'password5', 'user'), 
('Frank', 'default.png', 'default.png', 'frank@example.com', 'password6', 'user'), 
('Grace', 'default.png', 'default.png', 'grace@example.com', 'password7', 'user'), 
('Heidi', 'default.png', 'default.png', 'heidi@example.com', 'password8', 'user'), 
('Ivan', 'default.png', 'default.png', 'ivan@example.com', 'password9', 'user'), 
('Judy', 'default.png', 'default.png', 'judy@example.com', 'password10', 'user'),
('jules', 'jules.jpg', 'jules_banniere.jpg', 'jules@gmail.com', '$2y$10$xGzdLVzKOOPtWdApztNyrOiJzV0tHT9twPfDiMbVZIlwM89txqpMC', 'user');


-- Table: vhs_jeu 
INSERT INTO vhs_jeu (nom, regle) VALUES 
('Chess', 'Rules for chess game'), 
('Monopoly', 'Rules for monopoly game'),
('Uno', 'Rules for uno game'),
('Scrabble', 'Rules for scrabble game'),
('Poker', 'Rules for poker game'),
('Clue', 'Rules for clue game'),
('Catan', 'Rules for catan game'),
('Risk', 'Rules for risk game'),
('Go', 'Rules for go game'),
('Battleship', 'Rules for battleship game');


-- Table: vhs_forum 
INSERT INTO vhs_forum (nom, description, theme, idUtilisateur) VALUES 
('Chess Talk', 'Discussion about chess', 'Games', 1), 
('Monopoly Fans', 'Discussion about monopoly', 'Games', 2),
('Uno Strategies', 'Tips for uno game', 'Games', 3),
('Scrabble Club', 'Word game tips', 'Games', 4),
('Poker Pros', 'Poker discussions', 'Games', 5),
('Clue Detectives', 'Clue game insights', 'Games', 6),
('Catan Traders', 'Catan strategies', 'Games', 7),
('Risk Masters', 'Risk game tactics', 'Games', 8),
('Go Community', 'Discussions on Go', 'Games', 9),
('Battleship League', 'Battleship game tips', 'Games', 10);


-- Table: vhs_watchlist 
INSERT INTO vhs_watchlist (titre, genre, description, visible, idTMDB, idUtilisateur) VALUES 
('Favorites', 'Movies', 'Favorite movies to watch', TRUE, '12345,67890,112233', 1), 
('To Watch', 'Series', 'Series to binge-watch', TRUE, '44556,77889', 2),
('Watched', 'Movies', 'Movies already watched', FALSE, '12345,67890,112233', 3), 
('Series', 'Series', 'Series to watch', TRUE, '44556,77889', 4),
('Movies', 'Movies', 'Movies to watch', FALSE, '12345,67890,112233', 5), 
('TV Shows', 'Series', 'TV shows to watch', TRUE, '44556,77889', 6),
('Documentaries', 'Movies', 'Documentaries to watch', FALSE, '12345,67890,112233', 7), 
('Comedy', 'Series', 'Comedy series to watch', TRUE, '44556,77889', 8),
('Drama', 'Movies', 'Drama movies to watch', FALSE, '12345,67890,112233', 9), 
('Sci-Fi', 'Series', 'Sci-fi series to watch', TRUE, '44556,77889', 10);


-- Table: vhs_message 
INSERT INTO vhs_message (contenu, nbLike, nbDislike, idUtilisateur, idForum) VALUES 
('I love chess!', 10, 1, 1, 1), 
('Monopoly is great', 5, 0, 2, 2),
('Uno is fun', 7, 2, 3, 3), 
('Scrabble rocks', 3, 1, 4, 4),
('Poker night!', 8, 0, 5, 5), 
('Clue is amazing', 9, 3, 6, 6),
('Catan anyone?', 4, 2, 7, 7), 
('Risk challenges', 6, 2, 8, 8),
('Go strategies', 5, 1, 9, 9), 
('Battleship tips', 2, 0, 10, 10);


-- Table: vhs_notification 
INSERT INTO vhs_notification (dateNotif, destinataire, contenu, vu, idUtilisateur, idJeu, idMessage) VALUES 
('2024-11-01', 'Alice', 'New friend request', FALSE, 1, 1, 1), 
('2024-11-02', 'Bob', 'Game invite', TRUE, 2, 2, 2),
('2024-11-03', 'Charlie', 'Forum mention', FALSE, 3, 3, 3), 
('2024-11-04', 'David', 'Message like', TRUE, 4, 4, 4),
('2024-11-05', 'Eve', 'Profile view', FALSE, 5, 5, 5), 
('2024-11-06', 'Frank', 'Reply to your post', TRUE, 6, 6, 6),
('2024-11-07', 'Grace', 'Friend request accepted', FALSE, 7, 7, 7), 
('2024-11-08', 'Heidi', 'Game invite', TRUE, 8, 8, 8),
('2024-11-09', 'Ivan', 'Message like', FALSE, 9, 9, 9), 
('2024-11-10', 'Judy', 'New comment', TRUE, 10, 10, 10);


-- Table: vhs_quizz 
INSERT INTO vhs_quizz (nom, theme, nbQuestion, difficulte) VALUES 
('Chess Quiz', 'Games', 10, 'Medium'), 
('Monopoly Quiz', 'Games', 15, 'Easy'),
('Uno Quiz', 'Games', 5, 'Easy'), 
('Scrabble Quiz', 'Games', 20, 'Hard'),
('Poker Quiz', 'Games', 10, 'Medium'), 
('Clue Quiz', 'Games', 15, 'Medium'),
('Catan Quiz', 'Games', 10, 'Hard'), 
('Risk Quiz', 'Games', 20, 'Hard'),
('Go Quiz', 'Games', 10, 'Medium'), 
('Battleship Quiz', 'Games', 15, 'Easy');


-- Table: vhs_question 
INSERT INTO vhs_question (idTMDB, contenu, numero, nvDifficulte, bonneReponse, idOa) VALUES 
(123, 'What is the opening move in chess?', 1, 'Medium', 'E4', NULL), 
(456, 'How many properties in Monopoly?', 2, 'Easy', '28', NULL),
(789, 'What color cards in Uno?', 3, 'Easy', 'Red', NULL), 
(1011, 'What is the highest letter score in Scrabble?', 4, 'Hard', '10', NULL),
(1213, 'How many cards in poker?', 5, 'Medium', '52', NULL), 
(1415, 'Who is Mr. Green in Clue?', 6, 'Medium', 'Suspect', NULL),
(1617, 'How many players in Catan?', 7, 'Hard', '4', NULL), 
(1819, 'How many troops in Risk?', 8, 'Hard', '42', NULL),
(2021, 'Where was Go invented?', 9, 'Medium', 'China', NULL), 
(2223, 'What is the grid size in Battleship?', 10, 'Easy', '10x10', NULL);


-- Table: vhs_commentaire
INSERT INTO vhs_commentaire (idTMDB, contenu, dateCommentaire, idUtilisateur) VALUES 
(123, 'Great chess content!', '2024-11-01', 1), 
(456, 'Monopoly is awesome!', '2024-11-02', 2),
(372058, 'Une masterclass :)', '2024-11-03', 1), 
(372058, 'Loved the plot!', '2024-11-04', 4), 
(372058, 'Great acting!', '2024-11-05', 5), 
(789, 'Uno is a fun game!', '2024-11-06', 6), 
(1011, 'Scrabble is challenging!', '2024-11-07', 7), 
(1213, 'Poker nights are the best!', '2024-11-08', 8), 
(1415, 'Clue is a classic!', '2024-11-09', 9), 
(1617, 'Catan is strategic!', '2024-11-10', 10), 
(1819, 'Risk is intense!', '2024-11-11', 1), 
(2021, 'Go is a deep game!', '2024-11-12', 2), 
(2223, 'Battleship is exciting!', '2024-11-13', 3), 
(123, 'Chess is timeless!', '2024-11-14', 4), 
(456, 'Monopoly never gets old!', '2024-11-15', 5), 
(789, 'Uno is a family favorite!', '2024-11-16', 6), 
(1011, 'Scrabble expands vocabulary!', '2024-11-17', 7);


-- Table: vhs_repondre
INSERT INTO vhs_repondre (idQuizz, idUtilisateur, score) VALUES 
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


-- Table: vhs_jouerpartie
INSERT INTO vhs_jouerpartie (idJeu, idUtilisateur, idUtilisateur2, datePartie, idJoueurGagnant, sujetDebat) VALUES 
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


-- Table: vhs_participer
INSERT INTO vhs_participer (idForum, idUtilisateur) VALUES 
(1, 1), 
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(1, 2),
(2, 3);


-- Table: vhs_creer 
INSERT INTO vhs_creer (idUtilisateur, idWatchlist) VALUES 
(1, 1), 
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(1, 3),
(2, 4);


-- Table: vhs_porterSur
INSERT INTO vhs_porterSur (idQuizz, idQuestion) VALUES 
(1, 1), 
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(1, 2),
(2, 3);


-- Table: vhs_voir
INSERT INTO vhs_voir (idUtilisateur, idWatchlist) VALUES 
(1, 1), 
(2, 2),
(3, 3),
(4, 4),
(5, 5),
(6, 6),
(7, 7),
(8, 8),
(9, 9),
(10, 10),
(1, 2),
(2, 3);
