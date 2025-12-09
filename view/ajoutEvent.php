<?php

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure les fichiers nécessaires
    require_once('C:\xampp\htdocs\events\controller\eventC.php');
    require_once('C:\xampp\htdocs\events\model\event.php');

    $eventC = new EventC();

    // Récupérer les champs du formulaire
    $titre = isset($_POST["titre"]) ? trim($_POST["titre"]) : "";
    $description = isset($_POST["description"]) ? trim($_POST["description"]) : "";
    $lieu = isset($_POST["lieu"]) ? trim($_POST["lieu"]) : "";
    $date = isset($_POST["date"]) ? $_POST["date"] : "";
    $statut = isset($_POST["statut"]) ? $_POST["statut"] : "";
    $heure_deb = isset($_POST["heure_deb"]) ? $_POST["heure_deb"] : "";
    $heure_fin = isset($_POST["heure_fin"]) ? $_POST["heure_fin"] : "";

    // Vérifier que tous les champs requis sont définis et non vides
    if (
        !empty($titre) &&
        !empty($description) &&
        !empty($lieu) &&
        !empty($date) &&
        !empty($statut) &&
        !empty($heure_deb) &&
        !empty($heure_fin)
    ) {
        // Création de l'objet Event avec les données du formulaire
        $event = new Event(
            null, // ID auto-incrémenté
            $titre,
            $description,
            $lieu,
            $date,
            $statut,
            $heure_deb,
            $heure_fin
        );

        // Ajouter l'événement via le contrôleur
        $result = $eventC->addEvent($event);

        ?>
        <script>
             alert("Événement ajouté avec succès !");
             window.location.href = 'listeEvent.php';
        </script>
        <?php
    } else {
        echo "<br><strong>Erreur : Un ou plusieurs champs sont vides.</strong><br>";
        if (empty($titre)) echo "Champ titre est vide.<br>";
        if (empty($description)) echo "Champ description est vide.<br>";
        if (empty($lieu)) echo "Champ lieu est vide.<br>";
        if (empty($date)) echo "Champ date est vide.<br>";
        if (empty($statut)) echo "Champ statut est vide.<br>";
        if (empty($heure_deb)) echo "Champ heure de début est vide.<br>";
        if (empty($heure_fin)) echo "Champ heure de fin est vide.<br>";

        ?>
        <script>
            setTimeout(function() {
                window.location.href = 'ajoutEvent.php';
            }, 3000);
        </script>
        <?php
    }
} else {
    ?>
    <script>
        alert("Accès non autorisé !");
        // window.location.href = 'front-office/login.php';
    </script>
    <?php
}
?>


