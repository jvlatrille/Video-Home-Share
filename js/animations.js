document.addEventListener("DOMContentLoaded", () => {
    // Animation d'apparition sur les éléments marqués avec la classe .animate
    const animateElements = document.querySelectorAll('.animate');
    animateElements.forEach(el => {
        // Forcer le recalcul pour déclencher la transition
        setTimeout(() => {
            el.classList.add('fade-in');
        }, 50);
    });

    // Animation au survol pour les cartes
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('card-hover');
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('card-hover');
        });
    });

    // Smooth scroll sur les liens ayant la classe .smooth-scroll
    const smoothLinks = document.querySelectorAll('.smooth-scroll');
    smoothLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});
