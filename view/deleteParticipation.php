<?php
require_once(__DIR__ . '/../controller/participationC.php');

$participationC = new ParticipationC();

// Suppression de la participation via l'ID passé en GET
if (isset($_GET['idP'])) {
    $participationC->deleteParticipation($_GET['idP']);
    
    // Check if we should redirect back to a filtered list
    if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'listeParticipation.php') !== false) {
         header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
         header('Location: listeParticipation.php');
    }
    exit();
} else {
    echo "Erreur : ID de la participation non spécifié.";
}
?>
