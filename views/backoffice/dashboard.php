<?php
session_start();
require_once '../../controllers/userController.php';

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

$userController = new userController();
$users = $userController->listUsers(); 

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $userController->deleteUser($id);
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - GameAct</title>
    <link rel="stylesheet" href="../frontoffice/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
        <style>
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin: 20px 0;
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }
        .success { background: rgba(76, 175, 80, 0.2); color: #4caf50; border: 2px solid #4caf50; }
        .error   { background: rgba(255, 77, 77, 0.2); color: #ff6b6b; border: 2px solid #ff4d4d; }
    </style>

</head>
<body>

<header class="header-area header-sticky">
    <nav class="main-nav">
        <a href="../../index.html" class="logo">
            <img src="../frontoffice/assets/images/logo.png" alt="GameAct">
        </a>
        <ul class="nav">
            <li><a href="dashboard.php" class="active">Admin Dashboard</a></li>
            <li><a href="add_user.php">Add User</a></li>
            <li><a href="../frontoffice/profile.php">Back to Site</a></li>
            <li><a href="../frontoffice/login_client.php?logout=1" style="color:#ff4d4d;">Logout</a></li>
        </ul>
    </nav>
</header>



<div class="dashboard-container">
    <div class="dashboard-card">

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success">
                <?= $_SESSION['success'] ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error">
                <?= $_SESSION['error'] ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <h1>Users Management</h1>

        <?php if (empty($users)): ?>
            <p class="no-users">No users found in the database.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>CIN</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name'] . " " . $user['lastname']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['cin']) ?></td>
                        <td>
                            <strong style="color: <?= $user['role'] === 'admin' ? '#e75e8d' : '#4caf50' ?>">
                                <?= ucfirst($user['role']) ?>
                            </strong>
                        </td>
                        <td>
                            <a href="update_user.php?id=<?= $user['id'] ?>" class="btn btn-update">Update</a>
                            <?php if ($user['is_superadmin']): ?>
                                <span style="color:#e75e8d; font-weight:bold;">SUPER ADMIN</span>
                            <?php else: ?>
                                <a href="?delete=<?= $user['id'] ?>" 
                                class="btn btn-delete"
                                onclick="return confirm('Are you sure you want to delete <?= addslashes($user['name']) ?>?')">
                                Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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