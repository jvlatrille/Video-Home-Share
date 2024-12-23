<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Récupération des constantes
require_once 'config/init.php';

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout du code pour initialiser la connexion à la base de données

//require_once 'config/connexion.php';

//Ajout des contrôleurs
require_once 'controllers/controller_factory.class.php';
require_once 'controllers/controller.class.php';
require_once 'controllers/controller_oa.class.php';
require_once 'controllers/controller_index.class.php';
require_once 'controllers/controller_watchlist.class.php';
require_once 'controllers/controller_quizz.class.php';
require_once 'controllers/controller_question.class.php';
require_once 'controllers/controller_forum.class.php';
require_once 'controllers/controller_jeux.class.php';
require_once 'controllers/controller_utilisateur.class.php';
require_once 'controllers/controller_profil.class.php';
require_once 'controllers/controller_commentaire.class.php';

require_once 'controllers/controller_newsletter.class.php';



// //Ajout des modèles
require_once 'modeles/bd.class.php';
require_once 'modeles/oa.class.php';
require_once 'modeles/oa.dao.php';
require_once 'modeles/watchlist.class.php';
require_once 'modeles/watchlist.dao.php';
require_once 'modeles/quizz.class.php';
require_once 'modeles/quizz.dao.php';
require_once 'modeles/question.class.php';
require_once 'modeles/question.dao.php';

require_once 'modeles/personne.class.php';
require_once 'modeles/personne.dao.php';
require_once 'modeles/forum.dao.php';
require_once 'modeles/forum.class.php';
require_once 'modeles/notification.class.php';
require_once 'modeles/notification.dao.php';
require_once 'modeles/jeux.class.php';
require_once 'modeles/jeux.dao.php';
require_once 'modeles/utilisateur.class.php';
require_once 'modeles/utilisateur.dao.php';

require_once 'modeles/commentaire.class.php';
require_once 'modeles/commentaire.dao.php';
require_once 'modeles/validator.class.php';
