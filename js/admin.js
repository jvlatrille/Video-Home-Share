// Sauvegarde de la bd
document.getElementById('backupButton').addEventListener('click', function() {
    const statusElement = document.getElementById('backupStatus');
    statusElement.innerHTML = '<span class="text-warning"><i class="fas fa-spinner fa-spin"></i> Sauvegarde en cours...</span>';
    
    fetch('utilitaire/backupBD.php')
        .then(response => response.text())
        .then(data => {
            statusElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Sauvegarde terminée avec succès!</span>';
            setTimeout(() => {
                location.reload();
            }, 2000);
        })
        .catch(error => {
            statusElement.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Erreur lors de la sauvegarde</span>';
            console.error('Erreur:', error);
        });
});

function genererPseudo(userId) {
    const pseudoInput = document.getElementById('pseudo' + userId);
    const newPseudo = 'User' + Math.floor(Math.random() * 10000);
    pseudoInput.value = newPseudo;
}

function resetPhotoProfil(userId) {
    // Change l'image affichée et indique au serveur de réinitialiser la photo
    document.getElementById('photoProfilImg' + userId).src = 'img/profils/default.png';
    document.getElementById('photoProfil' + userId).value = "";
    document.getElementById('hiddenPhotoProfil' + userId).value = 'default.png';
}

function resetBanniereProfil(userId) {
    document.getElementById('banniereProfilImg' + userId).src = 'img/banniere/default.png';
    document.getElementById('banniereProfil' + userId).value = "";
    document.getElementById('hiddenBanniereProfil' + userId).value = 'default.png';
}

// Initialisation DataTables
$(document).ready(function () {
    $('#utilisateurTable').DataTable({
        responsive: true,
        pageLength: 5,
        language: {
            search: "🔍 Rechercher :",
            lengthMenu: "Afficher _MENU_ utilisateurs",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ utilisateurs",
            zeroRecords: "Aucun utilisateur trouvé"
        }
    });

    $('#quizzTable').DataTable({
        responsive: true,
        pageLength: 5,
        language: {
            search: "🔍 Rechercher :",
            lengthMenu: "Afficher _MENU_ Quiz",
            info: "Affichage de _START_ à _END_ sur _TOTAL_ Quiz",
            zeroRecords: "Aucun quiz trouvé"
        }
    });

    $(document).ready(function() {
        $('#forumTable').DataTable({
            responsive: true,
            pageLength: 5,
            language: {
                search: "🔍 Rechercher :",
                lengthMenu: "Afficher _MENU_ posts",
                info: "Affichage de _START_ à _END_ sur _TOTAL_ posts",
                zeroRecords: "Aucun post trouvé"
            }
        });
    });

    $(document).ready(function() {
        $('#commentaireTable').DataTable({
            responsive: true,
            pageLength: 5,
            language: {
                search: "🔍 Rechercher :",
                lengthMenu: "Afficher _MENU_ commentaires",
                info: "Affichage de _START_ à _END_ sur _TOTAL_ commentaires",
                zeroRecords: "Aucun commentaire trouvé"
            }
        });
    });
    $('#backupLogsTable').DataTable({
        responsive: true,
        pageLength: 5,
        language: {
             search: "🔍 Rechercher :",
             lengthMenu: "Afficher _MENU_ sauvegardes",
             info: "Affichage de _START_ à _END_ sur _TOTAL_ sauvegardes",
             zeroRecords: "Aucune sauvegarde trouvée"
        }
   });
});

// Bascule entre sections
document.addEventListener('DOMContentLoaded', function () {
    const btnHome    = document.querySelector('#btnHome');
    const btnUsers   = document.querySelector('#btnUsers');
    const btnQuiz   = document.querySelector('#btnQuiz');
    const btnForum   = document.querySelector('#btnForum');
    const btnCommentaires = document.getElementById('btnCommentaires');
    const sectionHome  = document.querySelector('#sectionHome');
    const sectionUsers = document.querySelector('#sectionUsers');
    const sectionQuiz = document.querySelector('#sectionQuiz');
    const sectionForum = document.querySelector('#sectionForum');
    const sectionCommentaires = document.getElementById('sectionCommentaires');

    // Accueil affiché par défaut
    sectionHome.style.display  = 'block';
    sectionUsers.style.display = 'none';
    sectionQuiz.style.display = 'none';
    sectionForum.style.display = 'none';
    sectionCommentaires.style.display = 'none';

    btnHome.addEventListener('click', function () {
        sectionHome.style.display  = 'block';
        sectionUsers.style.display = 'none';
        sectionQuiz.style.display = 'none';
        sectionForum.style.display = 'none';
        sectionCommentaires.style.display = 'none';
    });
    btnUsers.addEventListener('click', function () {
        sectionHome.style.display  = 'none';
        sectionUsers.style.display = 'block';
        sectionQuiz.style.display = 'none';
        sectionForum.style.display = 'none';
        sectionCommentaires.style.display = 'none';
    });
    btnQuiz.addEventListener('click', function () {
        sectionHome.style.display  = 'none';
        sectionUsers.style.display = 'none';
        sectionQuiz.style.display = 'block';
        sectionForum.style.display = 'none';
        sectionCommentaires.style.display = 'none';
    });
    btnForum.addEventListener('click', function () {
        sectionHome.style.display  = 'none';
        sectionUsers.style.display = 'none';
        sectionQuiz.style.display = 'none';
        sectionForum.style.display = 'block';
        sectionCommentaires.style.display = 'none';
    });
    btnCommentaires.addEventListener('click', function () {
        sectionHome.style.display = 'none';
        sectionUsers.style.display = 'none';
        sectionQuiz.style.display = 'none';
        sectionForum.style.display = 'none';
        sectionCommentaires.style.display = 'block';
    });
});

// Confirmation pour supprimer un utilisateur
function confirmDelete(userId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible !")) {
        window.location.href = "index.php?controleur=admin&methode=supprimerUtilisateur&id=" + userId;
    }
}

// Confirmation pour supprimer un quiz
function confirmDeleteQuiz(quizzId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce quiz ?")) {
        window.location.href = "index.php?controleur=quizz&methode=supprimerQuizz&id=" + quizzId;
    }
}

// Confirmation pour supprimer un post
function confirmDeletePost(postId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce post de forum ? Cette action est irréversible !")) {
        window.location.href = "index.php?controleur=forum&methode=supprimerPost&id=" + postId;
    }
}

// Fonction de confirmation pour supprimer un commentaire
function confirmDeleteComment(idCom, idOa, typeOA) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce commentaire ? Cette action est irréversible !")) {
        window.location.href = "index.php?controleur=commentaire&methode=supprimerCommentaire&idCommentaire=" 
                                + idCom + "&idOa=" + idOa + "&typeOA=" + typeOA;
    }
}  