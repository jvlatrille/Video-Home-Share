# Video-Home-Share
## Présentation de l'application
Video Home Share (VHS) est une application web développée dans le cadre d'une SAÉ 3.01 A&amp;D : Développement d’application et Gestion de projet. Cette application web à pour but de réintroduire la convivialité dans le monde du streaming. L'application comporte plusieurs fonctionnalités, comme : des forums pour partager son point de vue sur des thémes précis, des jeux pour régler des débats entre utilisateurs, des pages d'informations précises sur les films et les séries, des quizzs pour tester nos connaissances sur les oeuvres.

## Arborescence
Le code de notre application est organisé selon le patron de conception MVC : 

Video Home Share:.
│   .gitignore : permet de ne pas prendre en compte des fichiers trop lourds et innutiles comme vendor ou node_modules, ou encore des fichiers sensibles comme le 
│   composer.json : garantie l'installation correcte de toutes les dépendances utiles à l'application pour composer.
│   composer.lock : garantie l'installation correcte de toutes les dépendances utiles à l'application pour composer.
│   include.php : permet l'inclusion correcte de tout les fichiers selon l'arborescence choisie.
│   index.php : 
│   namrein_pro.sql : 
│   package-lock.json : 
│   package.json : 
│   README.md : (donc ce fichier), qui contient une présentation de l'application, de son arborescence, et comment installer le code sur votre ordinateur personnel.
│
├───config
│       init.php : fichier qui permet de charger les constantes globales du fichier 
│       templates.yaml : fichier de template pour les constantes de l'application, à modifier si vous souhaitez utiliser l'application en local.
│       twig.php : 
│
├───controllers
│       Contient l'intégralité des controllers *définition*
│
├───css
│       style.css : fichier pour ajouter des styles spécifiques
│       styles.css : fichier généré via le custom.scss
│       styles.css.map : fichier généré via le custom.scss
│
├───img
│   │   Images avec l'arborescence ci dessous
│   │
│   ├───banniere/
│   │
│   └───profils/
│
├───js
│       Fichier contenant les fichiers de js
├───modeles
│       Modeles *déf*
│
├───SCSS
│       custom.scss
│
└───templates
│       templates *défs*

## Mettre l'application en place sur votre ordinateur
Vous devrez procéder à 3 étapes : 
npm install
composer install
importer la BD
changer le template.yaml
