document.addEventListener("keydown", function (event) {
    if (event.altKey && event.shiftKey) {
        event.preventDefault();
        const utilisateurConnecte = window.utilisateurConnecte || null;

        let urlParDefault = "index.php?controleur=utilisateur&methode=connexion";

        if (event.key === "Shift" || event.key === "Alt") {
            return;
        } // éviter ce raccourci seul parce que sinon ça envoie direct vers connexion
        
        if (utilisateurConnecte) {
            switch (event.key.toLowerCase()) {
                case "p":
                    urlParDefault = `index.php?controleur=profil&methode=afficherAPropos&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "f":
                    urlParDefault = "index.php?controleur=forum&methode=listerForum";
                    break;
                case "l":
                    urlParDefault = `index.php?controleur=watchlist&methode=listerWatchList&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "c":
                    urlParDefault = `http://localhost/Video-Home-Share/index.php?controleur=watchlist&methode=listerWatchListVisible&id=${utilisateurConnecte.idUtilisateur}`;
                    break;
                case "q":
                    urlParDefault = "index.php?controleur=Quizz&methode=listerQuizz";
                    break;
                case "j":
                    urlParDefault = "index.php?controleur=jeux&methode=listeTableau";
                    break;
                case "s":
                    window.scrollTo({ top: 0, behavior: "smooth" });
                    document.getElementById("recherche")?.focus();
                    return;
                case "a":
                    urlParDefault = "index.php";
                    break;
                case "d":
                    urlParDefault = "index.php?controleur=utilisateur&methode=deconnexion";
                    break;
                case "y": // Par ce que c'est Jules qui à fait les raccourcis (libre arbitre tu connais)
                    urlParDefault = "index.php?controleur=oa&methode=afficherFilm&idOa=372058";
                    break;
                case "t":
                    urlParDefault = "index.php?controleur=oa&methode=afficherSerie&idOa=37305";
                    break;
            }
        } else {
            if (event.key.toLowerCase() === "f") {
                window.scrollTo({ top: 0, behavior: "smooth" });
                document.getElementById("recherche")?.focus();
                return;
            }
        }

        window.location.href = urlParDefault;
    }
});
