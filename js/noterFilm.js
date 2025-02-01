document.addEventListener("DOMContentLoaded", () => {
  const stars = document.querySelectorAll("#rating-system .star");
  const ratingDisplay = document.getElementById("rating-display"); // Note statique (moyenne)
  const userRatingDisplay = document.getElementById("user-rating-display"); // Note de l'utilisateur
  const ratingSystem = document.getElementById("rating-system");
  const filmId = ratingSystem.getAttribute("data-id");

  // La note statique initiale est celle du film (moyenne), on ne la modifie pas
  const originalRating = parseFloat(ratingDisplay.textContent);
  let selectedRating = null;

  // Fonction pour mettre à jour l'affichage visuel des étoiles
  function updateStars(rating) {
    stars.forEach((star) => {
      const starValue = parseInt(star.getAttribute("data-value"));
      star.src =
        starValue <= rating ? "img/noteRemplie.png" : "img/noteVide.png";
    });
  }

  // Au survol, on met à jour l'affichage des étoiles pour donner un feedback visuel
  stars.forEach((star) => {
    star.addEventListener("mouseover", () => {
      const value = parseInt(star.getAttribute("data-value"));
      updateStars(value);
    });

    star.addEventListener("mouseout", () => {
      // Au mouseout, on réaffiche les étoiles selon la note statique (non modifiée)
      updateStars(originalRating);
    });

    // Lors du clic, on envoie la note et on met à jour l'affichage de la note de l'utilisateur
    star.addEventListener("click", () => {
      const value = parseInt(star.getAttribute("data-value"));
      selectedRating = value;
      updateStars(selectedRating); // Optionnel : mettre en avant la sélection de l'utilisateur

      // Envoie la note via fetch
      fetch("index.php?controleur=oa&methode=noterFilm", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ idFilm: filmId, note: value }),
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            // Met à jour la note de l'utilisateur dans le paragraphe dédié
            if (userRatingDisplay) {
              userRatingDisplay.textContent = selectedRating;
            }
          } else {
            alert("Erreur : " + data.message);
          }
        })
        .catch((error) => {
          console.error("Erreur lors du fetch:", error);
          alert("Une erreur est survenue.");
        });
    });
  });
});
