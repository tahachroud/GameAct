<?php
require_once(__DIR__ . '/../controller/participationC.php');

$participationC = new ParticipationC();


if (isset($_GET['idP'])) {
    $participationC->deleteParticipation($_GET['idP']);
    
    
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
