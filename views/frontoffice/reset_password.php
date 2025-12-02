<?php
require_once '../../controllers/userController.php';
$userController = new userController();
$pdo = config::getConnexion();

$token = $_GET['token'] ?? '';
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'];
    $token = $_POST['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?")
            ->execute([$hashed, $user['id']]);

        $message = "<div style='text-align:center; padding:25px; background:#1e2d1e; border:2px solid #4caf50; border-radius:16px;'>
                        <h3 style='color:#4caf50; margin:0 0 10px;'>Password Changed!</h3>
                        <p style='color:#ccc; margin:10px 0;'>You can now log in with your new password.</p>
                        <a href='login_client.php' style='background:#4caf50; color:white; padding:14px 32px; text-decoration:none; border-radius:50px; font-weight:bold; display:inline-block;'>
                            Login Now
                        </a>
                    </div>";
    } else {
        $message = "<p style='color:#ff4d4d; text-align:center; background:#2d1e1e; padding:20px; border-radius:12px; border:2px solid #ff4d4d;'>
                        Invalid or expired link.
                    </p>";
    }
}

$stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expires > NOW()");
$stmt->execute([$token]);
$validToken = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GameAct</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header class="header-area header-sticky">
    <nav class="main-nav">
        <a href="../../index.html" class="logo">
            <img src="assets/images/logo.png" alt="GameAct">
        </a>
    </nav>
</header>

<div class="login-container" style="margin-top: 120px;">
    <div class="login-card">
        <h1 style="color:#e75e8d; text-align:center; margin-bottom:8px;">New Password</h1>
        <p style="text-align:center; color:#aaa; margin-bottom:30px;">Enter a strong new password</p>

        <?php echo $message; ?>

        <?php if (!$message && $validToken): ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <div class="form-group">
                <label>New Password <span class="required">*</span></label>
                <input type="password" name="password" id="password" placeholder="Enter new password" required>
                
                <!-- PASSWORD STRENGTH BAR - NOW WORKING -->
                <div class="password-strength">
                    <div class="strength-bar"></div>
                </div>
                <small class="password-hint">Use 9+ characters with letters, numbers & symbols</small>
            </div>

            <button type="submit" class="button" style="width:100%; margin-top:25px;">
                Change Password
            </button>
        </form>
        <?php endif; ?>

        <div style="text-align:center; margin-top:30px;">
            <a href="login_client.php" style="color:#ec6090; text-decoration:none; font-weight:600;">
                Back to Login
            </a>
        </div>
    </div>
</div>

<!-- THIS IS THE KEY - java.js with strength bar code -->
<script src="java.js"></script>

</body>
</html>