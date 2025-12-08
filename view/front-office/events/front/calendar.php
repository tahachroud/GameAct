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
  <title>GameAct - Calendrier des Événements</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="events-custom.css">
  
  <!-- FullCalendar CSS -->
  <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
  
  <style>
    .calendar-container {
      background: rgba(255, 255, 255, 0.05);
      backdrop-filter: blur(10px);
      border-radius: 20px;
      padding: 2rem;
      margin: 2rem auto;
      border: 1px solid rgba(255, 255, 255, 0.1);
      max-width: 1400px;
    }
    
    #calendar {
      background: rgba(0, 0, 0, 0.3);
      padding: 1.5rem;
      border-radius: 15px;
    }
    
    /* Personnalisation FullCalendar */
    .fc {
      color: #fff;
    }
    
    .fc-theme-standard .fc-scrollgrid {
      border-color: rgba(255, 255, 255, 0.1);
    }
    
    .fc-theme-standard td, 
    .fc-theme-standard th {
      border-color: rgba(255, 255, 255, 0.1);
    }
    
    .fc .fc-button {
      background: #e94560;
      border: none;
      padding: 0.6rem 1.2rem;
      font-weight: 600;
      text-transform: uppercase;
    }
    
    .fc .fc-button:hover {
      background: #ff6b7a;
    }
    
    .fc .fc-button:disabled {
      background: #666;
      opacity: 0.5;
    }
    
    .fc-toolbar-title {
      color: #e94560 !important;
      font-size: 1.8rem !important;
      font-weight: 700 !important;
      text-shadow: 0 0 20px rgba(233, 69, 96, 0.5);
    }
    
    .fc-col-header-cell {
      background: rgba(233, 69, 96, 0.2);
      color: #fff !important;
      font-weight: 600;
      padding: 1rem 0;
    }
    
    .fc-daygrid-day-number {
      color: #fff;
      font-weight: 600;
      padding: 0.5rem;
    }
    
    .fc-daygrid-day.fc-day-today {
      background: rgba(233, 69, 96, 0.15) !important;
    }
    
    .fc-event {
      background: linear-gradient(135deg, #e94560, #ff6b7a);
      border: none;
      border-radius: 8px;
      padding: 4px 8px;
      margin: 2px 0;
      cursor: pointer;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(233, 69, 96, 0.4);
    }
    
    .fc-event:hover {
      background: linear-gradient(135deg, #ff6b7a, #e94560);
      transform: scale(1.02);
    }
    
    .fc-event-title {
      font-size: 0.85rem;
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
      color: #ccc;
      font-size: 0.9rem;
    }
    
    .legend-color {
      width: 20px;
      height: 20px;
      border-radius: 4px;
    }
    
    .status-a-venir { background: #3498db; }
    .status-en-cours { background: #e94560; }
    .status-termine { background: #27ae60; }
    .status-annule { background: #95a5a6; }
    
    .view-controls {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
    }
    
    .view-btn {
      padding: 0.6rem 1.5rem;
      background: rgba(255, 255, 255, 0.1);
      border: 2px solid #e94560;
      border-radius: 50px;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .view-btn:hover,
    .view-btn.active {
      background: #e94560;
      transform: translateY(-2px);
    }
    
    /* Modal personnalisé */
    .modal-event {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.8);
      z-index: 9999;
      align-items: center;
      justify-content: center;
    }
    
    .modal-event.show {
      display: flex;
    }
    
    .modal-content-event {
      background: linear-gradient(135deg, #1a1a2e 0%, #0f0f1e 100%);
      border: 2px solid #e94560;
      border-radius: 20px;
      padding: 2rem;
      max-width: 600px;
      width: 90%;
      box-shadow: 0 20px 60px rgba(233, 69, 96, 0.3);
      position: relative;
    }
    
    .modal-close {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: none;
      border: none;
      color: #e94560;
      font-size: 2rem;
      cursor: pointer;
      transition: 0.3s;
    }
    
    .modal-close:hover {
      transform: rotate(90deg);
      color: #ff6b7a;
    }
    
    .modal-event h3 {
      color: #e94560;
      margin-bottom: 1.5rem;
      font-size: 1.8rem;
    }
    
    .modal-event-detail {
      margin: 1rem 0;
      color: #ccc;
      font-size: 1.1rem;
    }
    
    .modal-event-detail i {
      color: #e94560;
      width: 30px;
    }
    
    .modal-event-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
      justify-content: center;
    }
  </style>
</head>
<body>

  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots"><span></span><span></span><span></span></div>
    </div>
  </div>

  <!-- HEADER -->
  <header class="header-area header-sticky">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <nav class="main-nav">
            <a href="../../index.html" class="logo">
              <img src="../../assets/images/logo.png" alt="GameAct" style="height:60px;">
            </a>
            <ul class="nav">
              <li><a href="../../index.html">Accueil</a></li>
              <li><a href="../../browse.html">Parcourir</a></li>
              <li><a href="index.php">Événements</a></li>
              <li><a href="calendar.php" class="active">Calendrier</a></li>
              <li><a href="../../streams.html">Tutorials</a></li>
              <li><a href="../../profile.html">Profil</a></li>
              <li><a href="../../leaderboard.html">Leaderboard</a></li>
            </ul>
            <a class='menu-trigger'><span>Menu</span></a>
          </nav>
        </div>
      </div>
    </div>
  </header>

  <div class="container">
    <div class="page-content">

      <div class="mb-4">
        <a href="index.php" class="btn-back">
          <i class="fa fa-arrow-left"></i> Retour aux événements
        </a>
      </div>

      <div class="text-center mb-4">
        <h1 style="color:#e94560; font-size:3rem; text-shadow: 0 0 30px rgba(233,69,96,0.5);">
          📅 Calendrier des Événements
        </h1>
        <p style="color:#ccc; font-size:1.1rem;">Planifiez et gérez vos événements gaming</p>
      </div>

      <!-- Contrôles de vue -->
      <div class="view-controls">
        <button class="view-btn active" onclick="changeView('dayGridMonth')">
          <i class="fa fa-calendar"></i> Mois
        </button>
        <button class="view-btn" onclick="changeView('timeGridWeek')">
          <i class="fa fa-calendar-o"></i> Semaine
        </button>
        <button class="view-btn" onclick="changeView('timeGridDay')">
          <i class="fa fa-calendar-check-o"></i> Jour
        </button>
        <button class="view-btn" onclick="changeView('listMonth')">
          <i class="fa fa-list"></i> Liste
        </button>
      </div>

      <!-- Bouton Ajouter -->
      <div class="text-center mb-4">
        <a href="add-event.html" class="btn-primary" style="display:inline-block;">
          <i class="fa fa-plus"></i> Créer un événement
        </a>
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

  <!-- Modal Détails Événement -->
  <div id="eventModal" class="modal-event">
    <div class="modal-content-event">
      <button class="modal-close" onclick="closeModal()">&times;</button>
      <h3 id="modalTitle"></h3>
      <div class="modal-event-detail">
        <i class="fa fa-calendar"></i> <strong>Date:</strong> <span id="modalDate"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-clock-o"></i> <strong>Horaire:</strong> <span id="modalTime"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-map-marker"></i> <strong>Lieu:</strong> <span id="modalLieu"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-info-circle"></i> <strong>Statut:</strong> <span id="modalStatut"></span>
      </div>
      <div class="modal-event-detail">
        <i class="fa fa-align-left"></i> <strong>Description:</strong><br>
        <span id="modalDescription" style="display:block; margin-top:0.5rem;"></span>
      </div>
      <div class="modal-event-actions">
        <a id="modalDetailLink" href="#" class="btn-primary" style="display:inline-block;">
          Voir détails complets
        </a>
        <button onclick="closeModal()" class="btn-cancel">Fermer</button>
      </div>
    </div>
  </div>

  <footer class="mt-5 py-4" style="background:#0f0f1e; border-top:1px solid #333;">
    <div class="container text-center">
      <p style="color:#aaa;">Copyright © 2025 GameAct. Tous droits réservés.</p>
    </div>
  </footer>

  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/custom.js"></script>
  
  <!-- FullCalendar JS -->
  <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js"></script>

  <script>
    // Données des événements depuis PHP
    const eventsData = <?php echo json_encode($calendarEvents); ?>;
    
    // Fonction pour obtenir la couleur selon le statut
    function getEventColor(statut) {
      switch(statut.toLowerCase()) {
        case 'à venir': return '#3498db';
        case 'en cours': return '#e94560';
        case 'terminé': return '#27ae60';
        case 'annulé': return '#95a5a6';
        default: return '#e94560';
      }
    }
    
    // Ajouter les couleurs aux événements
    const events = eventsData.map(event => ({
      ...event,
      backgroundColor: getEventColor(event.statut),
      borderColor: getEventColor(event.statut)
    }));

    // Initialiser FullCalendar
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
          // Rediriger vers le formulaire avec la date pré-remplie
          window.location.href = 'add-event.html?date=' + info.dateStr;
        },
        height: 'auto',
        eventTimeFormat: {
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        }
      });
      
      calendar.render();
      
      // Rendre le calendrier accessible globalement
      window.calendar = calendar;
    });

    // Changer la vue
    function changeView(viewName) {
      if (window.calendar) {
        window.calendar.changeView(viewName);
        
        // Mettre à jour les boutons actifs
        document.querySelectorAll('.view-btn').forEach(btn => {
          btn.classList.remove('active');
        });
        event.target.closest('.view-btn').classList.add('active');
      }
    }

    // Afficher le modal
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
      document.getElementById('modalDetailLink').href = `detail.php?id=${event.id}`;
      
      modal.classList.add('show');
    }

    // Fermer le modal
    function closeModal() {
      document.getElementById('eventModal').classList.remove('show');
    }

    // Fermer le modal en cliquant en dehors
    window.onclick = function(event) {
      const modal = document.getElementById('eventModal');
      if (event.target === modal) {
        closeModal();
      }
    }
  </script>
</body>
</html>