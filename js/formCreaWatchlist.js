/**
 * @brief Gestion du formulaire de création de watchlist avec des aspects ergonomiques
 * @details Ce script permet de gérer le formulaire de création de watchlist de l'utilisateur
 * en vérifiant que les champs sont bien remplis et en affichant des messages
 * d'erreur si ce n'est pas le cas.
 * @version 2.0
 * @date 30/01/2025
 * @author CHIPY Thibault
 */



//Suggestion de films et séries
let listeOeuvres = [];
									
function ajouterWatchlist(select) {
    const selectedId = select.value;
    const selectedType = select.options[select.selectedIndex].getAttribute('data-type');
    if (!listeOeuvres.includes(selectedId)) {
        const oa = selectedId + ':' + selectedType;
        listeOeuvres.push(oa);
        updateSelectedIds();
        verifierChamps();
        
        const selectedOption = select.options[select.selectedIndex];
        const selectedText = selectedOption.text;
        const selectedImage = selectedOption.getAttribute('data-poster-path');
        const selectedNote = selectedOption.getAttribute('data-note');
        const selectedItem = document.createElement('div');
        selectedItem.className = 'selected-item alert alert-secondary d-flex justify-content-between align-items-center mt-2';
        selectedItem.id = 'item-' + selectedId;
        selectedItem.innerHTML = `
        <span class="d-flex align-items-center">
            <img src="${selectedImage}" alt="${selectedText}" class="me-2" style="width: 50px; height: auto;">
            ${selectedText}
        </span>
        <button type="button" class="btn-close" aria-label="Remove" onclick="supprimerDeWatchlist('${selectedId}', '${selectedType}')"></button>
        `;
        
        document.getElementById('selectedItemsContainer').appendChild(selectedItem);
    }
}

function supprimerDeWatchlist(id, selectedType) {
    const index = listeOeuvres.indexOf(id + ':' + selectedType);
    if (index > -1) {
        listeOeuvres.splice(index, 1);
        updateSelectedIds();
        
        const item = document.getElementById('item-' + id);
        if (item) {
            item.remove();
        }
    }
}

function updateSelectedIds() {
    document.getElementById('selectedIds').value = JSON.stringify(listeOeuvres);
}


function gestionGenre() {
    fetch('index.php?controleur=oa&methode=getGenres')
        .then(response => response.json())
        .then(data => {

            const genreSelect = document.getElementById("genreSelect");
            const selectedGenreInput = document.getElementById("selectedGenre");

            if (!genreSelect || !selectedGenreInput) {
                console.error("Éléments #genreSelect ou #selectedGenre introuvables.");
                return;
            }

            genreSelect.innerHTML = ""; 

            if (!data.genres || !Array.isArray(data.genres)) {
                console.error("Format des données incorrect:", data);
                return;
            }

            // Remplissage du select avec les genres
            data.genres.forEach(genre => {
                const option = document.createElement("option");
                option.value = genre.id;
                option.textContent = genre.name;
                genreSelect.appendChild(option);
            });

            const optionVide = document.createElement("option");
            optionVide.value = "";
            optionVide.textContent = "Choisir un genre";
            optionVide.disabled = true;
            optionVide.selected = true;  // Option par défaut
            genreSelect.insertBefore(optionVide, genreSelect.firstChild);

            function updateSelectedGenre() {
                const genreChoisi = data.genres.find(g => g.id == genreSelect.value);
                if (genreChoisi) {
                    selectedGenreInput.value = `${genreChoisi.id}:${genreChoisi.name}`;
                }
            }   

            // Mettre à jour dès le chargement
            updateSelectedGenre();

            genreSelect.addEventListener("change", updateSelectedGenre);
        })
        .catch(error => console.error("Erreur lors de la récupération des genres:", error));
}



document.addEventListener('DOMContentLoaded', function() {
    gestionGenre();
    chargerSuggestionsParGenre();
});

