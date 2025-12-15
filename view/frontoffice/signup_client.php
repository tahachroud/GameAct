<?php
session_start();

require_once __DIR__ . '/../../controller/userController.php';
require_once __DIR__ . '/../../model/User.php';

$userController = new userController();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $js_validated = $_POST['js_validated'] ?? '0';
    $js_ok = ($js_validated === '1');

    $name      = $_POST['name']      ?? '';
    $lastname  = $_POST['lastname']  ?? '';
    $email     = $_POST['email']     ?? '';
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';
    $cin       = $_POST['cin']       ?? '';
    $gender    = $_POST['gender']    ?? '';
    $location  = $_POST['location']  ?? '';
    $dob       = $_POST['dob']       ?? '';
    $role      = 'client';

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $name      = trim($name);
    $lastname  = trim($lastname);
    $email     = trim($email);
    $cin       = trim($cin);
    $location  = trim($location);
    $dob       = trim($dob);
    $age = null; 

    $fullNamePattern = '/^[A-ZÀ-Ö][a-zà-öø-ÿ]*(\s[A-ZÀ-Ö][a-zà-öø-ÿ]*)*$/u';
    $emailPattern    = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    $cinPattern      = '/^\d{8}$/';
    $agePattern      = '/^\d+$/';


    if ($name === '' || !preg_match($fullNamePattern, $name)) {
        if (!$js_ok) {
            $errors['name'] = 'First name must start with a capital letter and contain only letters.';
        }
    }

    if ($lastname === '' || !preg_match($fullNamePattern, $lastname)) {
        if (!$js_ok) {
            $errors['lastname'] = 'Last name must start with a capital letter and contain only letters.';
        }
    }

    if (!preg_match($emailPattern, $email)) {
        if (!$js_ok) {
            $errors['email'] = 'Email format is invalid.';
        }
    }

    if (strlen($password) < 8) {
        if (!$js_ok) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        }
    }

    if ($password !== $password2) {
        if (!$js_ok) {
            $errors['password2'] = 'Passwords do not match.';
        }
    }

    if (!preg_match($cinPattern, $cin)) {
        if (!$js_ok) {
            $errors['cin'] = 'CIN must contain exactly 8 digits.';
        }
    }

    if ($dob === '') {
        if (!$js_ok) {
            $errors['dob'] = 'Date of birth is required.';
        }
    } else {
        $dobDateTime = DateTime::createFromFormat('Y-m-d', $dob);

        if (!$dobDateTime || $dobDateTime->format('Y-m-d') !== $dob) {
            if (!$js_ok) {
                $errors['dob'] = 'Date of birth is invalid.';
            }
        } else {
            $today = new DateTime();
            if ($dobDateTime >= $today) {
                if (!$js_ok) {
                    $errors['dob'] = 'Date of birth must be in the past.';
                }
            } else {
                $ageInterval = $today->diff($dobDateTime);
                $age = $ageInterval->y;

                if ($age <= 0 || $age > 120) {
                    if (!$js_ok) {
                        $errors['dob'] = 'Please enter a valid date of birth.';
                    }
                }
            }
        }
    }

    if ($location === '') {
        if (!$js_ok) {
            $errors['location'] = 'Location is required.';
        }
    }

    if (!in_array($gender, ['male', 'female'], true)) {
        if (!$js_ok) {
            $errors['gender'] = 'Please select a gender.';
        }
    }

    if (empty($errors)) {

        $existingEmail = $userController->getUserByEmail($email);
        if ($existingEmail) {
            $errors['email'] = 'This email is already in use. Please choose another one.';
        } else {
            $existingCin = $userController->getUserByCin($cin);
            if ($existingCin) {
                $errors['cin'] = 'This CIN is already in use. Please choose another one.';
            } else {

                $user = new User(
                    null,
                    $name,
                    $lastname,
                    $email,
                    $hashed_password,
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
                        $errors['global'] = 'Signup succeeded but user could not be loaded.';
                    }

                } catch (Exception $e) {
                    $errors['global'] = 'Error during signup: ' . $e->getMessage();
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
    <link rel="stylesheet" href="../../public/assets/css/moving-bg.css">
</head>
<body>
    <div class="moving-bg"></div>


    <header class="header-area header-sticky">
        <nav class="main-nav">
            <a href="../../index.php" class="logo">
                <img src="assets/images/logo.png" alt="">
            </a>
            <div class="search-input">
                <form id="search" action="#">
                    <i class="fa fa-search"></i>
                    <input type="text" placeholder="Rechercher" id='searchText' name="searchKeyword" onkeypress="handle" />
                </form>
            </div>
            <ul class="nav">
                <li><a href="../../index.php" class="active">Home</a></li>
                <li><a href="../front-office/events/front/index.php">Evénements</a></li>
                <li><a href="../../shop-home.php">Boutique</a></li>
                <li><a href="../../index-community.php?action=community">Communauté</a></li>
                <li><a href="../../module-card module-tutorials">Tutoriels</a></li>
                <li><a href="../../quiz_list.php">Quiz</a></li>
                <li><a href="profile.php">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
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
            
            <form method="POST" onsubmit="return saisie();">
                <input type="hidden" id="js_validated" name="js_validated" value="0">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">
                            First name <span class="required">*</span>
                        </label>
                        <input type="text" id="name" name="name" placeholder="Name" required>
                        <p class="error-message"></p>
                    </div>

                    <div class="form-group">
                        <label for="lastname">
                            Last name <span class="required">*</span>
                        </label>
                        <input type="text" id="lastname" name="lastname" placeholder="Lastname" required>
                        <p class="error-message"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">
                        Email address <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                    <p class="error-message"></p>
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required oninput="saisie()">
                    
                    <div class="error-message"></div>

                    <div class="password-strength">
                        <div class="strength-bar"></div>
                    </div>

                    <small class="password-hint">Use 9+ characters with letters, numbers & symbols</small>
                </div>

                <div class="form-group">
                    <label for="password2">
                        Retype Password <span class="required">*</span>
                    </label>
                    <input type="password" id="password2" name="password2" placeholder="Confirm Password" required>
                    <p class="error-message"></p>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="cin">
                            CIN <span class="required">*</span>
                        </label>
                        <input type="text" id="cin" name="cin" placeholder="CIN" required>
                        <p class="error-message"></p>
                    </div>

                    <div class="form-group">
                        <label for="location">
                            Location <span class="required">*</span>
                        </label>
                        <input type="text" id="location" name="location" placeholder="Your Location" required>
                        <p class="error-message"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="dob">Date of birth <span class="required">*</span></label>
                    <input type="date" id="dob" name="dob" required class="dob-input">
                    <div class="error-message"></div>
                </div>

                <div class="form-group">
                    <label for="gender">
                        Gender <span class="required">*</span>
                    </label>
                    <select name="gender" id="gender" style="width:100%; padding:12px; margin-bottom:10px;">
                        <option disabled selected>Select gender</option>
                        <option>male</option>
                        <option>female</option>
                    </select>
                    <p class="error-message"></p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="server-error-box">
                        <?php foreach ($errors as $msg): ?>
                            <div><?= htmlspecialchars($msg) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

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

    <script src="assets/js/java.js"></script>
</body>
</html>