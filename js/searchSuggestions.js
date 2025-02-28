document.addEventListener('DOMContentLoaded', () => {
    const API_KEY = TMDB_API_KEY;
    const searchInput = document.getElementById('recherche');
    const searchResults = document.getElementById('search-results');

    async function chercherOeuvre(query) {
        if (!query) {
            searchResults.classList.add('d-none');
            return;
        }

        const url = `https://api.themoviedb.org/3/search/multi?api_key=${API_KEY}&query=${encodeURIComponent(query)}`;

        try {
            const response = await fetch(url);
			if(response.length === 0){
				searchResults.classList.add('d-none');
				return;
			}
            const data = await response.json();
            displayResults(data.results);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

	function displayResults(oeuvre) {
		searchResults.innerHTML = '';
		if (oeuvre.length === 0) {
			searchResults.classList.add('d-none');
			return;
		}

		oeuvre.slice(0, 5).forEach(oeuvre => {
			if (!(oeuvre.name || oeuvre.title)) return;
			
			const div = document.createElement('a');
			div.classList.add('list-group-item', 'list-group-item-action', 'result-item');
			
			//Gérer les séries et les films
			if (oeuvre.media_type === 'tv' && oeuvre.name) {
				div.textContent = oeuvre.name;
				div.href = `index.php?controleur=oa&methode=afficherSerie&idOa=${oeuvre.id}`;
			} else if (oeuvre.title) {
				div.textContent = oeuvre.title;
				div.href = `index.php?controleur=oa&methode=afficherFilm&idOa=${oeuvre.id}`;
			} else {
				//sil il y a rien on retourne rien
				return;
			}
			
			searchResults.appendChild(div);
		});

		searchResults.classList.remove('d-none');
	}

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.trim();
        chercherOeuvre(query);
    });

    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('d-none');
        }
    });
});
