<?php
// Include the necessary eventC.php file
require_once(__DIR__ . '/../../../../controller/eventC.php');

// Get event ID from URL
$eventId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Create an instance of EventC class
$eventController = new EventC();

// Fetch the event details
$event = null;
if ($eventId > 0) {
    try {
        $event = $eventController->showEvent($eventId);
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Function to format date in French
function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    if ($timestamp === false) return $date;
    
    $months = [
        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
        5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
        9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
    ];
    
    $day = date('d', $timestamp);
    $month = $months[(int)date('m', $timestamp)];
    $year = date('Y', $timestamp);
    
    return $day . ' ' . $month . ' ' . $year;
}

// Function to format date range
function formatDateRange($date, $heure_deb, $heure_fin) {
    $formatted = formatDate($date);
    if (!empty($heure_deb) && strtotime($heure_deb) !== false) {
        $formatted .= ' de ' . date('H:i', strtotime($heure_deb));
        if (!empty($heure_fin) && strtotime($heure_fin) !== false) {
            $formatted .= ' à ' . date('H:i', strtotime($heure_fin));
        }
    }
    return $formatted;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>GameAct - Détail Événement</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../../assets/css/fontawesome.css">
  <link rel="stylesheet" href="../../assets/css/templatemo-cyborg-gaming.css">
  <link rel="stylesheet" href="events-custom.css">
  <link rel="stylesheet" href="../../../../public/assets/css/moving-bg.css">
</head>
<body>

  <div class="moving-bg"></div>

  <div id="js-preloader" class="js-preloader">
    <div class="preloader-inner">
      <span class="dot"></span>
      <div class="dots">
        <span></span>
        <span></span>
        <span></span>
      </div>
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
              <li><a href="index.php" class="active">Events</a></li>
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

  <!-- CONTENU -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-content">

          <div class="mb-4">
            <a href="index.php" class="btn-back">
              <i class="fa fa-arrow-left"></i> Retour aux événements
            </a>
          </div>

          <?php if (!$event): ?>
            <!-- Event not found -->
            <div class="text-center" style="color:#ccc; padding:3rem;">
              <h2 style="color:#e94560; margin-bottom:1rem;">Événement introuvable</h2>
              <p style="font-size:1.2rem;">L'événement que vous recherchez n'existe pas ou a été supprimé.</p>
              <a href="index.php" class="btn-primary" style="display:inline-block; margin-top:2rem;">Retour à la liste</a>
            </div>
          <?php else: ?>
            <!-- Event details -->
            <div class="text-center mb-5">
              <h1 style="color:#e94560; font-size:2.5rem;"><?= htmlspecialchars($event['titre']); ?></h1>
            </div>

            <div class="event-info text-center mb-4" style="background:rgba(255,255,255,0.05); padding:2rem; border-radius:20px; border:2px solid rgba(233,69,96,0.3);">
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-calendar" style="color:#e94560;"></i> 
                <strong><?= formatDateRange($event['date'], $event['heure_deb'], $event['heure_fin']); ?></strong>
              </p>
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-map-marker" style="color:#e94560;"></i> 
                <strong><?= htmlspecialchars($event['lieu']); ?></strong>
              </p>
              <p style="margin:1rem 0; font-size:1.1rem;">
                <i class="fa fa-info-circle" style="color:#e94560;"></i> 
                <strong>Statut : <?= htmlspecialchars($event['statut']); ?></strong>
              </p>
            </div>

            <div class="text-center mb-4" style="background:rgba(255,255,255,0.05); padding:2rem; border-radius:20px; border:2px solid rgba(233,69,96,0.3);">
              <h3 style="color:#e94560; margin-bottom:1rem;">Description</h3>
              <p style="font-size:1.1rem; color:#ccc; max-width:800px; margin:0 auto; line-height:1.8;">
                <?= nl2br(htmlspecialchars($event['description'])); ?>
              </p>
            </div>

            <!-- Weather Widget -->
            <div id="weather-widget" class="text-center mb-4" style="background:rgba(255,255,255,0.05); padding:2rem; border-radius:20px; border:2px solid rgba(233,69,96,0.3); display:none;">
              <h3 style="color:#e94560; margin-bottom:1.5rem;">
                <i class="fa fa-cloud" style="margin-right:10px;"></i>Prévisions Météo
              </h3>
              <div id="weather-loading" style="color:#ccc; font-size:1.1rem;">
                <i class="fa fa-spinner fa-spin"></i> Chargement des prévisions météo...
              </div>
              <div id="weather-content" style="display:none;">
                <div class="row justify-content-center">
                  <div class="col-md-8">
                    <div style="background:rgba(233,69,96,0.1); padding:1.5rem; border-radius:15px; margin-bottom:1rem;">
                      <div style="display:flex; align-items:center; justify-content:center; gap:2rem; flex-wrap:wrap;">
                        <div style="text-align:center;">
                          <img id="weather-icon" src="" alt="Weather" style="width:80px; height:80px;">
                        </div>
                        <div style="text-align:left;">
                          <div id="weather-temp" style="font-size:3rem; color:#e94560; font-weight:bold; line-height:1;">--°C</div>
                          <div id="weather-description" style="font-size:1.2rem; color:#fff; text-transform:capitalize; margin-top:0.5rem;">--</div>
                        </div>
                      </div>
                    </div>
                    <div class="row" style="margin-top:1.5rem;">
                      <div class="col-4">
                        <div style="background:rgba(255,255,255,0.05); padding:1rem; border-radius:10px;">
                          <i class="fa fa-tint" style="color:#e94560; font-size:1.5rem;"></i>
                          <div style="margin-top:0.5rem; color:#ccc; font-size:0.9rem;">Humidité</div>
                          <div id="weather-humidity" style="color:#fff; font-size:1.2rem; font-weight:bold; margin-top:0.3rem;">--%</div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div style="background:rgba(255,255,255,0.05); padding:1rem; border-radius:10px;">
                          <i class="fa fa-wind" style="color:#e94560; font-size:1.5rem;"></i>
                          <div style="margin-top:0.5rem; color:#ccc; font-size:0.9rem;">Vent</div>
                          <div id="weather-wind" style="color:#fff; font-size:1.2rem; font-weight:bold; margin-top:0.3rem;">-- km/h</div>
                        </div>
                      </div>
                      <div class="col-4">
                        <div style="background:rgba(255,255,255,0.05); padding:1rem; border-radius:10px;">
                          <i class="fa fa-eye" style="color:#e94560; font-size:1.5rem;"></i>
                          <div style="margin-top:0.5rem; color:#ccc; font-size:0.9rem;">Ressenti</div>
                          <div id="weather-feels" style="color:#fff; font-size:1.2rem; font-weight:bold; margin-top:0.3rem;">--°C</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div id="weather-error" style="display:none; color:#e94560; font-size:1.1rem;">
                <i class="fa fa-exclamation-triangle"></i> Impossible de charger les prévisions météo
              </div>
            </div>

            <div class="text-center mb-5">
              <a href="participation.php?id=<?= $eventId ?>" class="btn-primary">S'inscrire maintenant</a>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="mt-5 py-4" style="background:#0f0f1e; border-top:1px solid #333;">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 text-center">
          <p style="color:#aaa; font-size:0.9rem; margin:0;">
            Copyright © 2025 <a href="#" style="color:#e94560; text-decoration:none;">GameAct</a>. Tous droits réservés.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="../../assets/js/custom.js"></script>
  
  <?php if ($event): ?>
  <!-- Weather API Script - Open-Meteo (Free, No API Key Required) -->
  <script>
    document.addEventListener('DOMContentLoaded', async () => {
      // Event data from PHP
      const eventDate = <?= json_encode($event['date']); ?>;
      const eventLocation = <?= json_encode($event['lieu']); ?>;
      
      const weatherWidget = document.getElementById('weather-widget');
      const weatherLoading = document.getElementById('weather-loading');
      const weatherContent = document.getElementById('weather-content');
      const weatherError = document.getElementById('weather-error');

      // Validation
      if (!eventDate || !eventLocation || !weatherWidget) {
        if (weatherWidget) weatherWidget.style.display = 'none';
        return;
      }

      // Hide weather for online events
      const onlineKeywords = ['zoom', 'en ligne', 'online', 'webinar', 'teams', 'google meet', 
                              'discord', 'skype', 'virtual', 'remote', 'internet', 'visio'];
      const isOnline = onlineKeywords.some(kw => eventLocation.toLowerCase().includes(kw));
      
      if (isOnline) {
        weatherWidget.style.display = 'none';
        return;
      }

      // Show widget
      weatherWidget.style.display = 'block';

      // Weather code to description mapping with emojis
      const getWeatherDescription = (code) => {
        if (code === 0) return "☀️ Ciel dégagé";
        if (code >= 1 && code <= 3) return "⛅ Partiellement nuageux";
        if (code >= 45 && code <= 48) return "🌫️ Brouillard";
        if (code >= 51 && code <= 67) return "🌧️ Pluie";
        if (code >= 71 && code <= 77) return "❄️ Neige";
        if (code >= 95) return "⛈️ Orage";
        return "🌤️ Météo variable";
      };

      // Geocoding function using Open-Meteo with smart city extraction
      const findCityCoordinates = async (locationStr) => {
        try {
          // Helper function to try geocoding
          const tryGeocode = async (searchTerm) => {
            if (!searchTerm || searchTerm.length < 2) return null;
            const url = `https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(searchTerm)}&count=1&language=fr&format=json`;
            const req = await fetch(url);
            const data = await req.json();
            return (data.results && data.results.length > 0) ? data.results[0] : null;
          };

          // Attempt 1: Exact search
          let result = await tryGeocode(locationStr);
          if (result) return result;

          // If no comma, return null (can't extract city)
          if (!locationStr.includes(',')) return null;

          // Split by comma
          const parts = locationStr.split(',').map(p => p.trim());

          // Attempt 2: Try the first part (often the building/institution name, but worth trying)
          result = await tryGeocode(parts[0]);
          if (result) return result;

          // Attempt 3: Look for common city/region keywords and try those parts
          const cityKeywords = ['Tunis', 'Gouvernorat', 'Délégation', 'Ariana', 'Sfax', 'Sousse', 
                                'Bizerte', 'Gabès', 'Kairouan', 'Nabeul', 'Monastir', 'Ben Arous',
                                'Paris', 'Lyon', 'Marseille', 'Lille', 'Toulouse', 'Nice'];
          
          for (const part of parts) {
            // Check if this part contains a known city keyword
            for (const keyword of cityKeywords) {
              if (part.toLowerCase().includes(keyword.toLowerCase())) {
                result = await tryGeocode(part);
                if (result) return result;
                
                // Also try just the keyword itself
                result = await tryGeocode(keyword);
                if (result) return result;
              }
            }
          }

          // Attempt 4: Try parts from the end (usually country, then region, then city)
          // For "..., Tunis, Gouvernorat Tunis, 1080, Tunisie"
          // Try: Tunisie, then 1080, then Gouvernorat Tunis, then Tunis
          for (let i = parts.length - 1; i >= 0; i--) {
            const part = parts[i];
            // Skip postal codes (numbers only)
            if (/^\d+$/.test(part)) continue;
            
            result = await tryGeocode(part);
            if (result) return result;
          }

          // Attempt 5: Try combining last meaningful parts (e.g., "Tunis, Tunisie")
          if (parts.length >= 2) {
            for (let i = parts.length - 1; i >= 1; i--) {
              if (!/^\d+$/.test(parts[i]) && !/^\d+$/.test(parts[i-1])) {
                const combined = `${parts[i-1]}, ${parts[i]}`;
                result = await tryGeocode(combined);
                if (result) return result;
              }
            }
          }

          // Attempt 6: Extract text before postal code
          // "El Menzah 1, Hay Es Salem, Délégation Cité El Khadra, Tunis" from before "1080"
          const postalCodeIndex = parts.findIndex(p => /^\d{4,5}$/.test(p.trim()));
          if (postalCodeIndex > 0) {
            // Try the part just before the postal code
            result = await tryGeocode(parts[postalCodeIndex - 1]);
            if (result) return result;
          }
          
          return null;
        } catch (error) {
          console.error('Geocoding error:', error);
          return null;
        }
      };

      try {
        // Get coordinates from location name
        const geoResult = await findCityCoordinates(eventLocation);
        
        if (!geoResult) {
          weatherLoading.style.display = 'none';
          weatherError.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Lieu non trouvé pour l\'affichage météo.';
          weatherError.style.display = 'block';
          return;
        }

        const { latitude, longitude, name } = geoResult;

        // Get weather data from Open-Meteo API
        let targetDate = eventDate.split(' ')[0]; // Keep only date part (YYYY-MM-DD)
        
        const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&timezone=auto&start_date=${targetDate}&end_date=${targetDate}`;
        
        const wReq = await fetch(weatherUrl);
        const weatherData = await wReq.json();

        if (!weatherData.daily || !weatherData.daily.time || weatherData.daily.time.length === 0) {
          weatherLoading.style.display = 'none';
          weatherError.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Données météo non disponibles pour cette date.';
          weatherError.style.display = 'block';
          return;
        }

        const maxTemp = weatherData.daily.temperature_2m_max[0];
        const minTemp = weatherData.daily.temperature_2m_min[0];
        const weatherCode = weatherData.daily.weathercode[0];
        const desc = getWeatherDescription(weatherCode);
        const avgTemp = Math.round((maxTemp + minTemp) / 2);

        // Update UI with weather data
        weatherLoading.style.display = 'none';
        
        document.getElementById('weather-temp').textContent = avgTemp + '°C';
        document.getElementById('weather-description').textContent = desc;
        document.getElementById('weather-humidity').innerHTML = `🔽 ${Math.round(minTemp)}°C`;
        document.getElementById('weather-wind').innerHTML = `🔼 ${Math.round(maxTemp)}°C`;
        document.getElementById('weather-feels').innerHTML = `📍 ${name}`;
        
        // Set weather icon based on code
        const iconMap = {
          0: '☀️', 1: '🌤️', 2: '⛅', 3: '☁️',
          45: '🌫️', 48: '🌫️',
          51: '🌧️', 53: '🌧️', 55: '🌧️', 61: '🌧️', 63: '🌧️', 65: '🌧️',
          71: '❄️', 73: '❄️', 75: '❄️', 77: '❄️',
          95: '⛈️', 96: '⛈️', 99: '⛈️'
        };
        const emoji = iconMap[weatherCode] || '🌤️';
        
        // Create emoji icon element
        const iconElement = document.getElementById('weather-icon');
        iconElement.style.fontSize = '80px';
        iconElement.style.width = 'auto';
        iconElement.style.height = 'auto';
        iconElement.alt = desc;
        iconElement.outerHTML = `<div id="weather-icon" style="font-size:80px; line-height:1;">${emoji}</div>`;

        // Update labels for better clarity
        const humidityLabel = weatherContent.querySelector('.col-4:nth-child(1) > div > div:nth-child(2)');
        const windLabel = weatherContent.querySelector('.col-4:nth-child(2) > div > div:nth-child(2)');
        const feelsLabel = weatherContent.querySelector('.col-4:nth-child(3) > div > div:nth-child(2)');
        
        if (humidityLabel) humidityLabel.textContent = 'Min';
        if (windLabel) windLabel.textContent = 'Max';
        if (feelsLabel) feelsLabel.textContent = 'Lieu';

        weatherContent.style.display = 'block';

      } catch (e) {
        console.error("Weather Error:", e);
        weatherLoading.style.display = 'none';
        weatherError.innerHTML = '<i class="fa fa-exclamation-triangle"></i> Impossible de charger les prévisions météo.';
        weatherError.style.display = 'block';
      }
    });
  </script>
  <?php endif; ?>
</body>
</html>

