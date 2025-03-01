document.addEventListener("keydown", function (event) {
    // Vérifie si Alt et Shift sont enfoncés
    if (event.altKey && event.shiftKey) {
        event.preventDefault(); // Empêche le comportement par défaut du navigateur

        // Récupération de la variable utilisateurConnecte depuis Twig
        const utilisateurConnecte = window.utilisateurConnecte || null;

        let targetUrl = "index.php?controleur=utilisateur&methode=connexion"; // Par défaut, page de connexion

        if (event.key === "Shift" || event.key === "Alt") {
            return;
        }
        if (utilisateurConnecte) {
            switch (event.key.toLowerCase()) {
                case "p":
                    targetUrl = `index.php?controleur=profil&methode=afficherAPropos&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "f":
                    targetUrl = "index.php?controleur=forum&methode=listerForum";
                    break;
                case "l":
                    targetUrl = `index.php?controleur=watchlist&methode=listerWatchList&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "c":
                    targetUrl = `http://localhost/Video-Home-Share/index.php?controleur=watchlist&methode=listerWatchListVisible&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "q":
                    targetUrl = "index.php?controleur=Quizz&methode=listerQuizz";
                    break;
                case "j":
                    targetUrl = "index.php?controleur=jeux&methode=listeTableau";
                    break;
                case "s":
                    window.scrollTo({ top: 0, behavior: "smooth" });
                    document.getElementById("recherche")?.focus();
                    return;
                case "a":
                    targetUrl = "index.php";
                    break;
                case "d":
                    targetUrl = "index.php?controleur=utilisateur&methode=deconnexion";
                    break;
            }
        } else {
            if (event.key.toLowerCase() === "f") {
                // Si l'utilisateur n'est pas connecté, ne pas rediriger pour "F" (juste focus sur la barre)
                window.scrollTo({ top: 0, behavior: "smooth" });
                document.getElementById("recherche")?.focus();
                return;
            }
        }

        window.location.href = targetUrl; // Redirige vers l'URL cible
    }
});
