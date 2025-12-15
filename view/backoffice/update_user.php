<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontoffice/login_client.php");
    exit;
}

if (!($_SESSION['is_superadmin'] ?? false)) {
    if (isset($_GET['id']) && (int)$_GET['id'] === 25) {  
        $_SESSION['error'] = "You cannot modify or delete the Super Admin!";
        header("Location: dashboard.php");
        exit;
    }
    
    if (isset($_GET['delete']) && (int)$_GET['delete'] === 26) {
        $_SESSION['error'] = "You cannot delete the Super Admin!";
        header("Location: dashboard.php");
        exit;
    }
}

require_once '../../controller/userController.php';
$userController = new userController();

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = (int)$_GET['id'];
$user = $userController->getUserById($id);

if (!$user) {
    $_SESSION['error'] = "User not found!";
    header("Location: dashboard.php");
    exit;
}

$error = $success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $superAdminId = 25; 
    if ($id == $superAdminId) {
        $_SESSION['error'] = "You cannot modify the Super Admin account!";
        header("Location: dashboard.php");
        exit;
    }
    $name      = trim($_POST['name']);
    $lastname  = trim($_POST['lastname']);
    $email     = trim($_POST['email']);
    $cin       = trim($_POST['cin']);
    $gender    = $_POST['gender'];
    $location  = trim($_POST['location']);
    $age       = (int)$_POST['age'];
    $role      = $_POST['role'];

    if ($id == $_SESSION['user_id'] && $role !== 'admin') {
        $error = "You cannot remove your own admin rights!";
    } else {
        $pdo = config::getConnexion();
        try {
            $query = $pdo->prepare("UPDATE users SET 
                name = ?, lastname = ?, email = ?, cin = ?, gender = ?, location = ?, age = ?, role = ?
                WHERE id = ?");
            $query->execute([$name, $lastname, $email, $cin, $gender, $location, $age, $role, $id]);

            $_SESSION['success'] = "User updated successfully!";
            header("Location: dashboard.php");
            exit;
        } catch (Exception $e) {
            $error = "Update failed. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User - GameAct Admin</title>
    <link rel="stylesheet" href="../frontoffice/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .update-container { margin: 120px auto; max-width: 600px; }
        .update-card { background: #2a2c2d; padding: 40px; border-radius: 20px; box-shadow: 0 15px 40px rgba(231,94,141,0.2); border: 2px solid #e75e8d; }
        h1 { color: #e75e8d; text-align: center; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #aaa; margin-bottom: 30px; }
        .alert { padding: 15px; border-radius: 12px; margin: 20px 0; text-align: center; font-weight: bold; }
        .success { background: #1e3d1e; color: #4caf50; border: 2px solid #4caf50; }
        .error { background: #3d1e1e; color: #ff6b6b; border: 2px solid #ff4d4d; }
        .role-admin { color: #e75e8d; font-weight: bold; }
        .role-client { color: #4caf50; font-weight: bold; }
    </style>
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
                <li><a href="dashboard.php">Back to Dashboard</a></li>
                <li><a href="index.html">Profile <img src="assets/images/profile-header.jpg" alt=""></a></li>
            </ul>
        </nav>
    </header>

<div class="update-container">
    <div class="update-card">
        <h1>Update User</h1>
        <p class="subtitle">Editing: <strong><?= htmlspecialchars($user['name'] . " " . $user['lastname']) ?></strong></p>

        <?php if ($error): ?>
            <div class="alert error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Name <span class="required">*</span></label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Last Name <span class="required">*</span></label>
                <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" required>
            </div>

            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>CIN <span class="required">*</span></label>
                <input type="text" name="cin" value="<?= htmlspecialchars($user['cin']) ?>" required maxlength="8">
            </div>

            <div class="form-group">
                <label>Gender <span class="required">*</span></label>
                <select name="gender" required>
                    <option value="male" <?= $user['gender'] == 'male' ? 'selected' : '' ?>>Male</option>
                    <option value="female" <?= $user['gender'] == 'female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>

            <div class="form-group">
                <label>Location <span class="required">*</span></label>
                <input type="text" name="location" value="<?= htmlspecialchars($user['location']) ?>" required>
            </div>

            <div class="form-group">
                <label>Age <span class="required">*</span></label>
                <input type="number" name="age" value="<?= $user['age'] ?>" required min="10" max="100">
            </div>

            <div class="form-group">
                <label>Role <span class="required">*</span></label>
                <select name="role" required>
                    <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?> class="role-admin">Admin</option>
                    <option value="client" <?= $user['role'] == 'client' ? 'selected' : '' ?> class="role-client">Client</option>
                </select>
            </div>

            <button type="submit" class="button" >
                Save Changes
            </button>
        </form>

        <div style="text-align:center; margin-top:20px;">
            <a href="dashboard.php" style="color:#ec6090; font-weight:600;">Cancel & Back</a>
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
  

</body>
</html>