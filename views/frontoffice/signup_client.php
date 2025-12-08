<?php
session_start();

require_once __DIR__ . '/../../controllers/userController.php';
require_once __DIR__ . '/../../model/User.php';

$userController = new userController();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name      = $_POST['name']      ?? '';
    $lastname  = $_POST['lastname']  ?? '';
    $email     = $_POST['email']     ?? '';
    $password  = $_POST['password']  ?? '';
    $password2 = $_POST['password2'] ?? '';
    $cin       = $_POST['cin']       ?? '';
    $gender    = $_POST['gender']    ?? '';
    $location  = $_POST['location']  ?? '';
    $age       = $_POST['age']       ?? '';
    $role      = 'client';


    $name     = trim($name);
    $lastname = trim($lastname);
    $email    = trim($email);
    $cin      = trim($cin);
    $location = trim($location);
    $age      = trim($age);

    $namePattern = '/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/u';

    if ($name === '' || !preg_match($namePattern, $name) || !preg_match('/^[A-ZÀ-Ö]/u', $name)) {
        $error = 'First name must start with a capital letter and contain only letters.';
    }
    elseif ($lastname === '' || !preg_match($namePattern, $lastname) || !preg_match('/^[A-ZÀ-Ö]/u', $lastname)) {
        $error = 'Last name must start with a capital letter and contain only letters.';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email format is invalid.';
    }
    elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters long.';
    }
    elseif ($password !== $password2) {
        $error = 'Passwords do not match.';
    }
    elseif (!preg_match('/^\d{8}$/', $cin)) {
        $error = 'CIN must contain exactly 8 digits.';
    }
    elseif (!ctype_digit($age) || (int)$age <= 0) {
        $error = 'Age must be a positive number.';
    }
    elseif ($location === '') {
        $error = 'Location is required.';
    }
    elseif (!in_array($gender, ['male', 'female'], true)) {
        $error = 'Please select a gender.';
    }

    if ($error === '') {

        $existingEmail = $userController->getUserByEmail($email);

        if ($existingEmail) {
            $error = 'This email is already in use. Please choose another one.';
        } else {
            $existingCin = $userController->getUserByCin($cin);

            if ($existingCin) {
                $error = 'This CIN is already in use. Please choose another one.';
            } else {
                $user = new User(
                    null,
                    $name,
                    $lastname,
                    $email,
                    $password,
                    $cin,
                    $gender,
                    $location,
                    $age,
                    $role
                );

                try {
                    $userController->addUser($user);
                    $insertedUser = $userController->getUserByEmail($email);

                    if ($insertedUser) {
                        $_SESSION['user_id'] = $insertedUser['id'];
                        header('Location: profile.php');
                        exit;
                    } else {
                        $error = 'Signup succeeded but user could not be loaded.';
                    }

                } catch (Exception $e) {
                    $error = 'Error during signup: ' . $e->getMessage();
                }
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <title>signup page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header class="header-area header-sticky">
        <nav class="main-nav">
            <a href="index.html" class="logo">
                <img src="assets/images/logo.png" alt="">
            </a>
            <div class="search-input">
                <form id="search" action="#">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Rechercher" id='searchText' name="searchKeyword" onkeypress="handle" />
                </form>
            </div>
            <ul class="nav">
                <li><a href="index.html" class="active">Home</a></li>
                <li><a href="browse.html">Evénements</a></li>
                <li><a href="details.html">Boutique</a></li>
                <li><a href="streams.html">Communauté</a></li>
                <li><a href="streams.html">Tutoriels</a></li>
                <li><a href="streams.html">Leaderboard</a></li>
                <li><a href="index.html">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>

    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1 class="register-title">SignUp</h1>
                <p class="sign-in-link">
                    Have an account? <a href="login_client.php">Sign in</a>
                </p>
            </div>

            <?php if (!empty($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            
            <p id="errorBox" style="color:red;"></p>

            <form method="POST" onsubmit="return saisie();">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            First name <span class="required">*</span>
                        </label>
                        <input type='text' id="name" name="name" placeholder="Name" required>
                    </div>

                    <div class="form-group">
                        <label for="lastname">
                            Last name <span class="required">*</span>
                        </label>
                        <input type="text" id="lastname" name="lastname" placeholder="Lastname" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">
                        Email address <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        Password <span class="required">*</span>
                    </label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>

                <div class="form-group">
                    <label for="password">
                        Retype Password <span class="required">*</span>
                    </label>
                    <input type="password" id="password2" name="password2" placeholder="Confirm Password" required>
                </div>

                <div class="form-group">
                    <label for="cin">
                        CIN <span class="required">*</span>
                    </label>
                    <input type='text'id="cin" name="cin" placeholder="CIN" required>
                </div>

                <div class="form-group">
                    <label for="age">
                        Age <span class="required">*</span>
                    </label>
                    <input type='text'id="age" name="age" placeholder="Your Age" required>
                </div>

                <div class="form-group">
                    <label for="age">
                        Location <span class="required">*</span>
                    </label>
                    <input type='text' id="location" name="location" placeholder="Your Location" required>
                </div>

                <select name="gender" id="gender" style="width:100%; padding:12px; margin-bottom:20px;">
                    <option disabled selected>Select gender</option>
                    <option>male</option>
                    <option>female</option>
                </select>

                <button type="submit" class="button">Register</button>
            </form>

        </div>
    </div>

  <footer>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <p>Copyright © 2036 <a href="#">GameAct</a> Company. All rights reserved. 
          
          <br>Design: <a href="#">Taha Chroud</a>  Distributed By <a href="#">APEX   </a></p>
        </div>
      </div>
    </div>
  </footer>

    <script src="java.js"></script>
</body>
</html>