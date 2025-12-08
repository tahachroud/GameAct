<?php
require_once('config.php');

class EventC
{
    // Ajouter un événement
    public function addEvent($event)
    {
        $sql = "INSERT INTO event (titre, description, lieu, date, statut, heure_deb, heure_fin, latitude, longitude)
                VALUES (:titre, :description, :lieu, :date, :statut, :heure_deb, :heure_fin, :latitude, :longitude)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'titre' => $event->getTitre(),
                'description' => $event->getDescription(),
                'lieu' => $event->getLieu(),
                'date' => $event->getDate(),
                'statut' => $event->getStatut(),
                'heure_deb' => $event->getHeureDeb(),
                'heure_fin' => $event->getHeureFin(),
                // NOUVEAU
                'latitude' => $event->getLatitude(),
                'longitude' => $event->getLongitude()

            ]);
            return "Événement ajouté avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de l'ajout de l'événement.";
        }
    }

    // Liste des événements (optimisé)
    public function listEvents()
    {
        // Sélectionner toutes les colonnes, y compris les nouvelles
        $sql = "SELECT id, titre, description, lieu, date, statut, heure_deb, heure_fin, latitude, longitude FROM event";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Afficher un événement
    public function showEvent($id)
    {
        $sql = "SELECT * FROM event WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            $event = $query->fetch(PDO::FETCH_ASSOC);
            return $event;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    // Supprimer un événement
    public function deleteEvent($id)
    {
        $sql = "DELETE FROM event WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id);
            $query->execute();
            return "Événement supprimé avec succès.";
        } catch (Exception $e) {
            return "Erreur lors de la suppression: " . $e->getMessage();
        }
    }

    // Mettre à jour un événement
    public function updateEvent($event, $id)
    {
        $sql = "UPDATE event SET
                titre = :titre,
                description = :description,
                lieu = :lieu,
                date = :date,
                statut = :statut,
                heure_deb = :heure_deb,
                heure_fin = :heure_fin,
                latitude = :latitude,
                longitude = :longitude

                WHERE id = :id";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':titre', $event->getTitre());
            $query->bindValue(':description', $event->getDescription());
            $query->bindValue(':lieu', $event->getLieu());
            $query->bindValue(':date', $event->getDate());
            $query->bindValue(':statut', $event->getStatut());
            $query->bindValue(':heure_deb', $event->getHeureDeb());
            $query->bindValue(':heure_fin', $event->getHeureFin());
            // NOUVEAU
            $query->bindValue(':latitude', $event->getLatitude());
            $query->bindValue(':longitude', $event->getLongitude());

            $query->bindValue(':id', $id);

            return $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    // Rechercher des événements
    public function searchEvents($search)
    {
        $sql = "SELECT * FROM event WHERE 
                titre LIKE :search OR 
                description LIKE :search OR 
                lieu LIKE :search OR 
                statut LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur de recherche: ' . $e->getMessage());
        }
    }
}