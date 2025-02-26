<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    
    $query = $pdo->prepare("SELECT * FROM users WHERE email = ? AND email_verified = 0");
    $query->execute([$email]);
    $user = $query->fetch();
    
    if ($user) {
        // Générer un nouveau token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        // Mettre à jour le token dans la base de données
        $update = $pdo->prepare("UPDATE users SET verification_token = ?, token_expiry = ? WHERE id = ?");
        $update->execute([$token, $expiry, $user['id']]);
        
        // Envoyer l'email de vérification
        $to = $email;
        $subject = "Vérification de votre compte (nouveau lien)";
        $verification_link = "http://localhost/Web_resa/verify.php?token=" . $token;
        
        $message = "
        <html>
        <head>
            <title>Vérification de votre compte</title>
        </head>
        <body>
            <p>Bonjour {$user['prenom']} {$user['nom']},</p>
            <p>Voici un nouveau lien pour activer votre compte. Veuillez cliquer sur le lien ci-dessous :</p>
            <p><a href='$verification_link'>Vérifier mon compte</a></p>
            <p>Ce lien expirera dans 24 heures.</p>
            <p>Si vous n'avez pas créé de compte, veuillez ignorer cet email.</p>
        </body>
        </html>
        ";
        
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Votre Site <noreply@votre-site.com>' . "\r\n";
        
        if(mail($to, $subject, $message, $headers)) {
            $success_message = "Un nouveau lien de vérification a été envoyé à votre adresse email.";
        } else {
            $error_message = "Erreur lors de l'envoi de l'email. Veuillez réessayer.";
        }
    } else {
        $error_message = "Aucun compte non vérifié trouvé avec cette adresse email.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Renvoyer l'email de vérification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div>
        <h1>Renvoyer l'email de vérification</h1>
        
        <?php if (isset($success_message)): ?>
            <p style="color: green;"><?php echo $success_message; ?></p>
            <p><a href="login.php">Retour à la page de connexion</a></p>
        <?php else: ?>
            <?php if (isset($error_message)): ?>
                <p style="color: red;"><?php echo $error_message; ?></p>
            <?php endif; ?>
            
            <form method="post" action="">
                <div>
                    <label for="email">Adresse email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <button type="submit">Envoyer</button>
                </div>
            </form>
            
            <p><a href="login.php">Retour à la page de connexion</a></p>
        <?php endif; ?>
    </div>
</body>
</html>
