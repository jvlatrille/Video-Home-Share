document.addEventListener("DOMContentLoaded", function () {
    let boutonsModifier = document.querySelectorAll("button[data-bs-target='#modifierMessageModal']");

    boutonsModifier.forEach(bouton => {
        bouton.addEventListener("click", function () {
            let idMessage = this.value; // Récupère l'idMessage du bouton cliqué
            let inputHidden = document.querySelector("#idMessageInput");

            if (inputHidden) {
                inputHidden.value = idMessage; // Met à jour la valeur de l'input hidden
            }
        });
    });
});
