<?php

class ControllerFactory{
    public static function getController($controleur, \Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader): Controller {  // Corrected here
        $controllerName = "Controller" . ucfirst($controleur);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);
    }
}