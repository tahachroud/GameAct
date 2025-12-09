<?php
// Include the necessary files
require_once(__DIR__ . '/../controller/participationC.php');
require_once(__DIR__ . '/../model/participation.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate required fields
    if (isset($_POST['nomP'], $_POST['emailP'], $_POST['telephone'], $_POST['discord'], $_POST['age'], $_POST['niveau'], $_POST['id'])) {
        
        $nomP = $_POST['nomP'];
        $emailP = $_POST['emailP'];
        $telephone = $_POST['telephone'];
        $discord = $_POST['discord'];
        $age = $_POST['age'];
        $niveau = $_POST['niveau'];
        $eventId = (int)$_POST['id'];
        
        // Combine extra info into remarqueP
        $remarqueP = "Tel: $telephone, Discord: $discord, Age: $age, Niveau: $niveau";
        $statutP = "En attente"; // Default status

        // Create Participation object
        $participation = new Participation(
            null,
            $nomP,
            $emailP,
            $statutP,
            $remarqueP,
            $eventId
        );

        // Create Controller instance
        $participationController = new ParticipationC();

        // Add participation
        $result = $participationController->addParticipation($participation);
        
        if ($result) {
             // Success: Redirect to detail page with success message
             echo "<script>
                alert('Inscription réussie !');
                window.location.href = 'front-office/events/front/detail.php?id=$eventId';
             </script>";
        } else {
            // Failure: Go back to form
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
    // Not a POST request
    header('Location: index.php');
    exit;
}
?>
