<?php

require_once(__DIR__ . '/../controller/participationC.php');
require_once(__DIR__ . '/../model/participation.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['nomP'], $_POST['emailP'], $_POST['telephone'], $_POST['discord'], $_POST['age'], $_POST['niveau'], $_POST['id'])) {
        
        $nomP = $_POST['nomP'];
        $emailP = $_POST['emailP'];
        $telephone = $_POST['telephone'];
        $discord = $_POST['discord'];
        $age = $_POST['age'];
        $niveau = $_POST['niveau'];
        $eventId = (int)$_POST['id'];
        
       
        $remarqueP = "Tel: $telephone, Discord: $discord, Age: $age, Niveau: $niveau";
        $statutP = "En attente"; 

        
        $participation = new Participation(
            null,
            $nomP,
            $emailP,
            $statutP,
            $remarqueP,
            $eventId
        );

        
        $participationController = new ParticipationC();

       
        $result = $participationController->addParticipation($participation);
        
        if ($result) {
             
             echo "<script>
                alert('Inscription réussie !');
                window.location.href = 'front-office/events/front/detail.php?id=$eventId';
             </script>";
        } else {
            
            echo "<script>
                alert('Erreur lors de l\'inscription.');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Champs manquants.');
            window.history.back();
        </script>";
    }
} else {
  
    header('Location: index.php');
    exit;
}
?>
