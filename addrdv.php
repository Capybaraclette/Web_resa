<?php
require 'config.php';
require 'gestion_rdv.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['book'])) {
        $date = $_POST['date'];
        $heure = $_POST['heure'];
        $message = addSlot( $date, $heure, $pdo);
    } elseif (isset($_POST['cancel'])) {
        $appointment_id = $_POST['appointment_id'];
        $message = cancelAppointment($user_id, $appointment_id, $pdo);
    }
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout des Rendez-vous</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Ajout de Rendez-vous</h2>
        <?php if (isset($message)) : ?>
            <div class="alert alert-info"> <?= htmlspecialchars($message) ?> </div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" class="form-control" name="date" required>
            </div>
            <div class="mb-3">
                <label for="heure" class="form-label">Heure</label>
                <input type="time" class="form-control" name="heure" required>
            </div>
            <button type="submit" name="book" class="btn btn-primary">Ajouter rendez-vous</button>
        </form>

    </div>
</body>
</html>
