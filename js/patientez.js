document.addEventListener('DOMContentLoaded', () => {
  // Fonction pour afficher le loader global et démarrer l'animation
  function showLoader() {
    const loader = document.getElementById('globalLoader');
    if (loader) {
    loader.classList.remove('d-none');
    startSequentialAnimation();
    }
  }
  
  // Ajoute l'événement seulement aux éléments avec la classe ilFautPatienter
  document.querySelectorAll('.ilFautPatienter').forEach(element => {
    if (element.tagName === 'FORM') {
    element.addEventListener('submit', () => {
      showLoader();
    });
    } else {
    element.addEventListener('click', () => {
      showLoader();
    });
    }
  });
  
  // Animation séquentielle des pop-corns
  function startSequentialAnimation() {
    const popcorns = document.querySelectorAll('#globalLoader .popcorn');
    let index = 0;
    setInterval(() => {
    // Pour le pop-corn courant, on bascule son état
    const popcorn = popcorns[index];
    if (popcorn.getAttribute('src').includes('noteVide.png')) {
      popcorn.setAttribute('src', 'img/noteRemplie.png');
    } else {
      popcorn.setAttribute('src', 'img/noteVide.png');
    }
    // Passage au suivant (s'écrase cycliquement)
    index = (index + 1) % popcorns.length;
    }, 400);
  }
  });
