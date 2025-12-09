# 🌦️ Intégration Météo - Open-Meteo API

## ✅ Configuration Terminée!

Votre widget météo est **déjà configuré et fonctionnel** ! 🎉

### Aucune clé API requise ✨

Contrairement à d'autres services météo, **Open-Meteo est 100% gratuit** et ne nécessite **aucune inscription** ni clé API. Le widget fonctionne immédiatement!

---

## 🌟 Fonctionnalités

| Fonctionnalité | Description |
|----------------|-------------|
| **API Gratuite** | Aucune clé API requise, requêtes illimitées |
| **Détection Intelligente** | Cache automatiquement la météo pour les événements en ligne |
| **Géocodage** | Convertit les noms de villes en coordonnées GPS |
| **Multilingue** | Support du français et autres langues |
| **Gestion d'Erreurs** | Affichage gracieux en cas de données manquantes |
| **Design Moderne** | Interface élégante avec emojis météo |
| **Prévisions 16 jours** | Données disponibles jusqu'à 16 jours à l'avance |

---

## 📋 Ce qui est affiché

Le widget météo affiche automatiquement:

### 🌡️ Données Principales
- **Température moyenne** (calculée à partir des min/max)
- **Description météo** avec emoji (☀️ Ciel dégagé, 🌧️ Pluie, ❄️ Neige, etc.)

### 📊 Données Détaillées
- **🔽 Température minimale** de la journée
- **🔼 Température maximale** de la journée
- **📍 Localisation** exacte (nom de la ville trouvée)

---

## 🎯 Détection Automatique des Événements en Ligne

Le widget se cache automatiquement si l'événement est en ligne. Il détecte les mots-clés suivants dans le lieu:

- `zoom`
- `en ligne`
- `online`
- `webinar`
- `teams`
- `google meet`
- `discord`
- `skype`
- `virtual`
- `remote`
- `internet`
- `visio`

**Exemple:** Si le lieu est "Zoom Meeting" ou "En ligne", la météo ne s'affichera pas.

---

## 🎨 Design et Thème

Le widget est stylisé pour correspondre au thème **GameAct** de votre site:

- **Couleur principale:** `#e94560` (rouge/rose)
- **Fond:** Semi-transparent avec bordure
- **Icônes:** Emojis météo + FontAwesome
- **Design:** Responsive et moderne

---

## 🔧 Comment ça fonctionne

### 1. Géocodage (Ville → Coordonnées)
```
Lieu de l'événement: "Paris"
    ↓
API Geocoding Open-Meteo
    ↓
Coordonnées: lat=48.8534, lon=2.3488
```

### 2. Récupération Météo
```
Coordonnées GPS + Date de l'événement
    ↓
API Forecast Open-Meteo
    ↓
Données: Temp min/max, Code météo
```

### 3. Affichage
```
Données météo
    ↓
Conversion en emoji + Formatage
    ↓
Affichage dans le widget
```

---

## 📚 Codes Météo

| Code | Condition | Emoji |
|------|-----------|-------|
| 0 | Ciel dégagé | ☀️ |
| 1-3 | Partiellement nuageux | ⛅ |
| 45-48 | Brouillard | 🌫️ |
| 51-67 | Pluie | 🌧️ |
| 71-77 | Neige | ❄️ |
| 95+ | Orage | ⛈️ |

