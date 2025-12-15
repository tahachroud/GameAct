<?php
session_start();
require_once '../../vendor/autoload.php';  // ← MUST HAVE THIS
require_once '../../controller/userController.php';
use PragmaRX\Google2FA\Google2FA;
use Bacon\BaconQrCode\Renderer\ImageRenderer;
use Bacon\BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use Bacon\BaconQrCode\Renderer\RendererStyle\RendererStyle;
use Bacon\BaconQrCode\Writer;

$google2fa = new Google2FA();
$userController = new userController();
$error = "";

if (!isset($_SESSION['temp_user_id'])) {
    header("Location: login_client.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'] ?? '';
    $user = $userController->getUserById($_SESSION['temp_user_id']);

    if ($google2fa->verifyKey($user['secret_2fa'], $code)) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        unset($_SESSION['temp_user_id']);
        unset($_SESSION['secret_2fa']);
        header("Location: profile.php");
        exit;
    } else {
        $error = "Invalid 2FA code!";
    }
}
?>

<!DOCTYPE html>
<html><head>
    <meta charset="UTF-8">
    <title>2FA Verification</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head><body>

<div class="login-container" style="margin-top:150px;">
    <div class="login-card">
        <?php if (isset($_SESSION['show_qr'])): 
            $google2fa = new Google2FA();
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                'GameAct',
                $userController->getUserById($_SESSION['temp_user_id'])['email'],
                $_SESSION['secret_2fa']
            );
        ?>
        <div style="text-align:center; margin:20px 0;">
            <p>Scan this QR code with Google Authenticator:</p>
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qrCodeUrl) ?>" alt="QR Code">
            <p style="margin-top:10px; background:#333; padding:10px; border-radius:8px; font-family:monospace;">
                <?= $_SESSION['secret_2fa'] ?>
            </p>
        </div>
        <?php unset($_SESSION['show_qr']); endif; ?>
        <h1 style="color:#e75e8d;text-align:center;">2FA Verification</h1>
        <p style="text-align:center;color:#aaa;">Open Google Authenticator and enter the code</p>

        <?php if($error): ?>
            <p style="color:red;text-align:center;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <input type="text" name="code" maxlength="6" required placeholder="Enter 6-digit code" style="text-align:center; font-size:24px; letter-spacing:8px;">
            </div>
            <button type="submit" class="button" style="width:100%; margin-top:20px;">
                Verify & Login
            </button>
        </form>
    </div>
</div>

</body></html>