// Gérer le formulaire
const form = document.getElementById('createWatchlistForm');
const boutonSubmit = document.getElementById('boutonCreer');
const titre = document.getElementById('watchlistTitle');
const genreSelect = document.getElementById('genreSelect');  
const description = document.getElementById('watchlistDescription');
const selectedIds = document.getElementById('selectedIds');

// Si bouton de soumission désactivé au début
boutonSubmit.disabled = true;

// Vérifier si tous les champs obligatoires sont remplis
const titreErreur = document.getElementById('titreErreur');
const genreErreur = document.getElementById('genreErreur');
const descErreur = document.getElementById('descErreur');
const suggestionErreur = document.getElementById('suggestionsErreur');

// Vérifier le titre
function verifierTitre() {
    if (titre.value.trim() === '') {
        titreErreur.innerHTML = 'Le titre est obligatoire';
        titreErreur.style.display = 'block';
        titre.style.border = '2px solid red';
        return false;
    } else {
        titreErreur.style.display = 'none';
        titre.style.border = '';  // Reset border
        return true;
    }
}

// Vérifier le genre
function verifierGenre() {
    if (genreSelect.value === '') {
        genreErreur.innerHTML = 'Le genre est obligatoire';
        genreErreur.style.display = 'block';
        genreSelect.style.border = '2px solid red';
        return false;
    } else {
        genreErreur.style.display = 'none';
        genreSelect.style.border = '';  // Reset border
        return true;
    }
}

// Vérifier la description
function verifierDescription() {
    if (description.value.trim() === '') {
        descErreur.innerHTML = 'La description est obligatoire';
        descErreur.style.display = 'block';
        description.style.border = '2px solid red';
        return false;
    } else {
        descErreur.style.display = 'none';
        description.style.border = '';  // Reset border
        return true;
    }
}

// Vérifier les suggestions
function verifierSuggestions() {
    const selectedIdsValue = document.getElementById('selectedIds').value;
    if (selectedIdsValue === '' || listeOeuvres.length === 0) {
        suggestionErreur.innerHTML = 'Aucune œuvre ajoutée à la watchlist';
        suggestionErreur.style.display = 'block';
        return false;
    } else {
        suggestionErreur.style.display = 'none';
        return true;
    }
}


// Vérifier tous les champs et activer/désactiver le bouton
function verifierChamps() {
    if (verifierTitre() && verifierGenre() && verifierDescription() && verifierSuggestions()) {
        boutonSubmit.disabled = false;
    } else {
        boutonSubmit.disabled = true;
    }
}

form.addEventListener('input', function(event) {
    verifierChamps();


});

//Mettre a jour les suggestions en fonction du genre choisi
function chargerSuggestionsParGenre() {
    const genreSelect = document.getElementById('genreSelect');

    if (!genreSelect) {
        console.error("Élément #genreSelect introuvable.");
        return;
    }

    genreSelect.addEventListener('change', function() {
        const genreId = genreSelect.value;

        if (!genreId) {
            console.log('Aucun genre sélectionné.');
            return;
        }

        fetch(`index.php?controleur=oa&methode=getSuggestionsByGenre&genre=${genreId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.suggestions) {
                    const suggestionsSelect = document.getElementById('watchlistSuggestions');
                    suggestionsSelect.innerHTML = ''; 

                    data.suggestions.forEach(suggestion => {
                        const option = document.createElement('option');
                        option.value = suggestion.id;
                        option.textContent = suggestion.title;
                        option.setAttribute('data-poster-path', "https://image.tmdb.org/t/p/original/"+suggestion.poster_path);
                        option.setAttribute('data-type', "Film");
                        option.setAttribute('data-note', suggestion.vote_average+"/10");
                        suggestionsSelect.appendChild(option);
                    });
                } else {
                    console.error('Erreur lors de la récupération des suggestions:', data.message);
                }
            })
            .catch(error => console.error("Erreur lors de la récupération des suggestions:", error));
    });
}


