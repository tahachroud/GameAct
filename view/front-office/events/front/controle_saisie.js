// Contrôle de saisie pour le formulaire d'événement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('formEvent');
    
    // Fonction pour afficher un message d'erreur
    function showError(fieldId, message) {
        const errorElement = document.getElementById('error-' + fieldId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.color = 'red';
            errorElement.style.fontSize = '0.875rem';
            errorElement.style.marginTop = '5px';
            errorElement.style.display = 'block';
        }
    }
    
    // Fonction pour effacer un message d'erreur
    function clearError(fieldId) {
        const errorElement = document.getElementById('error-' + fieldId);
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
        }
    }
    
    // Validation du titre
    function validateTitre() {
        const titre = document.getElementById('titre').value.trim();
        if (titre === '') {
            showError('titre', 'Le titre est obligatoire');
            return false;
        }
        if (titre.length < 3) {
            showError('titre', 'Le titre doit contenir au moins 3 caractères');
            return false;
        }
        if (titre.length > 100) {
            showError('titre', 'Le titre ne doit pas dépasser 100 caractères');
            return false;
        }
        clearError('titre');
        return true;
    }
    
    // Validation de la date
    function validateDate() {
        const date = document.getElementById('date').value.trim();
        if (date === '') {
            showError('date', 'La date est obligatoire');
            return false;
        }
        // Format YYYY-MM-DD
        const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
        if (!dateRegex.test(date)) {
            showError('date', 'Format de date invalide. Utilisez YYYY-MM-DD');
            return false;
        }
        const dateObj = new Date(date);
        if (isNaN(dateObj.getTime())) {
            showError('date', 'Date invalide');
            return false;
        }
        clearError('date');
        return true;
    }
    
    // Validation de l'heure de début
    function validateHeureDeb() {
        const heureDeb = document.getElementById('heure_deb').value.trim();
        if (heureDeb === '') {
            showError('heure_deb', 'L\'heure de début est obligatoire');
            return false;
        }
        // Format HH:MM
        const heureRegex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
        if (!heureRegex.test(heureDeb)) {
            showError('heure_deb', 'Format d\'heure invalide. Utilisez HH:MM (ex: 14:30)');
            return false;
        }
        clearError('heure_deb');
        return true;
    }
    
    // Validation de l'heure de fin
    function validateHeureFin() {
        const heureFin = document.getElementById('heure_fin').value.trim();
        if (heureFin === '') {
            showError('heure_fin', 'L\'heure de fin est obligatoire');
            return false;
        }
        // Format HH:MM
        const heureRegex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
        if (!heureRegex.test(heureFin)) {
            showError('heure_fin', 'Format d\'heure invalide. Utilisez HH:MM (ex: 18:00)');
            return false;
        }
        
        // Vérifier que l'heure de fin est après l'heure de début
        const heureDeb = document.getElementById('heure_deb').value.trim();
        if (heureDeb !== '') {
            const heureDebRegex = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;
            if (heureDebRegex.test(heureDeb)) {
                const date = document.getElementById('date').value.trim();
                if (date !== '') {
                    const dateDeb = new Date(date + 'T' + heureDeb);
                    const dateFin = new Date(date + 'T' + heureFin);
                    if (dateFin <= dateDeb) {
                        showError('heure_fin', 'L\'heure de fin doit être après l\'heure de début');
                        return false;
                    }
                }
            }
        }
        
        clearError('heure_fin');
        return true;
    }
    
    // Validation du lieu
    function validateLieu() {
        const lieu = document.getElementById('lieu').value.trim();
        if (lieu === '') {
            showError('lieu', 'Le lieu est obligatoire');
            return false;
        }
        if (lieu.length < 2) {
            showError('lieu', 'Le lieu doit contenir au moins 2 caractères');
            return false;
        }
        if (lieu.length > 100) {
            showError('lieu', 'Le lieu ne doit pas dépasser 100 caractères');
            return false;
        }
        clearError('lieu');
        return true;
    }
    
    // Validation du statut
    function validateStatut() {
        const statut = document.getElementById('statut').value;
        if (statut === '') {
            showError('statut', 'Le statut est obligatoire');
            return false;
        }
        const statutsValides = ['à venir', 'en cours', 'terminé', 'annulé'];
        if (!statutsValides.includes(statut)) {
            showError('statut', 'Statut invalide');
            return false;
        }
        clearError('statut');
        return true;
    }
    
    // Validation de la description
    function validateDescription() {
        const description = document.getElementById('description').value.trim();
        if (description === '') {
            showError('description', 'La description est obligatoire');
            return false;
        }
        if (description.length < 10) {
            showError('description', 'La description doit contenir au moins 10 caractères');
            return false;
        }
        if (description.length > 1000) {
            showError('description', 'La description ne doit pas dépasser 1000 caractères');
            return false;
        }
        clearError('description');
        return true;
    }
    
    // Validation complète du formulaire
    function validateForm() {
        let isValid = true;
        
        isValid = validateTitre() && isValid;
        isValid = validateDate() && isValid;
        isValid = validateHeureDeb() && isValid;
        isValid = validateHeureFin() && isValid;
        isValid = validateLieu() && isValid;
        isValid = validateStatut() && isValid;
        isValid = validateDescription() && isValid;
        
        return isValid;
    }
    
    // Ajouter les écouteurs d'événements pour la validation en temps réel
    document.getElementById('titre').addEventListener('blur', validateTitre);
    document.getElementById('titre').addEventListener('input', function() {
        if (document.getElementById('error-titre').textContent !== '') {
            validateTitre();
        }
    });
    
    document.getElementById('date').addEventListener('blur', validateDate);
    document.getElementById('date').addEventListener('input', function() {
        if (document.getElementById('error-date').textContent !== '') {
            validateDate();
        }
    });
    
    document.getElementById('heure_deb').addEventListener('blur', validateHeureDeb);
    document.getElementById('heure_deb').addEventListener('input', function() {
        if (document.getElementById('error-heure_deb').textContent !== '') {
            validateHeureDeb();
        }
        // Re-valider l'heure de fin si elle est déjà remplie
        if (document.getElementById('heure_fin').value.trim() !== '') {
            validateHeureFin();
        }
    });
    
    document.getElementById('heure_fin').addEventListener('blur', validateHeureFin);
    document.getElementById('heure_fin').addEventListener('input', function() {
        if (document.getElementById('error-heure_fin').textContent !== '') {
            validateHeureFin();
        }
    });
    
    document.getElementById('lieu').addEventListener('blur', validateLieu);
    document.getElementById('lieu').addEventListener('input', function() {
        if (document.getElementById('error-lieu').textContent !== '') {
            validateLieu();
        }
    });
    
    document.getElementById('statut').addEventListener('change', validateStatut);
    
    document.getElementById('description').addEventListener('blur', validateDescription);
    document.getElementById('description').addEventListener('input', function() {
        if (document.getElementById('error-description').textContent !== '') {
            validateDescription();
        }
    });
    
    // Validation à la soumission du formulaire
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
    });
    
    // Rendre clearError accessible globalement pour la carte
    window.clearError = clearError;
});

