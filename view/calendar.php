<?php
require_once(__DIR__ . '/../../../../controller/eventC.php');
$eventController = new EventC();
$events = $eventController->listEvents();

// Convertir les événements en format JSON pour le calendrier
$calendarEvents = array_map(function($event) {
    return [
        'id' => $event['id'],
        'title' => $event['titre'],
        'start' => $event['date'] . 'T' . $event['heure_deb'],
        'end' => $event['date'] . 'T' . $event['heure_fin'],
        'description' => $event['description'],
        'lieu' => $event['lieu'],
        'statut' => $event['statut']
    ];
}, $events);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin - Calendrier des Événements</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/style-admin.css">
  
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
  
  <style>
    .calendar-container {
      background: var(--dark);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    
    #calendar {
      background: var(--darker);
      padding: 1.5rem;
      border-radius: 10px;
    }
    
    /* Personnalisation FullCalendar pour Admin */
    .fc {
      color: var(--light);
    }
    
    .fc-theme-standard .fc-scrollgrid {
      border-color: var(--border);
    }
    
    .fc-theme-standard td, 
    .fc-theme-standard th {
      border-color: var(--border);
    }
    
    .fc .fc-button {
      background: var(--primary);
      border: none;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
    }
    
    .fc .fc-button:hover {
      background: var(--primary-light);
    }
    
    .fc .fc-button:disabled {
      background: #666;
      opacity: 0.5;
    }
    
    .fc-toolbar-title {
      color: var(--primary) !important;
      font-size: 1.8rem !important;
      font-weight: 700 !important;
    }
    
    .fc-col-header-cell {
      background: var(--gray);
      color: var(--light) !important;
      font-weight: 600;
      padding: 1rem 0;
    }
    
    .fc-daygrid-day-number {
      color: var(--light);
      font-weight: 600;
      padding: 0.5rem;
    }
    
    .fc-daygrid-day.fc-day-today {
      background: rgba(233, 69, 96, 0.1) !important;
    }
    
    .fc-event {
      border-radius: 6px;
      padding: 4px 8px;
      margin: 2px 0;
      cursor: pointer;
      font-weight: 600;
      box-shadow: 0 2px 5px rgba(0,0,0,0.3);
    }
    
    .fc-event:hover {
      opacity: 0.9;
      transform: scale(1.02);
    }
    
    .view-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }
    
    .calendar-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .stat-card {
      background: var(--dark);
      border: 1px solid var(--border);
      border-radius: 10px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .stat-number {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      margin: 0.5rem 0;
    }
    
    .stat-label {
      color: var(--text-muted);
      font-size: 0.9rem;
    }
    
    .calendar-legend {
      display: flex;
      justify-content: center;
      gap: 2rem;
      margin-top: 1.5rem;
      flex-wrap: wrap;
    }
    
    .legend-item {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--text-muted);
    }
    
    .legend-color {
      width: 20px;
      height: 20px;
      border-radius: 4px;
    }
    
    .status-a-venir { background: #3498db; }
    .status-en-cours { background: var(--primary); }
    .status-termine { background: var(--success); }
    .status-annule { background: #95a5a6; }
    
    /* Modal */
    .modal-event {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.85);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    
    .modal-event.show {
      display: flex;
    }
    
    .modal-content-event {
      background: var(--dark);
      border: 2px solid var(--primary);
      border-radius: 12px;
      padding: 2rem;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 20px 60px rgba(0,0,0,0.5);
      position: relative;
    }
    
    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      color: var(--primary);
      font-size: 2rem;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .modal-close:hover {
      transform: rotate(90deg);
      color: var(--primary-light);
    }
    
    .modal-event h3 {
      color: var(--primary);
      margin-bottom: 1.5rem;
    }
    
    .modal-event-detail {
      margin: 1rem 0;
      color: var(--text-muted);
    }
    
    .modal-event-detail i {
      color: var(--primary);
      width: 30px;
    }
    
    .modal-event-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
      justify-content: center;
      flex-wrap: wrap;
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="app-sidebar">
    <div class="sidebar-brand">
      <h4>GameAct Admin</h4>
    </div>
    <ul class="nav nav-sidebar flex-column">
      <li class="nav-item">
        <a class="nav-link" href="../dashboard.php">
          <i class="fa fa-dashboard"></i> Dashboard
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="calendar.php">
          <i class="fa fa-calendar"></i> Calendrier
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="listeEvent.php">
          <i class="fa fa-list"></i> Liste Événements
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../users/index.php">
          <i class="fa fa-users"></i> Utilisateurs
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../settings.php">
          <i class="fa fa-cog"></i> Paramètres
        </a>
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="app-main">
    <div class="container-fluid">
      
      <div class="d-flex justify-between align-items-center mb-4">
        <h1 class="text-gradient">📅 Calendrier des Événements</h1>
        <a href="ajoutEvent.php" class="btn btn-primary">
          <i class="fa fa-plus"></i> Nouvel Événement
        </a>
      </div>

      <!-- Statistiques rapides -->
      <div class="calendar-stats">
        <div class="stat-card">
          <i class="fa fa-calendar-check" style="font-size:2rem; color:var(--primary);"></i>
          <div class="stat-number"><?php echo count($events); ?></div>
          <div class="stat-label">Total Événements</div>
        </div>
        <div class="stat-card">
          <i class="fa fa-clock" style="font-size:2rem; color:#3498db;"></i>
          <div class="stat-number">
            <?php echo count(array_filter($events, fn($e) => $e['statut'] === 'à venir')); ?>
          </div>
          <div class="stat-label">À venir</div>
        </div>
        <div class="stat-card">
          <i class="fa fa-play-circle" style="font-size:2rem; color:var(--primary);"></i>
          <div class="stat-number">
            <?php echo count(array_filter($events, fn($e) => $e['statut'] === 'en cours')); ?>
          </div>
          <div class="stat-label">En cours</div>
        </div>
        <div class="stat-card">
          <i class="fa fa-check-circle" style="font-size:2rem; color:var(--success);"></i>
          <div class="stat-number">
            <?php echo count(array_filter($events, fn($e) => $e['statut'] === 'terminé')); ?>
          </div>
          <div class="stat-label">Terminés</div>
        </div>
      </div>

      <!-- Contrôles de vue -->
      <div class="view-controls">
        <button class="btn btn-outline-light active" onclick="changeView('dayGridMonth')">
          <i class="fa fa-calendar"></i> Mois
        </button>
        <button class="btn btn-outline-light" onclick="changeView('timeGridWeek')">
          <i class="fa fa-calendar-week"></i> Semaine
        </button>
        <button class="btn btn-outline-light" onclick="changeView('timeGridDay')">
          <i class="fa fa-calendar-day"></i> Jour
        </button>
        <button class="btn btn-outline-light" onclick="changeView('listMonth')">
          <i class="fa fa-list"></i> Liste
        </button>
      </div>

      <!-- Calendrier -->
      <div class="calendar-container">
        <div id="calendar"></div>
        
        <!-- Légende -->
        <div class="calendar-legend">
          <div class="legend-item">
            <div class="legend-color status-a-venir"></div>
            <span>À venir</span>
          </div>
          <div class="legend-item">
            <div class="legend-color status-en-cours"></div>
            <span>En cours</span>
          </div>
          <div class="legend-item">
            <div class="legend-color status-termine"></div>
            <span>Terminé</span>
          </div>
          <div class="legend-item">
            <div class="legend-color status-annule"></div>
            <span>Annulé</span>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal Détails -->
  <div id="eventModal" class="modal-event">
    <div class="modal-content-event">
      <button class="modal-close" onclick="closeModal()">&times;</button>
      <h3 id="modalTitle"></h3>
      <div class="modal-event-detail">
        <i class="fa fa-calendar"></i> <strong>Date:</strong> <span id="modalDate"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-clock"></i> <strong>Horaire:</strong> <span id="modalTime"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-map-marker"></i> <strong>Lieu:</strong> <span id="modalLieu"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-info-circle"></i> <strong>Statut:</strong> <span id="modalStatut"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-align-left"></i> <strong>Description:</strong><br>
        <span id="modalDescription" style="display:block; margin-top:0.5rem; color:#ccc;"></span>
      </div>
      <div class="modal-event-actions">
        <a id="modalEditLink" href="#" class="btn btn-warning">
          <i class="fa fa-edit"></i> Modifier
        </a>
        <button id="modalDeleteBtn" class="btn btn-danger">
          <i class="fa fa-trash"></i> Supprimer
        </button>
        <button onclick="closeModal()" class="btn btn-outline-light">Fermer</button>
      </div>
    </div>
  </div>

  <footer class="app-footer">
    <p>Copyright © 2025 GameAct Admin. Tous droits réservés.</p>
  </footer>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js"></script>

  <script>
    const eventsData = <?php echo json_encode($calendarEvents); ?>;
    
    function getEventColor(statut) {
      switch(statut.toLowerCase()) {
        case 'à venir': return '#3498db';
        case 'en cours': return '#e94560';
        case 'terminé': return '#27ae60';
        case 'annulé': return '#95a5a6';
        default: return '#e94560';
      }
    }
    
    const events = eventsData.map(event => ({
      ...event,
      backgroundColor: getEventColor(event.statut),
      borderColor: getEventColor(event.statut)
    }));

    document.addEventListener('DOMContentLoaded', function() {
      const calendarEl = document.getElementById('calendar');
      const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
        },
        buttonText: {
          today: "Aujourd'hui",
          month: 'Mois',
          week: 'Semaine',
          day: 'Jour',
          list: 'Liste'
        },
        events: events,
        eventClick: function(info) {
          showEventModal(info.event);
        },
        dateClick: function(info) {
          window.location.href = 'ajoutEvent.php?date=' + info.dateStr;
        },
        height: 'auto',
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        },
        editable: true,
        eventDrop: function(info) {
          // Gérer le drag & drop (optionnel)
          if (confirm(`Déplacer "${info.event.title}" vers cette date ?`)) {
            // Ici vous pouvez ajouter un appel AJAX pour mettre à jour en BDD
            alert('Fonctionnalité à implémenter: mise à jour de la date en BDD');
          } else {
            info.revert();
          }
        }
      });
      
      calendar.render();
      window.calendar = calendar;
    });

    function changeView(viewName) {
      if (window.calendar) {
        window.calendar.changeView(viewName);
        document.querySelectorAll('.view-controls .btn').forEach(btn => {
          btn.classList.remove('active');
        });
        event.target.closest('.btn').classList.add('active');
      }
    }

    function showEventModal(event) {
      const modal = document.getElementById('eventModal');
      
      document.getElementById('modalTitle').textContent = event.title;
      document.getElementById('modalDate').textContent = new Date(event.start).toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
      
      const startTime = new Date(event.start).toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
      });
      const endTime = event.end ? new Date(event.end).toLocaleTimeString('fr-FR', {
        hour: '2-digit',
        minute: '2-digit'
      }) : '';
      
      document.getElementById('modalTime').textContent = `${startTime} - ${endTime}`;
      document.getElementById('modalLieu').textContent = event.extendedProps.lieu || 'Non spécifié';
      document.getElementById('modalStatut').textContent = event.extendedProps.statut || 'À venir';
      document.getElementById('modalDescription').textContent = event.extendedProps.description || 'Aucune description';
      document.getElementById('modalEditLink').href = `updateEvent.php?id=${event.id}`;
      
      // Gérer la suppression
      document.getElementById('modalDeleteBtn').onclick = function() {
        if (confirm(`Êtes-vous sûr de vouloir supprimer "${event.title}" ?`)) {
          window.location.href = `../../../deleteEvent.php?id=${event.id}`;
        }
      };
      
      modal.classList.add('show');
    }

    function closeModal() {
      document.getElementById('eventModal').classList.remove('show');
    }

    window.onclick = function(event) {
      const modal = document.getElementById('eventModal');
      if (event.target === modal) {
        closeModal();
      }
    }
  </script>
</body>
</html>