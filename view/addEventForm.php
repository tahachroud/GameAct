<?php
// Get pre-filled date from URL if available
$prefilledDate = isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '';
?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter un Événement · GameAct Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.5.2/css/all.min.css" rel="stylesheet">
  <link rel="stylesheet" href="backoffice/css/style-admin.css">
  
  <!-- Leaflet CSS for Maps -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
    integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  
  <style>
    .form-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .form-section {
      background: var(--dark);
      border: 1px solid var(--border);
      border-radius: 12px;
      padding: 2rem;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    
    .form-section h2 {
      color: var(--primary);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 1rem;
      margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
      .form-row {
        grid-template-columns: 1fr;
      }
    }
    
    .btn-group-actions {
      display: flex;
      gap: 1rem;
      margin-top: 2rem;
    }
    
    .btn-group-actions .btn {
      flex: 1;
    }
    
    /* Map Styles */
    #map {
      height: 400px;
      width: 100%;
      border-radius: 12px;
      margin-top: 10px;
      border: 2px solid var(--primary);
      box-shadow: 0 4px 15px rgba(233, 69, 96, 0.3);
    }

    .search-container {
      position: relative;
      margin-bottom: 15px;
    }

    #searchInput {
      width: 100%;
      padding: 12px 120px 12px 15px;
      border: 1px solid var(--border);
      border-radius: 8px;
      background: var(--dark);
      color: #fff;
      font-size: 14px;
    }

    #searchInput:focus {
      border-color: var(--primary);
      outline: none;
    }

    #searchBtn {
      position: absolute;
      right: 5px;
      top: 5px;
      background: var(--primary);
      border: none;
      padding: 8px 15px;
      border-radius: 6px;
      color: white;
      cursor: pointer;
      transition: all 0.3s;
    }

    #searchBtn:hover {
      background: #d63651;
      transform: scale(1.05);
    }

    .coordinates-info {
      margin-top: 10px;
      padding: 10px;
      background: rgba(233, 69, 96, 0.1);
      border-radius: 8px;
      color: var(--primary);
      font-size: 13px;
      display: none;
    }
  </style>
