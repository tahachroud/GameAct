document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('tutorial-search-input');
    const clearButton = document.getElementById('clear-search-btn');
    const tutorialCards = document.querySelectorAll('.tutorial-card');
    const noResultsMessage = document.getElementById('no-results-message');

    // ** 1. Fonction utilitaire de Debouncing **
    const debounce = (func, delay) => {
        let timeoutId;
        return function(...args) {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => {
                func.apply(this, args);
            }, delay);
        };
    };

    // ** 2. Logique principale de recherche et de filtrage **
    const filterTutorials = () => {
        const searchTerm = searchInput.value.trim().toLowerCase();
        let resultsCount = 0;

        // Afficher/Masquer le bouton d'effacement
        clearButton.style.display = searchTerm.length > 0 ? 'flex' : 'none';

        if (searchTerm === "") {
            // Afficher toutes les cartes
            tutorialCards.forEach(card => {
                card.classList.remove('hidden-by-search');
            });
            noResultsMessage.style.display = 'none';
            return;
        }

        // Filtrer les cartes en fonction du terme de recherche
        tutorialCards.forEach(card => {
            // IMPORTANT : Obtenir le titre à partir d'un attribut de données pour une vérification rapide côté client
            const title = card.getAttribute('data-title').toLowerCase();
            
            // Recherche insensible à la casse
            if (title.includes(searchTerm)) {
                // CORRESPONDANCE TROUVÉE
                card.classList.remove('hidden-by-search');
                resultsCount++;
                
                // Optionnel : Surligner le terme de recherche (implémentation de base non incluse pour la performance)
                
            } else {
                // AUCUNE CORRESPONDANCE
                card.classList.add('hidden-by-search');
            }
        });

        // ** 3. Feedback "Aucun résultat" **
        if (resultsCount === 0) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }

        // Optionnel : Afficher le nombre de résultats
        // console.log(`${resultsCount} tutoriels trouvés`);
    };

    // ** 4. Écouteurs d'événements **

    // Recherche avec Debounce sur la saisie
    searchInput.addEventListener('input', debounce(filterTutorials, 300));

    // Fonctionnalité du bouton d'effacement
    clearButton.addEventListener('click', () => {
        searchInput.value = ''; // Effacer l'entrée
        filterTutorials();       // Exécuter le filtre pour afficher tout
        searchInput.focus();     // Retourner le focus à l'entrée
    });
});