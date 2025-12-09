<?php
/**
 * Fichier pour gérer les actions AJAX du calendrier
 * Actions: update_event_date, get_events, check_conflicts
 */

header('Content-Type: application/json');
require_once(__DIR__ . '/../controller/eventC.php');
require_once(__DIR__ . '/../controller/calendarC.php');
require_once(__DIR__ . '/../model/event.php');

$eventC = new EventC();
$calendarC = new CalendarC();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

try {
    switch ($action) {
        
        case 'update_event_date':
            // Mise à jour de la date/heure d'un événement (drag & drop)
            $id = $_POST['id'] ?? 0;
            $newDate = $_POST['date'] ?? '';
            $newStart = $_POST['start'] ?? '';
            $newEnd = $_POST['end'] ?? '';
            
            if (!$id || !$newDate || !$newStart || !$newEnd) {
                echo json_encode(['success' => false, 'message' => 'Données manquantes']);
                exit;
            }
            
            // Extraire l'heure de début et de fin
            $heureDeb = date('H:i:s', strtotime($newStart));
            $heureFin = date('H:i:s', strtotime($newEnd));
            
            $result = $calendarC->updateEventDateTime($id, $newDate, $heureDeb, $heureFin);
            
            if ($result) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Événement déplacé avec succès'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Erreur lors du déplacement'
                ]);
            }
            break;
            
        case 'get_events':
            // Récupérer les événements pour une période
            $start = $_GET['start'] ?? '';
            $end = $_GET['end'] ?? '';
            
            if ($start && $end) {
                $events = $calendarC->getEventsByDateRange($start, $end);
            } else {
                $events = $eventC->listEvents();
            }
            
            // Formater pour FullCalendar
            $calendarEvents = array_map(function($event) {
                return [
                    'id' => $event['id'],
                    'title' => $event['titre'],
                    'start' => $event['date'] . 'T' . $event['heure_deb'],
                    'end' => $event['date'] . 'T' . $event['heure_fin'],
                    'description' => $event['description'],
                    'lieu' => $event['lieu'],
                    'statut' => $event['statut'],
                    'backgroundColor' => getStatusColor($event['statut']),
                    'borderColor' => getStatusColor($event['statut'])
                ];
            }, $events);
            
            echo json_encode($calendarEvents);
            break;
            
        case 'check_conflicts':
            // Vérifier les conflits d'horaires
            $date = $_POST['date'] ?? '';
            $heureDeb = $_POST['heure_deb'] ?? '';
            $heureFin = $_POST['heure_fin'] ?? '';
            $lieu = $_POST['lieu'] ?? '';
            $excludeId = $_POST['exclude_id'] ?? null;
            
            if (!$date || !$heureDeb || !$heureFin || !$lieu) {
                echo json_encode([
                    'hasConflict' => false, 
                    'message' => 'Données incomplètes'
                ]);
                exit;
            }
            
            $conflicts = $calendarC->checkDateConflicts($date, $heureDeb, $heureFin, $lieu, $excludeId);
            
            if (count($conflicts) > 0) {
                $conflictDetails = array_map(function($c) {
                    return $c['titre'] . ' (' . substr($c['heure_deb'], 0, 5) . '-' . substr($c['heure_fin'], 0, 5) . ')';
                }, $conflicts);
                
                echo json_encode([
                    'hasConflict' => true,
                    'conflicts' => $conflicts,
                    'message' => 'Conflit détecté avec: ' . implode(', ', $conflictDetails)
                ]);
            } else {
                echo json_encode([
                    'hasConflict' => false,
                    'message' => 'Aucun conflit'
                ]);
            }
            break;
            
        case 'get_stats':
            // Récupérer les statistiques
            $stats = $calendarC->getEventStats();
            echo json_encode($stats);
            break;
            
        case 'get_upcoming':
            // Récupérer les événements à venir
            $days = $_GET['days'] ?? 30;
            $events = $calendarC->getUpcomingEvents($days);
            echo json_encode($events);
            break;
            
        case 'export_ics':
            // Exporter au format iCalendar
            $events = $eventC->listEvents();
            $icsContent = $calendarC->exportToICS($events);
            
            header('Content-Type: text/calendar; charset=utf-8');
            header('Content-Disposition: attachment; filename="gameact-events.ics"');
            echo $icsContent;
            exit;
            break;
            
        case 'filter_events':
            // Filtrer les événements
            $filters = [
                'statut' => $_POST['statut'] ?? '',
                'lieu' => $_POST['lieu'] ?? '',
                'dateDebut' => $_POST['date_debut'] ?? '',
                'dateFin' => $_POST['date_fin'] ?? ''
            ];
            
            $events = $calendarC->getFilteredEvents($filters);
            
            // Formater pour FullCalendar
            $calendarEvents = array_map(function($event) {
                return [
                    'id' => $event['id'],
                    'title' => $event['titre'],
                    'start' => $event['date'] . 'T' . $event['heure_deb'],
                    'end' => $event['date'] . 'T' . $event['heure_fin'],
                    'description' => $event['description'],
                    'lieu' => $event['lieu'],
                    'statut' => $event['statut'],
                    'backgroundColor' => getStatusColor($event['statut']),
                    'borderColor' => getStatusColor($event['statut'])
                ];
            }, $events);
            
            echo json_encode($calendarEvents);
            break;
            
        default:
            echo json_encode([
                'success' => false, 
                'message' => 'Action non reconnue'
            ]);
            break;
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}

/**
 * Fonction helper pour obtenir la couleur selon le statut
 */
function getStatusColor($statut) {
    switch(strtolower($statut)) {
        case 'à venir': return '#3498db';
        case 'en cours': return '#e94560';
        case 'terminé': return '#27ae60';
        case 'annulé': return '#95a5a6';
        default: return '#e94560';
    }
}
?>