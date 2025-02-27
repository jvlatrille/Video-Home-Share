document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.getElementById("searchWatchlist");
    let clearSearch = document.getElementById("clearSearch");
    let watchlistContainer = document.getElementById("watchlistContainer");
    let watchlistItems = Array.from(watchlistContainer.getElementsByClassName("watchlist-item"));
    let noResultMessage = document.getElementById("noResultMessage");

    function filterWatchlists() {
        let filter = searchInput.value.toLowerCase().trim();
        let found = false;

        watchlistItems.forEach(item => {
            let watchlistTitle = item.getAttribute("data-nom");
            let watchlistGenre = item.getAttribute("data-genre");
            let match = watchlistTitle.includes(filter) || watchlistGenre.includes(filter) || filter === "";
            
            item.style.display = match ? "block" : "none";
            if (match) found = true;
        });

        noResultMessage.style.display = found ? "none" : "block";
    }

    searchInput.addEventListener("input", filterWatchlists);
    clearSearch.addEventListener("click", function () {
        searchInput.value = "";
        filterWatchlists();
    });
});