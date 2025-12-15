<?php
session_start();
require_once '../../controller/userController.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../frontoffice/login_client.php");
    exit;
}

$userController = new userController();
$users = $userController->listUsers();

// Sorting logic with toggle direction
$sort = $_GET['sort'] ?? 'role';     // 'role' or 'age'
$order = $_GET['order'] ?? 'asc';    // 'asc' or 'desc'

// Toggle order when clicking the same column
if (isset($_GET['sort']) && $_GET['sort'] === $sort) {
    $order = ($order === 'asc') ? 'desc' : 'asc';
}

if ($sort === 'age') {
    usort($users, function($a, $b) use ($order) {
        return ($order === 'asc') ? ($a['age'] <=> $b['age']) : ($b['age'] <=> $a['age']);
    });
} else {
    // Default: superadmin > admin > client + alphabetical name
    $roleOrder = ['superadmin' => 0, 'admin' => 1, 'client' => 2];
    usort($users, function($a, $b) use ($roleOrder, $order) {
        $roleA = $roleOrder[$a['role']] ?? 3;
        $roleB = $roleOrder[$b['role']] ?? 3;
        
        if ($roleA === $roleB) {
            $nameA = $a['name'] . ' ' . $a['lastname'];
            $nameB = $b['name'] . ' ' . $b['lastname'];
            return ($order === 'asc') ? strcasecmp($nameA, $nameB) : strcasecmp($nameB, $nameA);
        }
        return ($order === 'asc') ? ($roleA <=> $roleB) : ($roleB <=> $roleA);
    });
}

// Delete user
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($id !== $_SESSION['user_id']) {
        $userController->deleteUser($id);
        $_SESSION['success'] = "User deleted successfully!";
    } else {
        $_SESSION['error'] = "You cannot delete yourself!";
    }
    header("Location: dashboard.php?sort=$sort&order=$order");
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
        .sort-buttons { text-align: center; margin: 20px 0; }
        .sort-btn { 
            padding: 12px 24px; margin: 0 10px; border-radius: 50px; 
            background: #333; color: white; text-decoration: none; font-weight: bold;
            transition: 0.3s; display: inline-block;
        }
        .sort-btn.active { background: #e75e8d; }
        .sort-btn:hover { background: #e75e8d; }
        .sort-btn::after {
            content: ' ↑↓';
            font-size: 12px;
            opacity: 0.7;
        }
        .alert { padding: 15px; border-radius: 12px; margin: 20px 0; text-align: center; font-weight: bold; }
        .success { background: rgba(76,175,80,0.2); color: #4caf50; border: 2px solid #4caf50; }
        .error { background: rgba(255,77,77,0.2); color: #ff6b6b; border: 2px solid #ff4d4d; }
    </style>
</head>
<body>

<header class="header-area header-sticky">
    <nav class="main-nav">
        <a href="../../index.php" class="logo">
            <img src="../frontoffice/assets/images/logo.png" alt="GameAct">
        </a>
        <ul class="nav">
            <li><a href="dashboard.php" class="active">Users List</a></li>
            <li><a href="add_user.php">Add Admin</a></li>
            <li><a href="../frontoffice/profile.php">Back to Site</a></li>
            <li><a href="../frontoffice/login_client.php?logout=1" style="color:#ff4d4d;">Logout</a></li>
        </ul>
    </nav>
</header>

<div class="dashboard-container">
    <div class="dashboard-card">
        <h1>Users Management</h1>

        <!-- Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php endif; ?>

        <!-- Sort Buttons -->
        <div class="sort-buttons">
            <a href="?sort=role&order=asc" class="sort-btn <?= $sort === 'role' ? 'active' : '' ?>">
                By Role & Name
            </a>
            <a href="?sort=age&order=<?= ($sort === 'age' && $order === 'asc') ? 'desc' : 'asc' ?>" 
               class="sort-btn <?= $sort === 'age' ? 'active' : '' ?>">
                By Age <?= ($sort === 'age' && $order === 'asc') ? '↑' : '↓' ?>
            </a>
        </div>

        <?php if (empty($users)): ?>
            <p style="text-align:center; color:#aaa; padding:40px;">No users found.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>CIN</th>
                        <th>Age</th>
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
                        <td><?= $user['age'] ?></td>
                        <td>
                            <strong style="color: <?= 
                                $user['role'] === 'superadmin' ? '#ff00ff' : 
                                ($user['role'] === 'admin' ? '#e75e8d' : '#4caf50') 
                            ?>">
                                <?= ucfirst($user['role']) ?>
                            </strong>
                        </td>
                        <td>
                            <a href="update_user.php?id=<?= $user['id'] ?>" class="btn btn-update">Update</a>
                            <a href="?delete=<?= $user['id'] ?>&sort=<?= $sort ?>&order=<?= $order ?>" 
                               class="btn btn-delete"
                               onclick="return confirm('Delete <?= addslashes($user['name'].' '.$user['lastname']) ?> forever?')">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
</html>