</head>
<body>

  <!-- Header -->
  <header class="app-header">
    <div class="header-content">
      <div class="d-flex align-items-center">
        <button class="btn btn-link text-light d-lg-none me-3" id="sidebarToggle">
          <i class="fas fa-bars"></i>
        </button>
        <a href="#" class="text-decoration-none">
          <span class="brand-text">GameAct Admin</span>
        </a>
      </div>
      <div>
        <button class="btn btn-outline-light btn-sm me-2"><i class="fas fa-bell"></i></button>
        <a href="#" class="btn btn-outline-danger btn-sm">Déconnexion</a>
      </div>
    </div>
  </header>

  <!-- Sidebar -->
  <aside class="app-sidebar" id="sidebar">
    <div class="sidebar-brand">
      <h4 class="text-gradient">GameAct</h4>
    </div>
    <nav class="nav-sidebar">
      <a href="listeEvent.php" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
      <a href="calendar.php" class="nav-link"><i class="fas fa-calendar"></i> Calendrier</a>
      <a href="listeEvent.php" class="nav-link"><i class="fas fa-calendar-check"></i> Événements</a>
      <a href="#" class="nav-link"><i class="fas fa-users"></i> Participants</a>
      <a href="#" class="nav-link"><i class="fas fa-user"></i> Utilisateurs</a>
      <a href="#" class="nav-link"><i class="fas fa-comments"></i> Feed</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="app-main">
    <div class="container-fluid">
      <div class="d-flex justify-between align-center mb-4">
        <h1 class="h3">
          <i class="fas fa-plus-circle text-gradient"></i> Ajouter un Événement
        </h1>
        <a href="listeEvent.php" class="btn btn-outline-light">
          <i class="fas fa-arrow-left"></i> Retour
        </a>
      </div>

      <div class="form-container">
        <div class="form-section">
          <h2>
            <i class="fas fa-calendar-plus"></i>
            Informations de l'événement
          </h2>
          
          <form action="ajoutEvent.php" method="POST" id="eventForm">
            <div class="mb-3">
              <label for="titre" class="form-label">
                <i class="fas fa-heading"></i> Titre de l'événement *
              </label>
              <input type="text" class="form-control" id="titre" name="titre" required 
                     placeholder="Ex: Tournoi de Gaming 2025">
            </div>

            <div class="mb-3">
              <label for="description" class="form-label">
                <i class="fas fa-align-left"></i> Description *
              </label>
              <textarea class="form-control" id="description" name="description" rows="4" required
                        placeholder="Décrivez votre événement..."></textarea>
            </div>

            <div class="mb-3">
              <label for="lieu" class="form-label">
                <i class="fas fa-map-marker-alt"></i> Lieu * <small style="color:#aaa;">(Recherchez ou cliquez sur la carte)</small>
              </label>
              
              <!-- Search Bar -->
              <div class="search-container">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Rechercher une adresse (ex: Paris, France)">
                <button type="button" id="searchBtn">Rechercher</button>
              </div>

              <!-- Map Container -->
              <div id="map"></div>

              <!-- Coordinates Info -->
              <div class="coordinates-info" id="coordsInfo">
                📍 Coordonnées: <span id="coordsDisplay"></span>
              </div>

              <!-- Lieu Input (will be auto-filled) -->
              <input type="text" class="form-control mt-3" id="lieu" name="lieu" required
                     placeholder="Ex: Centre de Convention, Paris">

              <!-- Hidden fields for coordinates -->
              <input type="hidden" id="latitude" name="latitude">
              <input type="hidden" id="longitude" name="longitude">
            </div>

            <div class="form-row">
              <div>
                <label for="date" class="form-label">
                  <i class="fas fa-calendar"></i> Date *
                </label>
                <input type="date" class="form-control" id="date" name="date" required
                       value="<?= $prefilledDate ?>">
              </div>

              <div>
                <label for="statut" class="form-label">
                  <i class="fas fa-info-circle"></i> Statut *
                </label>
                <select class="form-select" id="statut" name="statut" required>
                  <option value="">Sélectionner un statut</option>
                  <option value="à venir" selected>À venir</option>
                  <option value="en cours">En cours</option>
                  <option value="terminé">Terminé</option>
                  <option value="annulé">Annulé</option>
                </select>
              </div>
            </div>

            <div class="form-row">
              <div>
                <label for="heure_deb" class="form-label">
                  <i class="fas fa-clock"></i> Heure de début *
                </label>
                <input type="time" class="form-control" id="heure_deb" name="heure_deb" required>
              </div>

              <div>
                <label for="heure_fin" class="form-label">
                  <i class="fas fa-clock"></i> Heure de fin *
                </label>
                <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
              </div>
            </div>

            <div class="btn-group-actions">
              <button type="submit" class="btn btn-success">
                <i class="fas fa-check"></i> Créer l'événement
              </button>
              <a href="listeEvent.php" class="btn btn-outline-light">
                <i class="fas fa-times"></i> Annuler
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Footer -->
  <footer class="app-footer">
    <strong>© 2025 GameAct Admin.</strong> Tous droits réservés.
    <span class="float-end d-none d-sm-inline">Version 1.0</span>
  </footer>

  <!-- Leaflet JS for Maps -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script>
    // Toggle sidebar on mobile
    document.addEventListener('DOMContentLoaded', function() {
      const sidebarToggle = document.getElementById('sidebarToggle');
      const sidebar = document.getElementById('sidebar');
      
      if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
          sidebar.classList.toggle('show');
        });
      }
      
      // Validate end time is after start time
      const startTime = document.getElementById('heure_deb');
      const endTime = document.getElementById('heure_fin');
      
      endTime.addEventListener('change', function() {
        if (startTime.value && endTime.value) {
          if (endTime.value <= startTime.value) {
            alert('L\'heure de fin doit être après l\'heure de début');
            endTime.value = '';
          }
        }
      });
    });

    // ========== MAP FUNCTIONALITY ==========
    
    // Initialize map centered on Paris by default
    let map = L.map('map').setView([48.8566, 2.3522], 13);
    let marker = null;

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '© OpenStreetMap contributors',
      maxZoom: 19
    }).addTo(map);

    // Function to add/update marker
    function addMarker(lat, lng, address = '') {
      if (marker) {
        map.removeLayer(marker);
      }

      marker = L.marker([lat, lng], {
        draggable: true
      }).addTo(map);

      marker.bindPopup(`<b>📍 Lieu sélectionné</b><br>${address || 'Lat: ' + lat.toFixed(4) + ', Lng: ' + lng.toFixed(4)}`).openPopup();

      // Update form fields
      document.getElementById('latitude').value = lat;
      document.getElementById('longitude').value = lng;

      // Show coordinates
      document.getElementById('coordsDisplay').textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
      document.getElementById('coordsInfo').style.display = 'block';

      // If address provided, update lieu field
      if (address) {
        document.getElementById('lieu').value = address;
      }

      // Handle marker drag
      marker.on('dragend', function (e) {
        const position = marker.getLatLng();
        reverseGeocode(position.lat, position.lng);
      });
    }

    // Click on map to place marker
    map.on('click', function (e) {
      const lat = e.latlng.lat;
      const lng = e.latlng.lng;
      reverseGeocode(lat, lng);
    });

    // Reverse geocoding (coordinates to address) using Photon API
    async function reverseGeocode(lat, lng) {
      try {
        // Try Photon API (more reliable than Nominatim, no rate limits)
        const response = await fetch(`https://photon.komoot.io/reverse?lat=${lat}&lon=${lng}&limit=1`);
        const data = await response.json();

        if (data.features && data.features.length > 0) {
          const props = data.features[0].properties;
          // Build address from components
          const parts = [];
          if (props.name) parts.push(props.name);
          if (props.street) parts.push(props.street);
          if (props.city) parts.push(props.city);
          if (props.state) parts.push(props.state);
          if (props.country) parts.push(props.country);

          const address = parts.length > 0 ? parts.join(', ') : `${lat.toFixed(4)}, ${lng.toFixed(4)}`;
          addMarker(lat, lng, address);
        } else {
          // Fallback: just show coordinates
          addMarker(lat, lng, `📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
        }
      } catch (error) {
        console.error('Reverse geocoding error:', error);
        // Fallback: just show coordinates
        addMarker(lat, lng, `📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}`);
      }
    }

    // Search functionality
    document.getElementById('searchBtn').addEventListener('click', searchLocation);
    document.getElementById('searchInput').addEventListener('keypress', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        searchLocation();
      }
    });

    async function searchLocation() {
      const query = document.getElementById('searchInput').value;
      if (!query) {
        alert('Veuillez entrer une adresse à rechercher');
        return;
      }

      try {
        // Use Open-Meteo Geocoding API (same as weather widget, no rate limits)
        const response = await fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(query)}&count=1&language=fr&format=json`);
        const data = await response.json();

        if (data.results && data.results.length > 0) {
          const result = data.results[0];
          const lat = result.latitude;
          const lng = result.longitude;
          const address = `${result.name}${result.admin1 ? ', ' + result.admin1 : ''}${result.country ? ', ' + result.country : ''}`;

          // Center map and add marker
          map.setView([lat, lng], 15);
          addMarker(lat, lng, address);
        } else {
          alert('Aucun résultat trouvé. Essayez une autre adresse.');
        }
      } catch (error) {
        console.error('Geocoding error:', error);
        alert('Erreur lors de la recherche. Veuillez réessayer.');
      }
    }

    // Get user's current location
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        map.setView([lat, lng], 13);
        // Optionally add a marker at current location
        // addMarker(lat, lng, 'Ma position');
      }, function (error) {
        console.log('Geolocation error:', error);
        // Keep default Paris location
      });
    }
  </script>

</body>
</html>
