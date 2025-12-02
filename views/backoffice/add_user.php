<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontoffice/login_client.php");
    exit;
}

require_once '../../controllers/userController.php';
$userController = new userController();
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name      = trim($_POST['name']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $cin       = trim($_POST['cin']);
    $gender    = $_POST['gender'];
    $location  = trim($_POST['location']);
    $age       = (int)$_POST['age'];

    if (empty($name) || empty($lastname) || empty($email) || empty($password) || empty($cin)) {
        $error = "All fields are required!";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters!";
    } elseif ($userController->getUserByEmail($email)) {
        $error = "Email already exists!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $pdo = config::getConnexion();
        try {
            $query = $pdo->prepare("INSERT INTO users (name, lastname, email, password, cin, gender, location, age, role) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'admin')");
            $query->execute([$name, $lastname, $email, $hashed, $cin, $gender, $location, $age]);

            $_SESSION['success'] = "Admin <strong>$name $lastname</strong> created successfully!";
            header("Location: dashboard.php");
            exit;

        } catch (Exception $e) {
            $error = "Database error. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin - GameAct</title>
    <link rel="stylesheet" href="../frontoffice/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .add-container { margin: 120px auto; max-width: 600px; }
        .add-card { background: #2a2c2d; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(231,94,141,0.2); border: 2px solid #e75e8d; }
        h1 { color: #e75e8d; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #aaa; margin-bottom: 30px; }
        .alert { padding: 15px; border-radius: 12px; margin: 20px 0; text-align: center; font-weight: bold; }
        .success { background: #1e3d1e; color: #4caf50; border: 2px solid #4caf50; }
        .error { background: #3d1e1e; color: #ff6b6b; border: 2px solid #ff4d4d; }
    </style>
</head>
<body>

<header class="header-area header-sticky">
    <nav class="main-nav">
        <a href="../../index.html" class="logo">
            <img src="../frontoffice/assets/images/logo.png" alt="GameAct">
        </a>
        <ul class="nav">
            <li><a href="dashboard.php">Dashboard</a></li>
        </ul>
    </nav>
</header>

<div class="add-container">
    <div class="add-card">
        <h1>Add New Admin</h1>
        <p class="subtitle">Create a new administrator account</p>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" value="<?= $_POST['name'] ?? '' ?>" required placeholder="First name">
            </div>

            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="lastname" value="<?= $_POST['lastname'] ?? '' ?>" required placeholder="Last name">
            </div>

            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required placeholder="admin@gameact.com">
            </div>

            <div class="form-group">
                <label>Password <span class="required">*</span></label>
                <input type="password" name="password" id="password" required placeholder="Minimum 8 characters" autocomplete="new-password">
                <div class="password-strength" style="margin-top:12px;">
                    <div class="strength-bar"></div>
                    <div class="strength-text" style="text-align:center; margin-top:8px; color:#aaa;"></div>
                </div>
                <small class="password-hint">Use 8+ characters with letters, numbers & symbols</small>
            </div>

            <div class="form-group">
                <label>CIN <span class="required">*</span></label>
                <input type="text" name="cin" value="<?= $_POST['cin'] ?? '' ?>" required maxlength="8">
            </div>

            <div class="form-group">
                <label>Gender <span class="required">*</span></label>
                <select name="gender" required>
                    <option value="male" <?= ($_POST['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= ($_POST['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Location <span class="required">*</span></label>
                <input type="text" name="location" value="<?= $_POST['location'] ?? 'Ariana' ?>" required>
            </div>

            <div class="form-group">
                <label>Age <span class="required">*</span></label>
                <input type="number" name="age" value="<?= $_POST['age'] ?? '25' ?>" required min="18">
            </div>

            <button type="submit" class="button" style="width:100%; margin-top:20px;">
                Create Admin Account
            </button>
        </form>

        <div style="text-align:center; margin-top:30px;">
            <a href="dashboard.php" style="color:#ec6090; font-weight:600;">Back to Dashboard</a>
        </div>
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
  

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('password');
    const bar = document.querySelector('.strength-bar');
    const text = document.querySelector('.strength-text');

    input.addEventListener('input', function() {
        const val = input.value;
        let score = 0;
        if (val.length >= 8) score++;
        if (/[a-z]/.test(val) && /[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        bar.style.width = (score * 25) + '%';
        bar.style.height = '8px';
        bar.style.borderRadius = '4px';
        bar.style.transition = 'all 0.3s';

        if (score <= 1) { bar.style.background = '#ff4d4d'; text.textContent = 'Weak'; text.style.color = '#ff4d4d'; }
        else if (score <= 2) { bar.style.background = '#ffb84d'; text.textContent = 'Medium'; text.style.color = '#ffb84d'; }
        else if (score <= 3) { bar.style.background = '#a0e75e'; text.textContent = 'Strong'; text.style.color = '#a0e75e'; }
        else { bar.style.background = '#4caf50'; text.textContent = 'Very Strong'; text.style.color = '#4caf50'; }
    });
});
</script>

</body>
</html>