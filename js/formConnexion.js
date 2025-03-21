/**
 * @brief Gestion du formulaire de connexion avec des aspects ergonomiques
 * @details Ce script permet de gérer le formulaire de connexion de l'utilisateur
 * en vérifiant que les champs sont bien remplis et en affichant des messages
 * d'erreur si ce n'est pas le cas.
 * @version 1.1
 * @date 28/01/2025
 * @author CHIPY Thibault
 */

document.addEventListener('DOMContentLoaded', function() {
    const formulaire = document.querySelector('form');
    const boutonConnexion = document.getElementById('boutonConnexion');
    const email = document.getElementById('email');
    const erreurMail = document.getElementById('emailErreur');
    const mdp = document.getElementById('mdp');
    const erreurMdp = document.getElementById('mdpErreur');

    // Désactivation du bouton de connexion
    boutonConnexion.disabled = true;

    const storedEmail = localStorage.getItem('email');
    if (storedEmail) {
        email.value = storedEmail;
        email.style.border = '1px solid green';
        mdp.focus();
    } else {
        email.style.border = '1px solid red';
        erreurMail.textContent = 'Veuillez renseigner votre mail';
        email.focus();
    }
    mdp.style.border = '1px solid red';



    function verifierMdp() {
        const messages = [];
       if(mdp.value.length === 0 || mdp.value.length < 8) {
            messages.push('Veuillez renseigner un mot de passe');
            erreurMdp.textContent = messages;
            mdp.style.border = '3px solid red';
            return false;
        }
        else{
            erreurMdp.style.display = 'none';
            mdp.style.border = '3px solid green';
            return true;
        }
    }

    function verifierEmail() {
        const emailValide = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,18}$/.test(email.value);
        if (emailValide) {
            erreurMail.textContent = '';
            email.style.border = '3px solid green';
            localStorage.setItem('email', email.value);
            return true;
        } else {
            erreurMail.textContent = 'Veuillez renseigner un email valide';
            email.style.border = '3px solid red';
            return false;
        }
    }

    function verifierFormulaire() {
        const emailValide = verifierEmail();
        const mdpValide = verifierMdp();
        boutonConnexion.disabled = !(emailValide && mdpValide);
    }

    email.addEventListener('input', function() {
        if (verifierEmail()) {
            erreurMail.textContent = '';
            email.style.border = '1px solid green';
        } else {
            erreurMail.textContent = 'Veuillez renseigner un email valide';
            email.style.border = '1px solid red';
        }
        verifierFormulaire();
    });

    mdp.addEventListener('input', function() {
        verifierMdp();
        verifierFormulaire();
    });
});
