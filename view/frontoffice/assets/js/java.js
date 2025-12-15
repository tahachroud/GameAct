function showError(input, message) {
    if (!input) return;
    input.classList.remove("success");
    input.classList.add("error");

    const errorBox = input.nextElementSibling;
    if (errorBox) {
        errorBox.textContent = message;
        errorBox.classList.add("show");
    }
}

function showSuccess(input) {
    if (!input) return;
    input.classList.remove("error");
    input.classList.add("success");

    const errorBox = input.nextElementSibling;
    if (errorBox) {
        errorBox.textContent = "";
        errorBox.classList.remove("show");
    }
}

function clearErrors() {
    const inputs = document.querySelectorAll(".form-group input, .form-group select");
    inputs.forEach((el) => {
        el.classList.remove("error", "success");
    });

    const errorMessages = document.querySelectorAll(".error-message");
    errorMessages.forEach((msg) => {
        msg.textContent = "";
        msg.classList.remove("show");
    });

    const serverBox = document.querySelector(".server-error-box");
    if (serverBox && serverBox.parentNode) {
        serverBox.parentNode.removeChild(serverBox);
    }
}

function saisie() {
    clearErrors();

    const nameInput     = document.getElementById("name");
    const lastnameInput = document.getElementById("lastname");
    const emailInput    = document.getElementById("email");
    const pass1Input    = document.getElementById("password");
    const pass2Input    = document.getElementById("password2");
    const cinInput      = document.getElementById("cin");
    const locationInput = document.getElementById("location");
    const dobInput      = document.getElementById("dob");
    const genderSelect  = document.getElementById("gender");

    const name      = nameInput.value.trim();
    const lastname  = lastnameInput.value.trim();
    const email     = emailInput.value.trim();
    const pass1     = pass1Input.value;
    const pass2     = pass2Input.value;
    const cin       = cinInput.value.trim();
    const location  = locationInput.value.trim();
    const dob       = dobInput.value;      
    const gender    = genderSelect.value;

    const fullNamePattern = /^[A-ZÀ-Ö][a-zà-öø-ÿ]*(\s[A-ZÀ-Ö][a-zà-öø-ÿ]*)*$/u;

    if (!fullNamePattern.test(name)) {
        showError(nameInput, "First name must start with a capital letter and contain only letters.");
        return false;
    } else {
        showSuccess(nameInput);
    }

    if (!fullNamePattern.test(lastname)) {
        showError(lastnameInput, "Last name must start with a capital letter and contain only letters.");
        return false;
    } else {
        showSuccess(lastnameInput);
    }

    const emailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailFormat.test(email)) {
        showError(emailInput, "Email format is invalid.");
        return false;
    } else {
        showSuccess(emailInput);
    }

    if (pass1.length < 8) {
        showError(pass1Input, "Password must be at least 8 characters long.");
        return false;
    } else {
        showSuccess(pass1Input);
    }

    const trimmedPass1 = pass1.trim();
    const trimmedPass2 = pass2.trim();

    if (trimmedPass1 === '' || trimmedPass2 === '') {
        showError(pass2Input, "Please fill in both password fields.");
        return false;
    }

    if (trimmedPass1 !== trimmedPass2) {
        showError(pass2Input, "Passwords do not match.");
        return false;
    } else {
        showSuccess(pass2Input);
    }

    const cinPattern = /^\d{8}$/;
    if (!cinPattern.test(cin)) {
        showError(cinInput, "CIN must contain exactly 8 digits.");
        return false;
    } else {
        showSuccess(cinInput);
    }

    if (!dob) {
        showError(dobInput, "Date of birth is required.");
        return false;
    } else {
        const dobDate = new Date(dob);      
        if (isNaN(dobDate.getTime())) {
            showError(dobInput, "Date of birth is invalid.");
            return false;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (dobDate >= today) {
            showError(dobInput, "Date of birth must be in the past.");
            return false;
        }

        let age = today.getFullYear() - dobDate.getFullYear();
        const m = today.getMonth() - dobDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dobDate.getDate())) {
            age--;
        }

        if (age <= 0 || age > 120) {
            showError(dobInput, "Please enter a valid date of birth.");
            return false;
        }

        showSuccess(dobInput);
    }

    if (location.length === 0) {
        showError(locationInput, "Location is required.");
        return false;
    } else {
        showSuccess(locationInput);
    }

    if (gender === "Select gender") {
        showError(genderSelect, "Please select a gender.");
        return false;
    } else {
        showSuccess(genderSelect);
    }

    const jsValidated = document.getElementById("js_validated");
    if (jsValidated) {
        jsValidated.value = "1";
    }

    return true;
}

document.getElementById('password')?.addEventListener('input', function () {
    const password = this.value;
    const strengthBar = document.querySelector('.strength-bar');
    const hint = document.querySelector('.password-hint');

    if (!strengthBar || !hint) return;

    strengthBar.classList.remove('strength-weak', 'strength-medium', 'strength-strong');

    if (password.length === 0) {
        strengthBar.style.width = '0';
        hint.textContent = 'Use 9+ characters with letters, numbers & symbols';
        hint.style.color = '#888';
        return;
    }

    const hasUpper = /[A-Z]/.test(password);
    const hasDigit = /\d/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);

    if (password.length >= 9 && hasUpper && hasDigit && hasSpecial) {
        strengthBar.classList.add('strength-strong');
        hint.textContent = "Strong password!";
        hint.style.color = '#4caf50';
    }
    else if (password.length >= 7 && hasUpper && hasDigit) {
        strengthBar.classList.add('strength-medium');
        hint.textContent = "Medium: Add special characters (!@#$%) to make it strong";
        hint.style.color = '#ffb84d';
    }
    else if (password.length <= 6 && !hasUpper && !hasDigit) {
        strengthBar.classList.add('strength-weak');
        hint.textContent = "Too weak: Use more characters, add capital letters and numbers";
        hint.style.color = '#ff6b6b';
    }
    else {
        strengthBar.classList.add('strength-weak');
        hint.textContent = "Weak: Needs capital letters, numbers and special characters";
        hint.style.color = '#ff6b6b';
    }
});