<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Rediriger si l'utilisateur n'est pas en attente de vérification
if (!isset($_SESSION['email_verification'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vérification en attente</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div>
        <h1>Vérifiez votre boîte de réception</h1>
        <p>Un email de vérification a été envoyé à votre adresse email. Veuillez cliquer sur le lien dans cet email pour activer votre compte.</p>
        <p>Si vous n'avez pas reçu l'email, vérifiez votre dossier de spam ou cliquez ci-dessous pour renvoyer l'email.</p>
        <a href="resend_verification.php">Renvoyer l'email de vérification</a>
    </div>
</body>
</html>
