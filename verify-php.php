<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Utilisez la fonction de vérification définie dans le fichier principal
    require_once 'your-main-php-file.php'; // Remplacez par le nom de votre fichier principal
    
    if (verifyAccount($token, $pdo)) {
        $_SESSION['verification_success'] = true;
        header("Location: login.php?verified=1");
        exit();
    } else {
        echo "Le lien de vérification est invalide ou a expiré.";
    }
} else {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vérification de compte</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div>
        <h1>Vérification de compte</h1>
        <?php if (isset($_SESSION['verification_success']) && $_SESSION['verification_success']): ?>
            <p>Votre compte a été vérifié avec succès. Vous pouvez maintenant vous connecter.</p>
            <a href="login.php">Se connecter</a>
        <?php else: ?>
            <p>Le lien de vérification est invalide ou a expiré. Veuillez réessayer ou demander un nouveau lien.</p>
            <a href="resend_verification.php">Demander un nouveau lien</a>
        <?php endif; ?>
    </div>
</body>
</html>