[Liste complète des codes WMO](https://open-meteo.com/en/docs)

---

## 🐛 Dépannage

### La météo ne s'affiche pas?

**Vérifications:**

1. **Ouvrez la console du navigateur** (F12) pour voir les erreurs
2. **Vérifiez le lieu de l'événement:**
   - Est-ce un nom de ville valide?
   - Essayez "Paris, France" au lieu de juste "Paris"
3. **Vérifiez la date:**
   - Format: YYYY-MM-DD
   - Dans les 16 prochains jours?
4. **Vérifiez si c'est un événement en ligne:**
   - Le widget se cache automatiquement pour les événements virtuels

### Message: "Lieu non trouvé"

**Solutions:**
- Utilisez des noms de villes reconnus (grandes villes)
- Ajoutez le pays: "Tunis, Tunisie" au lieu de "Tunis"
- Évitez les adresses complètes, préférez le nom de la ville

### Message: "Données météo non disponibles"

**Causes possibles:**
- Date trop éloignée (>16 jours)
- Format de date incorrect
- Problème de connexion à l'API

**Solutions:**
- Vérifiez que la date est au format YYYY-MM-DD
- Assurez-vous que l'événement est dans les 16 prochains jours
- Vérifiez votre connexion Internet

---

## 🎨 Personnalisation

### Changer les Couleurs

Dans `detail.php`, modifiez les styles du widget:

```css
/* Changer la couleur principale */
style="background:rgba(255,255,255,0.05); border:2px solid rgba(147,51,234,0.3);"

/* Gradient violet */
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Gradient rose */
background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
```

### Ajouter Plus de Données Météo

Vous pouvez ajouter d'autres paramètres météo:

```javascript
// Dans l'URL de l'API, ajoutez:
&daily=weathercode,temperature_2m_max,temperature_2m_min,precipitation_sum,windspeed_10m_max

// Puis accédez aux données:
const precipitation = weatherData.daily.precipitation_sum[0];
const windSpeed = weatherData.daily.windspeed_10m_max[0];

// Affichez-les:
<p>💧 Précipitations: ${precipitation}mm</p>
<p>💨 Vent: ${windSpeed}km/h</p>
```

### Changer l'Unité de Température

Pour afficher en Fahrenheit:

```javascript
// Modifiez l'URL de l'API:
const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&daily=weathercode,temperature_2m_max,temperature_2m_min&temperature_unit=fahrenheit&timezone=auto&start_date=${targetDate}&end_date=${targetDate}`;

// Changez l'affichage:
${avgTemp}°F
```

---

## 📖 Documentation API

### API de Géocodage

**Endpoint:** `https://geocoding-api.open-meteo.com/v1/search`

**Exemple:**
```
https://geocoding-api.open-meteo.com/v1/search?name=Paris&count=1&language=fr&format=json
```

**Réponse:**
```json
{
  "results": [
    {
      "id": 2988507,
      "name": "Paris",
      "latitude": 48.85341,
      "longitude": 2.3488,
      "country": "France"
    }
  ]
}
```

### API de Prévisions Météo

**Endpoint:** `https://api.open-meteo.com/v1/forecast`

**Exemple:**
```
https://api.open-meteo.com/v1/forecast?latitude=48.8534&longitude=2.3488&daily=weathercode,temperature_2m_max,temperature_2m_min&timezone=auto&start_date=2024-12-25&end_date=2024-12-25
```

**Réponse:**
```json
{
  "daily": {
    "time": ["2024-12-25"],
    "weathercode": [0],
    "temperature_2m_max": [15.2],
    "temperature_2m_min": [8.5]
  }
}
```

---

## 🔗 Liens Utiles

- [Documentation Open-Meteo](https://open-meteo.com/en/docs)
- [API de Géocodage](https://open-meteo.com/en/docs/geocoding-api)
- [Référence des Codes Météo](https://www.nodc.noaa.gov/archive/arc0021/0002199/1.1/data/0-data/HTML/WMO-CODE/WMO4677.HTM)
- [Fetch API MDN](https://developer.mozilla.org/fr/docs/Web/API/Fetch_API)

---

## ✨ Avantages d'Open-Meteo

✅ **Gratuit** - Aucun frais, aucune limite
✅ **Sans clé API** - Fonctionne immédiatement
✅ **Fiable** - Données de NOAA, DWD, Météo-France
✅ **Rapide** - Réponses en millisecondes
✅ **Open Source** - Code transparent
✅ **CORS activé** - Fonctionne depuis le navigateur

---

## 📝 Licence

Cette intégration utilise l'**API Open-Meteo** qui est gratuite pour un usage non commercial.

Pour un usage commercial, consultez: https://open-meteo.com/en/pricing

---

## 🎉 Prêt à l'emploi!

Votre widget météo est **déjà fonctionnel** ! Visitez simplement votre page de détails d'événement:

```
http://localhost/events/view/front-office/events/front/detail.php?id=5
```

La météo s'affichera automatiquement selon la date et le lieu de l'événement! 🌤️

---

**Bon développement! 🚀**
