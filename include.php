<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Ajout du fichier constantes qui permet de configurer le site
require_once 'config/constantes.php';

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



// //Ajout des modèles
require_once 'modeles/bd.class.php';
require_once 'modeles/oa.class.php';
require_once 'modeles/oa.dao.php';
require_once 'modeles/watchlist.class.php';
require_once 'modeles/watchlist.dao.php';
