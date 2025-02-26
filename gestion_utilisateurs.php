<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require 'config.php'; // Fichier de configuration de la base de données

// Inscription
if (isset($_POST['register'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $date = htmlspecialchars($_POST['date']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $phone = htmlspecialchars($_POST['phone']);

    // Vérifier si l'email est unique
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->rowCount() == 0) {
        $insert = $pdo->prepare("INSERT INTO users (nom, prenom, email, password, date_naissance, adresse, telephone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert->execute([$nom, $prenom, $email, $password, $date, $adresse, $phone]);
        $_SESSION['user_id'] = $user['id'];
        header("Location: profil.php");
        echo "Inscription réussie. Veuillez vérifier votre email.";
    } else {
        echo "Cet email est déjà utilisé.";
    }
}

// Connexion
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: profil.php");
    } else {
        echo "Identifiants incorrects.";
    }
}

// Modification des informations
if (isset($_POST['update'])) {
    $user_id = $_SESSION['user_id'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $date = htmlspecialchars($_POST['date']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $phone = htmlspecialchars($_POST['phone']);

    $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check->execute([$email, $user_id]);
    if ($check->rowCount() == 0) {
        $update = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, email = ?, date_naissance = ?, adresse = ?, telephone = ? WHERE id = ?");
        $update->execute([$nom, $prenom, $email, $date, $adresse, $phone, $user_id]);        echo "Mise à jour réussie.";
    } else {
        echo "Cet email est déjà utilisé.";
    }
}

// Suppression de compte
if (isset($_POST['delete_account'])) {
    $user_id = $_SESSION['user_id'];
    $delete = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $delete->execute([$user_id]);
    session_destroy();
    header("Location: login.php");
    exit();
}

function getUserById($user_id, $pdo) {
    $query = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $query->execute([$user_id]);
    return $query->fetch(PDO::FETCH_ASSOC);
}
?>
