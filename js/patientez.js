
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



        function patienterBouton(b){
            b.forEach(button => {
                button.addEventListener('click', (event) => {
                    event.preventDefault(); // Empêche le chargement immédiat
    
                    const loader = document.createElement('div');
                    loader.id = 'loadingMessage';
                    loader.innerHTML = `<div class="text-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle">
                                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                            Patienter un instant...
                                        </div>`;
                    document.body.appendChild(loader);
    
                    setTimeout(() => {
                        window.location.href = button.href; // Redirige après un court instant
                    }, 5);
                });
            });

        }

        function patienterLien(l){
            l.forEach(link => {
                        link.addEventListener('click', (event) => {
                            event.preventDefault(); // Empêche le chargement immédiat
            
                            const loader = document.createElement('div');
                            loader.id = 'loadingMessage';
                            loader.innerHTML = `<div class="text-center p-3 bg-dark text-white rounded shadow-lg position-fixed top-50 start-50 translate-middle">
                                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                    Patienter un instant...
                                                </div>`;
                            document.body.appendChild(loader);
            
                            setTimeout(() => {
                                window.location.href = link.href; // Redirige après un court instant
                            }, 5);
                        });
                    });
        }