// ========================================
// 🗺️ INTÉGRATION DE LA CARTE LEAFLET
// ========================================

// Variables globales pour la carte
var map = null;
var marker = null;

/**
 * Toggle l'affichage de la popup de la carte
 */
function toggleMap() {
    var popup = document.getElementById('mapPopup');
    popup.classList.toggle('show');
    
    // Initialiser la carte uniquement à la première ouverture
    if (map === null && popup.classList.contains('show')) {
        initializeMap();
    }
    
    // Corriger la taille de la carte si elle existe déjà
    if (map !== null && popup.classList.contains('show')) {
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    }
}

/**
 * Fermer la popup de la carte
 */
function closeMap() {
    document.getElementById('mapPopup').classList.remove('show');
}

/**
 * Initialiser la carte Leaflet
 */
function initializeMap() {
    // Créer la carte centrée sur la France
    map = L.map('map').setView([46.2276, 2.2137], 6);
    
    // Ajouter les tuiles OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // Événement de clic sur la carte
    map.on('click', function(e) {
        var lat = e.latlng.lat.toFixed(4);
        var lng = e.latlng.lng.toFixed(4);
        var coords = lat + ', ' + lng;
        
        // Mettre à jour le champ lieu
        document.getElementById('lieu').value = coords;
        
        // Retirer l'ancien marqueur s'il existe
        if (marker !== null) {
            map.removeLayer(marker);
        }
        
        // Ajouter un nouveau marqueur
        marker = L.marker(e.latlng).addTo(map);
        marker.bindPopup('<strong>Lieu sélectionné</strong><br>' + coords).openPopup();
        
        // Effacer l'erreur du champ lieu si elle existe
        if (window.clearError) {
            window.clearError('lieu');
        }
    });
    
    console.log('✅ Carte initialisée avec succès');
}

/**
 * Définir un lieu depuis les boutons de villes
 */
function setLocation(cityName, lat, lng) {
    // Mettre à jour le champ
    document.getElementById('lieu').value = cityName;
    
    // Centrer la carte sur la ville
    if (map !== null) {
        map.setView([lat, lng], 12);
        
        // Retirer l'ancien marqueur
        if (marker !== null) {
            map.removeLayer(marker);
        }
        
        // Ajouter un marqueur sur la ville
        marker = L.marker([lat, lng]).addTo(map);
        marker.bindPopup('<strong>' + cityName + '</strong>').openPopup();
    }
    
    // Effacer l'erreur
    if (window.clearError) {
        window.clearError('lieu');
    }
}

/**
 * Fermer la popup en cliquant à l'extérieur
 */
document.addEventListener('click', function(e) {
    // Vérifier si le clic est en dehors du picker-container
    if (!e.target.closest('.picker-container')) {
        closeMap();
    }
});

console.log('🗺️ Module de carte chargé avec succès');