<?php
require_once('config.php');

class CalendarC
{
    /**
     * Récupère les événements pour une période donnée
     */
    public function getEventsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT id, titre, description, lieu, date, statut, heure_deb, heure_fin 
                FROM event 
                WHERE date BETWEEN :start AND :end
                ORDER BY date ASC, heure_deb ASC";
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute([
                'start' => $startDate,
                'end' => $endDate
            ]);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Récupère les événements d'un mois spécifique
     */
    public function getEventsByMonth($year, $month)
    {
        $startDate = "$year-$month-01";
        $endDate = date("Y-m-t", strtotime($startDate)); // Dernier jour du mois
        
        return $this->getEventsByDateRange($startDate, $endDate);
    }

    /**
     * Récupère les événements à venir (pour les 30 prochains jours)
     */
    public function getUpcomingEvents($days = 30)
    {
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime("+$days days"));
        
        return $this->getEventsByDateRange($startDate, $endDate);
    }

    /**
     * Récupère les événements d'aujourd'hui
     */
    public function getTodayEvents()
    {
        $today = date('Y-m-d');
        return $this->getEventsByDateRange($today, $today);
    }

    /**
     * Récupère les statistiques des événements
     */
    public function getEventStats()
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN statut = 'à venir' THEN 1 ELSE 0 END) as a_venir,
                SUM(CASE WHEN statut = 'en cours' THEN 1 ELSE 0 END) as en_cours,
                SUM(CASE WHEN statut = 'terminé' THEN 1 ELSE 0 END) as termine,
                SUM(CASE WHEN statut = 'annulé' THEN 1 ELSE 0 END) as annule
                FROM event";
        
        $db = config::getConnexion();
        
        try {
            $query = $db->query($sql);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Vérifie si une date a des conflits d'événements (même lieu, même horaire)
     */
    public function checkDateConflicts($date, $heureDeb, $heureFin, $lieu, $excludeId = null)
    {
        $sql = "SELECT id, titre, heure_deb, heure_fin 
                FROM event 
                WHERE date = :date 
                AND lieu = :lieu
                AND (
                    (:heureDeb BETWEEN heure_deb AND heure_fin)
                    OR (:heureFin BETWEEN heure_deb AND heure_fin)
                    OR (heure_deb BETWEEN :heureDeb AND :heureFin)
                )";
        
        if ($excludeId) {
            $sql .= " AND id != :excludeId";
        }
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $params = [
                'date' => $date,
                'lieu' => $lieu,
                'heureDeb' => $heureDeb,
                'heureFin' => $heureFin
            ];
            
            if ($excludeId) {
                $params['excludeId'] = $excludeId;
            }
            
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Met à jour la date et les horaires d'un événement (pour drag & drop)
     */
    public function updateEventDateTime($id, $newDate, $newHeureDeb, $newHeureFin)
    {
        $sql = "UPDATE event 
                SET date = :date, 
                    heure_deb = :heure_deb, 
                    heure_fin = :heure_fin 
                WHERE id = :id";
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            return $query->execute([
                'id' => $id,
                'date' => $newDate,
                'heure_deb' => $newHeureDeb,
                'heure_fin' => $newHeureFin
            ]);
        } catch (Exception $e) {
            throw new Exception('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    /**
     * Récupère les événements avec filtres
     */
    public function getFilteredEvents($filters = [])
    {
        $sql = "SELECT id, titre, description, lieu, date, statut, heure_deb, heure_fin 
                FROM event WHERE 1=1";
        
        $params = [];
        
        if (isset($filters['statut']) && !empty($filters['statut'])) {
            $sql .= " AND statut = :statut";
            $params['statut'] = $filters['statut'];
        }
        
        if (isset($filters['lieu']) && !empty($filters['lieu'])) {
            $sql .= " AND lieu LIKE :lieu";
            $params['lieu'] = '%' . $filters['lieu'] . '%';
        }
        
        if (isset($filters['dateDebut']) && !empty($filters['dateDebut'])) {
            $sql .= " AND date >= :dateDebut";
            $params['dateDebut'] = $filters['dateDebut'];
        }
        
        if (isset($filters['dateFin']) && !empty($filters['dateFin'])) {
            $sql .= " AND date <= :dateFin";
            $params['dateFin'] = $filters['dateFin'];
        }
        
        $sql .= " ORDER BY date ASC, heure_deb ASC";
        
        $db = config::getConnexion();
        
        try {
            $query = $db->prepare($sql);
            $query->execute($params);
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Exporte les événements au format iCalendar (.ics)
     */
    public function exportToICS($events)
    {
        $icsContent = "BEGIN:VCALENDAR\r\n";
        $icsContent .= "VERSION:2.0\r\n";
        $icsContent .= "PRODID:-//GameAct//Events Calendar//FR\r\n";
        $icsContent .= "CALSCALE:GREGORIAN\r\n";
        $icsContent .= "METHOD:PUBLISH\r\n";
        
        foreach ($events as $event) {
            $dateDebut = str_replace(['-', ':'], '', $event['date'] . 'T' . $event['heure_deb']);
            $dateFin = str_replace(['-', ':'], '', $event['date'] . 'T' . $event['heure_fin']);
            
            $icsContent .= "BEGIN:VEVENT\r\n";
            $icsContent .= "UID:" . $event['id'] . "@gameact.com\r\n";
            $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
            $icsContent .= "DTSTART:" . $dateDebut . "\r\n";
            $icsContent .= "DTEND:" . $dateFin . "\r\n";
            $icsContent .= "SUMMARY:" . $this->escapeICS($event['titre']) . "\r\n";
            $icsContent .= "DESCRIPTION:" . $this->escapeICS($event['description']) . "\r\n";
            $icsContent .= "LOCATION:" . $this->escapeICS($event['lieu']) . "\r\n";
            $icsContent .= "STATUS:" . strtoupper($event['statut']) . "\r\n";
            $icsContent .= "END:VEVENT\r\n";
        }
        
        $icsContent .= "END:VCALENDAR\r\n";
        
        return $icsContent;
    }

    /**
     * Échappe les caractères spéciaux pour le format iCalendar
     */
    private function escapeICS($text)
    {
        $text = str_replace(['\\', ',', ';', "\n"], ['\\\\', '\\,', '\\;', '\\n'], $text);
        return $text;
    }
}
?>