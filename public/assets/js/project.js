// ============================================================
//  FICHIER : public/assets/js/projet.js
//  VALIDATION OPTIMISÉE + ANIMATIONS GAMEACT
// ============================================================

document.addEventListener("DOMContentLoaded", () => {

    // Animation Shake
    function shake(element) {
        element.style.animation = "shake 0.3s";
        setTimeout(() => element.style.animation = "", 300);
    }

    function showError(form, message) {
        let errorBox = form.querySelector(".error-box");

        if (!errorBox) {
            errorBox = document.createElement("div");
            errorBox.className = "error-box";
            form.prepend(errorBox);
        }

        // Style animé
        errorBox.innerHTML = message;
        errorBox.classList.add("visible");
        shake(errorBox);
    }

    function clearError(form) {
        let errorBox = form.querySelector(".error-box");
        if (errorBox) {
            errorBox.classList.remove("visible");
            errorBox.innerHTML = "";
        }
    }

    function validate(form) {
        clearError(form);

        let contentField = form.querySelector("textarea[name='content']");

        if (contentField) {
            let content = contentField.value.trim();

            if (content.length === 0) {
                showError(form, "⚠ Le contenu ne peut pas être vide.");
                contentField.classList.add("input-error");
                shake(contentField);
                return false;
            }

            if (content.length < 3) {
                showError(form, "⚠ Le contenu doit contenir au moins 3 caractères.");
                contentField.classList.add("input-error");
                shake(contentField);
                return false;
            }

            if (content.length > 500) {
                showError(form, "⚠ Le contenu ne doit pas dépasser 500 caractères.");
                contentField.classList.add("input-error");
                shake(contentField);
                return false;
            }

            // Si valide → on reset l'erreur visuelle
            contentField.classList.remove("input-error");
        }

        return true;
    }

    // Appliquer validation à tous les formulaires
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", event => {
            if (!validate(form)) {
                event.preventDefault();
            }
        });
    });
});
