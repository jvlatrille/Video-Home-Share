# Video-Home-Share
## Présentation de l'application
Video Home Share (VHS) est une application web développée dans le cadre d'une SAÉ 3.01 A&amp;D : Développement d’application et Gestion de projet. Cette application web a pour but de réintroduire la convivialité dans le monde du streaming. L'application comporte plusieurs fonctionnalités, comme : des forums pour partager son point de vue sur des thémes précis, des jeux pour régler des débats entre utilisateurs, des pages d'informations précises sur les films et les séries, des quizz pour tester nos connaissances sur les oeuvres.

## Arborescence
Le code de notre application est organisé selon le patron de conception MVC : 

Video-Home-Share/  
│  
├── .gitignore          # Exclut les fichiers inutiles (vendor, node_modules, fichiers sensibles)  
├── composer.json       # Garantit l'installation correcte des dépendances Composer  
├── composer.lock       # Verrouille les versions des dépendances Composer  
├── include.php         # Inclut correctement les fichiers selon l'arborescence  
├── index.php           # Point d'entrée principal de l'application  
├── namrein_pro.sql     # Structure de la base de données  
├── package-lock.json   # Verrouille les versions des dépendances npm  
├── package.json        # Gestion des dépendances npm  
├── README.md           # Ce fichier  
│  
├── config/  
│   ├── init.php        # Charge les constantes globales  
│   ├── templates.yaml  # Configuration des templates (à modifier pour une utilisation locale)  
│   ├── twig.php        # Configuration de Twig  
│  
├── controllers/        # Contient les contrôleurs de l'application  
│  
├── css/  
│   ├── style.css       # Styles CSS spécifiques  
│   ├── styles.css      # Généré depuis custom.scss  
│   ├── styles.css.map  # Carte source CSS  
│ 
├── docs/  # Fichier contenant la documentation de notre application disponible à l'adresse : https://jvlatrille.github.io/Video-Home-Share/
│  
├── img/  
│   ├── banniere/       # Images pour les bannières  
│   ├── profils/        # Images pour les profils utilisateurs  
│  
├── js/                # Fichiers JavaScript  
│  
├── modeles/           # Modèles de données  
│  
├── SCSS/  
│   ├── custom.scss     # Fichier source SCSS principal  
│  
└── templates/         # Gabarits de rendu Twig  

## Mettre l'application en place sur votre ordinateur
Vous devrez procéder à 4 étapes : 

### 1 - Installer les dépendances de npm :
Entrez la commande ```bash npm install``` dans votre terminal, dans le dossier contenant les fichiers de l'application

### 2 - Installer les dépendances de Composer
Entrez la commande ```bash composer install``` dans votre terminal, dans le dossier contenant les fichiers de l'application

### 3 - Importer la BD  
* Si vous êtes sur Lakartxela, vous pouvez passer cette étape)*
Importez le fichier namrein_pro.sql dans votre gestionnaire de base de données.

### 4 - Modifier le fichier config/template.yaml
Modifiez le fichier config/templates.yaml selon les indications données dedans.

## Technologies utilisées
Bootstrap 5.3.3
HTML, CSS, PHP
JavaScript
Twig 3.14
MySql
Git
