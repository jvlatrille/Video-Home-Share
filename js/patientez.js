// Bouton découvrir ds caroussel page accueil
const boutonDecouvrir = document.querySelectorAll('.btn.btn-primary.mt-2');
// A la une
const lienALaUne = document.querySelectorAll('.container.mt-4 .row-cols-md-5 a');
// Découvrir plus d'oeuvres
const boutonDecouvrirPlus = document.querySelectorAll('#decouvrirOaContainer .row a');
// Suggestion film
const lienCarouselSuggFilm = document.querySelectorAll('#carouselSuggestions a');
// Suggestion séries
const lienCarouselSuggSerie = document.querySelectorAll('#carouselSuggestions a');
// Film d'une watchlist
const lienCarouselWatchList = document.querySelectorAll('#carouselWatchList a');

document.addEventListener('DOMContentLoaded', () => {
    function patienterBouton(elements) {
        if (!elements || elements.length === 0) return;

        elements.forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();

                // Remplacez la création du loader dans les deux fonctions par ceci:
                const overlay = document.createElement('div');
                overlay.id = 'loadingOverlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // Fond noir semi-transparent
                overlay.style.zIndex = '9999';

                const loader = document.createElement('div');
                loader.id = 'loadingMessage';
                loader.innerHTML = `<div class="text-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle" style="z-index: 10000;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Patienter un instant...
                    </div>`;

                document.body.appendChild(overlay);
                document.body.appendChild(loader);

                
                setTimeout(() => {
                    window.location.href = button.href;
                    overlay.remove();
                }, 5);
            });
        });
    }

    function patienterLien(elements) {
        if (!elements || elements.length === 0) return;

        elements.forEach(link => {
            link.addEventListener('click', (event) => {
                event.preventDefault();

                // Remplacez la création du loader dans les deux fonctions par ceci:
                const overlay = document.createElement('div');
                overlay.id = 'loadingOverlay';
                overlay.style.position = 'fixed';
                overlay.style.top = '0';
                overlay.style.left = '0';
                overlay.style.width = '100%';
                overlay.style.height = '100%';
                overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.5)'; // Fond noir semi-transparent
                overlay.style.zIndex = '9999';

                const loader = document.createElement('div');
                loader.id = 'loadingMessage';
                loader.innerHTML = `<div class="text-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle" style="z-index: 10000;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                        Patienter un instant...
                    </div>`;

                document.body.appendChild(overlay);
                document.body.appendChild(loader);

                
                setTimeout(() => {
                    window.location.href = button.href;
                    overlay.remove();
                }, 5);

            });
        });
    }

    patienterBouton(document.querySelectorAll('.btn.btn-primary.mt-2')); // Boutons Découvrir
    patienterLien(document.querySelectorAll('.container.mt-4 .row-cols-md-5 a')); // À la une
    patienterBouton(document.querySelectorAll('#decouvrirOaContainer .row a')); // Découvrir plus
    patienterLien(document.querySelectorAll('#carouselSuggestions a')); // Suggestions
    patienterLien(document.querySelectorAll('#carouselWatchList a')); // Watchlist
});
