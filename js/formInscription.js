/**
 * @brief Gestion du formulaire d'inscription avec des aspects ergonomiques
 * @details Ce script permet de gérer le formulaire d'inscription de l'utilisateur
 * en vérifiant que les champs sont bien remplis et en affichant des messages
 * d'erreur si ce n'est pas le cas.
 * @version 1.1
 * @date 28/01/2025
 * @author CHIPY Thibault
 */
    
window.addEventListener('load', function () {
    const formulaire = document.getElementById('form');
    const pseudo = document.getElementById('pseudo');
    const dateNaiss = document.getElementById('dateNaiss');
    const mail = document.getElementById('mail');
    const mdp = document.getElementById('mdp');
    const mdpVerif = document.getElementById('mdpVerif');
    const boutonInscription = document.getElementById('boutonInscription');

    const pseudoError = document.getElementById('pseudoErreur');
    const dateNaissError = document.getElementById('dateNaissErreur');
    const mailError = document.getElementById('mailErreur');
    const mdpError = document.getElementById('mdpErreur');
    const mdpVerifError = document.getElementById('mdpVerifErreur');

    // Désactivation du bouton d'inscription initialement
    boutonInscription.disabled = true;

    function verifierPseudo() {
        if (pseudo.value.trim() === '') {
            afficherErreur(pseudo, pseudoError, "Le pseudo est obligatoire");
            return false;
        }
        cacherErreur(pseudo, pseudoError);
        return true;
    }

    function verifierDateNaiss() {
        if (!dateNaiss.value) {
            afficherErreur(dateNaiss, dateNaissError, "La date de naissance est obligatoire");
            return false;
        }
        const dateNaissValue = new Date(dateNaiss.value);
        const age = new Date().getFullYear() - dateNaissValue.getFullYear();
        const monthDiff = new Date().getMonth() - dateNaissValue.getMonth();
        const dayDiff = new Date().getDate() - dateNaissValue.getDate();
        if (age < 13 || (age === 13 && (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)))) {
            afficherErreur(dateNaiss, dateNaissError, "Vous devez avoir plus de 13 ans");
            return false;
        }
        cacherErreur(dateNaiss, dateNaissError);
        return true;
    }

    function verifierMail() {
        const emailValide = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,18}$/.test(mail.value);
        if (!emailValide) {
            afficherErreur(mail, mailError, "Veuillez renseigner une adresse e-mail valide");
            return false;
        }
        cacherErreur(mail, mailError);
        return true;
    }

    function verifierMdp() {
        const messages = [];
        if (!mdp.value) {
            messages.push("Le mot de passe est obligatoire");
        } else {
            if (mdp.value.length < 8) messages.push("Le mot de passe doit contenir au moins 8 caractères");
            if (!/[A-Z]/.test(mdp.value)) messages.push("Le mot de passe doit contenir au moins une majuscule");
            if (!/[a-z]/.test(mdp.value)) messages.push("Le mot de passe doit contenir au moins une minuscule");
            if (!/\d/.test(mdp.value)) messages.push("Le mot de passe doit contenir au moins un chiffre");
            if (!/[@$!%*?&]/.test(mdp.value)) messages.push("Le mot de passe doit contenir au moins un caractère spécial");
        }

        if (messages.length > 0) {
            mdpError.innerHTML="<ul>" + messages.map(msg => `<li>${msg}</li>`).join('') + "</ul>";
            return false;
        }
        cacherErreur(mdp, mdpError);
        return true;
    }

    function verifierMdpVerif() {
        if (mdpVerif.value !== mdp.value) {
            afficherErreur(mdpVerif, mdpVerifError, "Les mots de passe ne correspondent pas");
            return false;
        }
        cacherErreur(mdpVerif, mdpVerifError);
        return true;
    }

    function afficherErreur(champ, errorElement, message) {
        errorElement.innerHTML = message;
        errorElement.style.display = 'block';
        champ.style.border = '3px solid red';
    }

    function cacherErreur(champ, errorElement) {
        errorElement.style.display = 'none';
        errorElement.innerHTML = '';
        champ.style.border = '3px solid green';
    }

    function verifierFormulaire() {
        const pseudoValide = verifierPseudo();
        const dateNaissValide = verifierDateNaiss();
        const mailValide = verifierMail();
        const mdpValide = verifierMdp();
        const mdpVerifValide = verifierMdpVerif();

        boutonInscription.disabled = !(pseudoValide && dateNaissValide && mailValide && mdpValide && mdpVerifValide);
    }

    // Gestion des événements d'entrée utilisateur
    pseudo.addEventListener('input', verifierFormulaire);
    dateNaiss.addEventListener('input', verifierFormulaire);
    mail.addEventListener('input', verifierFormulaire);
    mdp.addEventListener('input', verifierFormulaire);
    mdpVerif.addEventListener('input', verifierFormulaire);
});
