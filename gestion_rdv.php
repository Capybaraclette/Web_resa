<?php
require '/config/config.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fonction pour récupérer les créneaux disponibles
function getAvailableSlots($pdo) {
    $query = $pdo->prepare("SELECT * FROM creneaux WHERE est_disponible = 1");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour prendre un rendez-vous
function bookAppointment($user_id, $date, $heure, $pdo) {
    // Vérifier si le créneau est disponible
    $query = $pdo->prepare("SELECT id FROM creneaux WHERE date_creneau = ? AND heure_creneau = ? AND est_disponible = 1");
    $query->execute([$date, $heure]);
    $creneau = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($creneau) {
        // Enregistrer le rendez-vous
        $insert = $pdo->prepare("INSERT INTO reservations (user_id, date_rdv, heure_rdv) VALUES (?, ?, ?)");
        $insert->execute([$user_id, $date, $heure]);
        
        // Marquer le créneau comme indisponible
        $update = $pdo->prepare("UPDATE creneaux SET est_disponible = 0 WHERE id = ?");
        $update->execute([$creneau['id']]);
        
        return "Rendez-vous réservé avec succès.";
    } else {
        return "Ce créneau n'est plus disponible.";
    }
}

// Fonction pour annuler un rendez-vous
function cancelAppointment($user_id, $appointment_id, $pdo) {
    // Récupérer le rendez-vous
    $query = $pdo->prepare("SELECT * FROM reservations WHERE id = ? AND user_id = ?");
    $query->execute([$appointment_id, $user_id]);
    $appointment = $query->fetch(PDO::FETCH_ASSOC);
    
    if ($appointment) {
        // Supprimer le rendez-vous
        $delete = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
        $delete->execute([$appointment_id]);
        
        // Libérer le créneau horaire
        $update = $pdo->prepare("UPDATE creneaux SET est_disponible = 1 WHERE date_creneau = ? AND heure_creneau = ?");
        $update->execute([$appointment['date_rdv'], $appointment['heure_rdv']]);
        
        return "Rendez-vous annulé avec succès.";
    } else {
        return "Impossible d'annuler ce rendez-vous.";
    }
    
}

// Fonction pour ajouter un créneau disponible
function addSlot($date, $heure, $pdo) {
    // Vérifier si le créneau existe déjà
    $query = $pdo->prepare("SELECT id FROM creneaux WHERE date_creneau = ? AND heure_creneau = ?");
    $query->execute([$date, $heure]);
    $existingSlot = $query->fetch(PDO::FETCH_ASSOC);
    
    if (!$existingSlot) {
        // Insérer le créneau
        $insert = $pdo->prepare("INSERT INTO creneaux (date_creneau, heure_creneau, est_disponible) VALUES (?, ?, 1)");
        $insert->execute([$date, $heure]);
        return "Créneau ajouté avec succès.";
    } else {
        return "Ce créneau existe déjà.";
    }
}

?>