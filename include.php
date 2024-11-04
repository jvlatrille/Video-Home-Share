<?php

//Ajout de l'autoload de composer
require_once 'vendor/autoload.php';

//Ajout du fichier constantes qui permet de configurer le site
require_once 'config/constantes.php';

//Ajout du code pour initialiser twig
require_once 'config/twig.php';

//Ajout du code pour initialiser la connexion à la base de données
require_once 'config/connexion.php';

//Ajout des contrôleurs
require_once 'controllers/jsp.php';
//Ajout des modèles
require_once 'modeles/jsp.php';