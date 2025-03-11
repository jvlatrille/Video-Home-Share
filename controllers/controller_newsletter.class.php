<?php

/**
 * @file controller_newsletter.class.php
 * @author Nathan AMREIN
 * @brief Controleur de la newsletter 
 * 
 * @version 1.0
 * @date 17/12/2024
 */
class ControllerNewsletter extends Controller
{

    /**
     * @brief Constructeur de la classe ControllerNewsletter
     *
     * @param \Twig\Environment $twig Envrironnement twig
     * @param \Twig\Loader\FilesystemLoader $loader loader de fichiers twig
     */
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    public function afficher()
    {
        $breadcrumb = [
            ['title' => 'Accueil', 'url' => 'index.php'],
            ['title' => 'Newsletter', 'url' => 'index.php?controleur=Newsletter&methode=afficher']
        ];
        $template = $this->getTwig()->load('newsLetter.html.twig');
        echo $template->render(['breadcrumb' => $breadcrumb]);
    }
}
