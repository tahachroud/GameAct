<?php

require_once('C:\xampp\htdocs\events\controller\eventC.php');

$eventC = new EventC();

// Suppression de l'événement via l'ID passé en GET
if (isset($_GET['id'])) {
    $eventC->deleteEvent($_GET['id']);
    header('Location: listeEvent.php');
    exit();
} else {
    echo "Erreur : ID de l'événement non spécifié.";
}
?>


