<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require_once 'gestion_utilisateurs.php'; // Inclusion du fichier de gestion des utilisateurs

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Connexion / Inscription</h2>
        <div class="row">
            <div class="col-md-6">
                <h3>Connexion</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <?php if (isset($error)) : ?>
                        <div class="alert alert-danger"> <?= $error ?> </div>
                    <?php endif; ?>
                    <button type="submit" name="login" class="btn btn-primary w-100">Se connecter</button>
                </form>
            </div>
            <div class="col-md-6">
                <h3>Inscription</h3>
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="prenom" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="date" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse postale</label>
                        <input type="text" class="form-control" name="adresse" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-success w-100">S'inscrire</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
