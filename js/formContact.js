document.addEventListener('DOMContentLoaded', function() {
    const formulaire = document.querySelector('form');
    const boutonEnvoyer = document.querySelector('button[type="submit"]');
    const email = document.querySelector('input[name="mail"]');
    const erreurMail = document.getElementById('emailErreur');
    const name = document.getElementById('name');
    const message = document.getElementById('message');

    // Désactivation du bouton d'envoi
    boutonEnvoyer.disabled = true;

    const storedEmail = localStorage.getItem('email');
    if (storedEmail) {
        email.value = storedEmail;
        email.style.border = '1px solid green';
        name.focus();
    } else {
        email.style.border = '1px solid red';
        erreurMail.textContent = 'Veuillez renseigner votre mail';
        email.focus();
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
        const nameValide = name.value.trim() !== '';
        const messageValide = message.value.trim() !== '';
        boutonEnvoyer.disabled = !(emailValide && nameValide && messageValide);
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

    name.addEventListener('input', verifierFormulaire);
    message.addEventListener('input', verifierFormulaire);
});
