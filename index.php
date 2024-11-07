<?php

//Ajout du code commun à toutes les pages
require_once 'include.php';



try  {
    if (isset($_GET['controleur'])){
        $controllerName=$_GET['controleur'];
    }else{
        $controllerName='';
    }

    if (isset($_GET['methode'])){
        $methode=$_GET['methode'];
    }else{
        $methode='';
    }

    //Gestion de la page d'accueil par défaut
    if ($controllerName == '' && $methode ==''){
        $controllerName='index';
        $methode='render';
    }

    if ($controllerName == '' ){
        throw new Exception('Le controleur n\'est pas défini');
    }

    if ($methode == '' ){
        throw new Exception('La méthode n\'est pas définie');
    }

    $controller = ControllerFactory::getController($controllerName, $loader, $twig);
  
    $controller->call($methode);
}catch (Exception $e) {
   die('Erreur : ' . $e->getMessage());
}