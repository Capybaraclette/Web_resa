<?php
require 'config.php';
require 'gestion_rdv.php';
require 'gestion_utilisateurs.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Traitement de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book'])) {
        $date_heure = $_POST['heure'];  // Format date + heure
        list($date, $heure) = explode(' ', $date_heure); // Séparer la date et l'heure
        $message = bookAppointment($user_id, $date, $heure, $pdo);
    } elseif (isset($_POST['cancel'])) {
        $appointment_id = $_POST['appointment_id'];
        $message = cancelAppointment($user_id, $appointment_id, $pdo);
    }
}

// Récupérer les rendez-vous de l'utilisateur
$query = $pdo->prepare("SELECT * FROM reservations WHERE user_id = ?");
$query->execute([$user_id]);
$appointments = $query->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les créneaux disponibles
$availableSlots = getAvailableSlots($pdo);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="fontcolor" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="rdv.php">Prendre rendez-vous</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profil.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Déconnexion</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h2 class="text-center">Prise de Rendez-vous</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-info"> <?= htmlspecialchars($message) ?> </div>
        <?php endif; ?>
        
        <!-- Formulaire de réservation (sans champ de date) -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="heure" class="form-label">Choisir un créneau</label>
                <select class="form-control" name="heure" required>
                    <?php
                    // Affichage des créneaux groupés par date
                    $currentDate = null;
                    foreach ($availableSlots as $slot) :
                        $date_creneau = $slot['date_creneau'];
                        $heure_creneau = $slot['heure_creneau'];

                        // Grouper les créneaux par date
                        if ($date_creneau !== $currentDate) {
                            if ($currentDate !== null) {
                                echo '</optgroup>'; // Fermer l'optgroup précédent
                            }
                            echo "<optgroup label='" . htmlspecialchars($date_creneau) . "'>"; // Ouvrir un nouveau groupe pour chaque date
                            $currentDate = $date_creneau;
                        }
                    ?>
                        <option value="<?= htmlspecialchars($date_creneau . ' ' . $heure_creneau) ?>">
                            <?= htmlspecialchars($heure_creneau) ?>
                        </option>
                    <?php endforeach; ?>
                    </optgroup> <!-- Fermer le dernier optgroup -->
                </select>
            </div>
            <button type="submit" name="book" class="btn btn-primary">Réserver</button>
        </form>

        <h2 class="text-center mt-5">Mes Rendez-vous</h2>
        <ul class="list-group">
            <?php foreach ($appointments as $appointment) : ?>
                <li class="list-group-item">
                    <?= htmlspecialchars($appointment['date_rdv']) ?> à <?= htmlspecialchars($appointment['heure_rdv']) ?>
                    <form method="POST" action="" class="d-inline">
                        <input type="hidden" name="appointment_id" value="<?= $appointment['id'] ?>">
                        <button type="submit" name="cancel" class="btn btn-danger btn-sm">Annuler</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>
