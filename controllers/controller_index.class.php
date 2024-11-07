<?php

class ControllerIndex extends Controller{
    public function __construct(\Twig\Environment $twig, \Twig\Loader\FilesystemLoader $loader)
    {
        parent::__construct($twig, $loader);
    }

    // Render base template
    public function render()
    {
        $template = $this->getTwig()->load('index.html.twig');
        echo $template->render();
    }
}