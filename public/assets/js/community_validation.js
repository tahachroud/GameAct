document.addEventListener("DOMContentLoaded", () => {

    // Shake animation
    function shake(element) {
        element.style.animation = "shake 0,3s";
        setTimeout(() => element.style.animation = "", 300);
    }

    function showError(form, message) {
        let errorBox = form.querySelector(".error-box");

        if (!errorBox) {
            errorBox = document.createElement("div");
            errorBox.className = "error-box";
            form.appendChild(errorBox);
        }

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
        if (!contentField) return true;

        let content = contentField.value.trim();

        // ❗ NOT EMPTY
        if (content.length === 0) {
            showError(form, "⚠ Le contenu ne peut pas être vide.");
            shake(contentField);
            return false;
        }

        // ❗ MIN LENGTH
        if (content.length < 3) {
            showError(form, "⚠ Le contenu doit contenir au moins 3 caractères.");
            shake(contentField);
            return false;
        }

        // ❗ MAX LENGTH
        if (content.length > 500) {
            showError(form, "⚠ Le contenu ne doit pas dépasser 500 caractères.");
            shake(contentField);
            return false;
        }

        return true;
    }

    /* -----------------------------
       VALIDATION FOR POST FORM
    ----------------------------- */
    const composerForm = document.querySelector("#composerForm");
    if (composerForm) {
        composerForm.addEventListener("submit", function (e) {
            if (!validate(composerForm)) {
                e.preventDefault();
            }
        });
    }

    /* -----------------------------
       VALIDATION FOR COMMENT FORMS
    ----------------------------- */
    document.querySelectorAll(".comment-form").forEach(form => {
        form.addEventListener("submit", function (e) {
            if (!validate(form)) {
                e.preventDefault();
            }
        });
    });
    // Show dropdown when clicking search bar
document.getElementById("mainSearch").addEventListener("focus", function() {
    document.getElementById("searchDropdown").style.display = "block";
});

// ===============================
// SEARCH DROPDOWN CONTROLLER
// ===============================

// Show dropdown when clicking search bar
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("mainSearch");
    const dropdown = document.getElementById("searchDropdown");

    if (!input || !dropdown) return; // Safety check

    input.addEventListener("focus", function () {
        dropdown.style.display = "block";
    });

    // Hide dropdown when clicking outside
    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.style.display = "none";
        }
    });
});

// Apply quick filter
function applyQuickFilter(filter) {
    window.location = "index.php?action=search&" + filter;
}



});
