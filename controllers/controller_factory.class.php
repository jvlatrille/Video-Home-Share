<?php
/**
 * @files controller_factory.class.php
 * @brief Factory pour les contrôleurs
 * @details Cette classe permet de créer un contrôleur en fonction de son nom
 * 
 * @version 1.0
 * @date 2024-08-29
 * @auteur  CHIPY Thibault
 */
class ControllerFactory
{
    public static function getController($controleur, \Twig\Loader\FilesystemLoader $loader, \Twig\Environment $twig)
    {
        $controllerName="Controller".ucfirst($controleur);
        if (!class_exists($controllerName)) {
            throw new Exception("Le controleur $controllerName n'existe pas");
        }
        return new $controllerName($twig, $loader);

    }
}