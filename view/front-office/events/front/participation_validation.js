document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formParticipation');

    // Helper functions
    function showError(fieldId, message) {
        const errorElement = document.getElementById('error-' + fieldId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.color = '#e94560'; // Theme color
            errorElement.style.fontSize = '0.875rem';
            errorElement.style.marginTop = '5px';
            errorElement.style.display = 'block';
        }
    }

    function clearError(fieldId) {
        const errorElement = document.getElementById('error-' + fieldId);
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.style.display = 'none';
        }
    }

    // Validators
    function validatePseudo() {
        const field = document.getElementById('pseudo');
        const value = field.value.trim();
        if (value === '') {
            showError('pseudo', 'Le pseudo est obligatoire');
            return false;
        }
        if (value.length < 3) {
            showError('pseudo', 'Le pseudo doit contenir au moins 3 caractères');
            return false;
        }
        clearError('pseudo');
        return true;
    }

    function validateEmail() {
        const field = document.getElementById('email');
        const value = field.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (value === '') {
            showError('email', 'L\'email est obligatoire');
            return false;
        }
        if (!emailRegex.test(value)) {
            showError('email', 'Veuillez entrer une adresse email valide');
            return false;
        }
        clearError('email');
        return true;
    }

    function validateTelephone() {
        const field = document.getElementById('telephone');
        const value = field.value.trim();
        const phoneRegex = /^\d{8}$/; // Assuming 8 digits for Tunisia, adjust if needed

        if (value === '') {
            showError('telephone', 'Le téléphone est obligatoire');
            return false;
        }
        if (!phoneRegex.test(value)) {
            showError('telephone', 'Le numéro doit contenir 8 chiffres');
            return false;
        }
        clearError('telephone');
        return true;
    }

    function validateDiscord() {
        const field = document.getElementById('discord');
        const value = field.value.trim();
        // Basic Discord format check: User#1234 or just username (since migration)
        // Let's enforce at least 2 chars

        if (value === '') {
            showError('discord', 'L\'ID Discord est obligatoire');
            return false;
        }
        if (value.length < 2) {
            showError('discord', 'ID Discord invalide');
            return false;
        }
        clearError('discord');
        return true;
    }

    function validateAge() {
        const field = document.getElementById('age');
        const value = parseInt(field.value.trim(), 10);

        if (isNaN(value)) {
            showError('age', 'L\'âge est obligatoire');
            return false;
        }
        if (value < 12) {
            showError('age', 'Vous devez avoir au moins 12 ans');
            return false;
        }
        if (value > 100) {
            showError('age', 'Âge invalide');
            return false;
        }
        clearError('age');
        return true;
    }

    function validateConditions() {
        const field = document.getElementById('conditions');
        if (!field.checked) {
            showError('conditions', 'Vous devez accepter les conditions');
            return false;
        }
        clearError('conditions');
        return true;
    }

    function validateForm() {
        let isValid = true;
        isValid = validatePseudo() && isValid;
        isValid = validateEmail() && isValid;
        isValid = validateTelephone() && isValid;
        isValid = validateDiscord() && isValid;
        isValid = validateAge() && isValid;
        isValid = validateConditions() && isValid;
        return isValid;
    }

    // Event Listeners for Real-time Validation
    const inputs = [
        { id: 'pseudo', validator: validatePseudo },
        { id: 'email', validator: validateEmail },
        { id: 'telephone', validator: validateTelephone },
        { id: 'discord', validator: validateDiscord },
        { id: 'age', validator: validateAge }
    ];

    inputs.forEach(item => {
        const element = document.getElementById(item.id);
        if (element) {
            element.addEventListener('blur', item.validator);
            element.addEventListener('input', function () {
                // Only validate on input if an error is already shown
                const errorSpan = document.getElementById('error-' + item.id);
                if (errorSpan && errorSpan.textContent !== '') {
                    item.validator();
                }
            });
        }
    });

    const conditions = document.getElementById('conditions');
    if (conditions) {
        conditions.addEventListener('change', validateConditions);
    }

    // Form Submission
    if (form) {
        form.addEventListener('submit', function (e) {
            if (!validateForm()) {
                e.preventDefault();
            }
            // If valid, let the form submit naturally to the PHP backend
        });
    }
});
