<?php
require_once('config.php');

class ParticipationC
{
    
    public function addParticipation($participation)
    {
        $sql = "INSERT INTO participation (nomP, emailP, statutP, remarqueP, id)
                VALUES (:nomP, :emailP, :statutP, :remarqueP, :id)";

        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nomP' => $participation->getNomP(),
                'emailP' => $participation->getEmailP(),
                'statutP' => $participation->getStatutP(),
                'remarqueP' => $participation->getRemarqueP(),
                'id' => $participation->getId()
            ]);
            return "Participation ajoutée avec succès !";
        } catch (PDOException $e) {
            echo 'Erreur PDO : ' . $e->getMessage();
            return "Erreur lors de l'ajout de la participation.";
        }
    }

    
    public function listParticipations()
    {
        $sql = "SELECT idP, nomP, emailP, statutP, remarqueP, id FROM participation";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    
    public function deleteParticipation($idP)
    {
        $sql = "DELETE FROM participation WHERE idP = :idP";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':idP', $idP);
            $query->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    
    public function showParticipation($idP)
    {
        $sql = "SELECT * FROM participation WHERE idP = :idP";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':idP', $idP);
            $query->execute();
            $participation = $query->fetch();
            return $participation;
        } catch (Exception $e) {
            throw new Exception('Erreur lors de l\'affichage de la participation : ' . $e->getMessage());
        }
    }

    
    public function updateParticipation($participation, $idP)
    {
        $sql = "UPDATE participation SET
                    nomP = :nomP,
                    emailP = :emailP,
                    statutP = :statutP,
                    remarqueP = :remarqueP,
                    id = :id
                WHERE idP = :idP";

        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':nomP', $participation->getNomP());
            $query->bindValue(':emailP', $participation->getEmailP());
            $query->bindValue(':statutP', $participation->getStatutP());
            $query->bindValue(':remarqueP', $participation->getRemarqueP());
            $query->bindValue(':id', $participation->getId());
            $query->bindValue(':idP', $idP);

            return $query->execute();
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    
    public function getParticipationsByEvent($eventId)
    {
        $sql = "SELECT * FROM participation WHERE id = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $eventId);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

   
    public function searchParticipations($search)
    {
        $sql = "SELECT * FROM participation WHERE 
                nomP LIKE :search OR 
                emailP LIKE :search OR 
                statutP LIKE :search OR 
                remarqueP LIKE :search";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':search', '%' . $search . '%');
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
?>
