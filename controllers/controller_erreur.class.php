<?php
class ErreurController extends Controller {
    /**
     * Affiche une page d'erreur avec un message personnalisé.
     *
     * @param string $message Le message d'erreur à afficher.
     */
    public function renderErreur(string $message) {
        echo $this->getTwig()->render('erreur.html.twig', [
            'message' => $message
        ]);
    }
}
