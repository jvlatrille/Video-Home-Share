document.addEventListener('DOMContentLoaded', () => {
	const API_KEY = TMDB_API_KEY;
	const searchInput = document.getElementById('search-input');
	const searchResults = document.getElementById('search-results');

	async function fetchMovies(query) {
		if (!query) {
			searchResults.style.display = 'none';
			return;
		}
		
		const url = `https://api.themoviedb.org/3/search/movie?api_key=${API_KEY}&query=${encodeURIComponent(query)}`;
		
		try {
			const response = await fetch(url);
			const data = await response.json();
			displayResults(data.results);
            console.log(data.results);
		} catch (error) {
			console.error('Error fetching data:', error);
		}
	}

	function displayResults(movies) {
		searchResults.innerHTML = '';
		if (movies.length === 0) {
			searchResults.style.display = 'none';
			return;
		}
		
		movies.slice(0, 3).forEach(movie => {
			const div = document.createElement('div');
			div.classList.add('result-item', 'list-group-item');
			div.textContent = movie.title;
			div.onclick = () => {
				searchInput.value = movie.title;
				searchResults.style.display = 'none';
			};
			searchResults.appendChild(div);
		});
		
		searchResults.style.display = 'block';
	}

	searchInput.addEventListener('input', () => {
		const query = searchInput.value.trim();
		fetchMovies(query);
	});

	document.addEventListener('click', (e) => {
		if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
			searchResults.style.display = 'none';
		}
	});
});
