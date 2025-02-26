<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';
require_once 'gestion_utilisateurs.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user = getUserById($_SESSION['user_id'], $pdo);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (isset($_POST['update'])) {
    include 'gestion_utilisateurs.php';
}

if (isset($_POST['delete_account'])) {
    include 'gestion_utilisateurs.php';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Profil de <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></h2>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date de naissance</label>
                <input type="date" class="form-control" name="date" value="<?= htmlspecialchars($user['date_naissance']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="adresse" class="form-label">Adresse postale</label>
                <input type="text" class="form-control" name="adresse" value="<?= htmlspecialchars($user['adresse'])?>" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Numéro de téléphone</label>
                <input type="text" class="form-control" name="phone" value="<?= htmlspecialchars($user['telephone'])?>" required>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Mettre à jour</button>
        </form>
        <form method="POST" action="" class="mt-3">
            <button type="submit" name="delete_account" class="btn btn-danger">Supprimer mon compte</button>
        </form>
        <a href="logout.php" class="btn btn-secondary mt-3">Déconnexion</a>
    </div>
</body>
</html>
