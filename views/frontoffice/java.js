function saisie() {
    let name = document.getElementById("name").value.trim();
    let lastname = document.getElementById("lastname").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass1 = document.getElementById("password").value;
    let pass2 = document.getElementById("password2").value;
    let cin = document.getElementById("cin").value.trim();
    let age = document.getElementById("age").value.trim();
    let location = document.getElementById("location").value.trim();
    let gender = document.getElementById("gender").value;

    let errorBox = document.getElementById("errorBox");
    errorBox.innerHTML = "";
    errorBox.style.color = "red";

    let nameFormat = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;

    if (name.length === 0 || name[0] !== name[0].toUpperCase() || !nameFormat.test(name)) {
        errorBox.innerHTML = "Name must start with a capital letter and only contain letters.";
        return false; 
    }

    if (lastname.length === 0 || lastname[0] !== lastname[0].toUpperCase() || !nameFormat.test(lastname)) {
        errorBox.innerHTML = "Lastname must start with a capital letter and only contain letters.";
        return false; 
    }

    let emailFormat = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailFormat.test(email)) {
        errorBox.innerHTML = "Email format is invalid.";
        return false; 
    }
    
    if (pass1.length < 8) {
        errorBox.innerHTML = "Password must be at least 8 characters long.";
        return false; 
    }

    if (pass1 !== pass2) {
        errorBox.innerHTML = "Passwords do not match.";
        return false; 
    }

    if (cin.length !== 8 || isNaN(cin)) {
        errorBox.innerHTML = "CIN must contain exactly 8 numbers.";
        return false; 
    }

    if (age.length < 1 || isNaN(age)) {
        errorBox.innerHTML = "Age must be a number.";
        return false; 
    }

    if (location.length === 0) {
        errorBox.innerHTML = "Fill the location field.";
        return false; 
    }

    if (gender === "Select gender") {
        errorBox.innerHTML = "Please select a gender.";
        return false;
    }

    return true; 
}

function submit() {
    let role = document.querySelector("input[name='role']:checked");
    let errorBox = document.getElementById("errorBox");

    if (!role) {
        errorBox.innerHTML = "Please select a role.";
        return;
    }

    errorBox.innerHTML = "";

    if (role.value === "admin") {
        window.location.href = "admin.html";
    } else {
        window.location.href = "form.html";
    }
}

