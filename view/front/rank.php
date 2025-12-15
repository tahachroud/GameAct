<?php 
require './view/front/header.php'; 

// ⚠️ Simulation si la BDD n'est pas encore branchée
if (!isset($tutorials)) {
    $tutorials = [
        (object)[
            'id' => 1,
            'title' => 'FIFA 23 Gameplay (PC UHD)',
            'interactions_count' => 1500,
            'videoUrl' => 'https://www.youtube.com/embed/VIDEO_ID_1'
        ],
        (object)[
            'id' => 2,
            'title' => 'Assassin\'s Creed Valhalla',
            'interactions_count' => 950,
            'videoUrl' => 'https://www.youtube.com/embed/VIDEO_ID_2'
        ],
        (object)[
            'id' => 3,
            'title' => 'God Of War Ragnarök',
            'interactions_count' => 820,
            'videoUrl' => 'https://www.youtube.com/embed/VIDEO_ID_3'
        ],
    ];
}
?>

<!-- ===== STYLE GLOBAL + TROPHÉE ANIMÉ TOP 1 ===== -->
<style>
body {
  background:#0f0f13;
  color:#fff;
}

/* TITRE */
.rank-title {
  text-align:center;
  margin:60px 0 10px;
  color:#ff1177;
  font-size:45px;
  font-weight:900;
  text-shadow:0 0 20px #ff1177;
}

.rank-subtitle {
  text-align:center;
  color:#aaa;
  margin-bottom:50px;
}

/* CARTE VIDÉO */
.rank-card {
  background:#1b1c22;
  border-radius:14px;
  padding:18px;
  border:1px solid #2a2b32;
  box-shadow:0 0 12px rgba(255,0,90,0.15);
  transition:0.35s;
  position:relative;
}

.rank-card:hover {
  transform:scale(1.03);
  border-color:#ff1177;
  box-shadow:0 0 20px #ff1177;
}

.rank-card iframe {
  width:100%;
  height:200px;
  border-radius:12px;
}

/* TITRE VIDÉO */
.rank-card h4 {
  color:#ff4c4c;
  margin-top:12px;
  font-weight:bold;
}

/* INTERACTIONS */
.rank-interactions {
  color:#ffd700;
  font-weight:bold;
  margin-top:5px;
}

/* BOUTON */
.rank-btn a {
  display:block;
  margin-top:15px;
  text-align:center;
  padding:10px;
  border-radius:8px;
  border:1px solid #ff1177;
  color:#ff1177;
  transition:0.3s;
  text-decoration:none;
}

.rank-btn a:hover {
  background:#ff1177;
  color:#fff;
  box-shadow:0 0 12px #ff1177;
}

/* BADGE RANK */
.rank-badge {
  position:absolute;
  top:10px;
  left:10px;
  padding:6px 14px;
  font-weight:900;
  border-radius:6px;
  color:#111;
}

/* ===== ANIMATION TOP 1 ===== */
@keyframes glow {
  0% { box-shadow: 0 0 15px #ffd700; }
  50% { box-shadow: 0 0 35px #ffd700, 0 0 60px #ff1177; }
  100% { box-shadow: 0 0 15px #ffd700; }
}

@keyframes badgePulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.15); }
  100% { transform: scale(1); }
}

.top1 {
  border: 3px solid #ffd700 !important;
  animation: glow 2s infinite;
}

.top1 .rank-badge {
  animation: badgePulse 1.5s infinite;
  background: linear-gradient(45deg, #ffd700, #ff1177);
  color: #111;
}

/* ===== TROPHÉE ANIMÉ SANS ICÔNE ===== */
.top1::before {
  content: "MOST INTERACTED";
  position: absolute;
  top: -22px;
  left: 50%;
  transform: translateX(-50%);
  padding: 6px 18px;
  font-size: 13px;
  font-weight: 900;
  letter-spacing: 2px;
  color: #111;
  background: linear-gradient(45deg, #ffd700, #ff1177);
  border-radius: 20px;
  box-shadow: 0 0 15px #ffd700;
  animation: trophyFloat 2s infinite ease-in-out;
  z-index: 20;
}

@keyframes trophyFloat {
  0% {
    transform: translateX(-50%) translateY(0);
    box-shadow: 0 0 15px #ffd700;
  }
  50% {
    transform: translateX(-50%) translateY(-6px);
    box-shadow: 0 0 30px #ffd700, 0 0 50px #ff1177;
  }
  100% {
    transform: translateX(-50%) translateY(0);
    box-shadow: 0 0 15px #ffd700;
  }
}
</style>

<!-- ===== CONTENU ===== -->

<h1 class="rank-title">Classement des Tutoriels</h1>
<p class="rank-subtitle">
Les vidéos les plus populaires selon les interactions
</p>

<div class="container">
  <div class="row">

<?php
$rank = 0;
foreach ($tutorials as $tutorial):
    $rank++;

    if ($rank === 1) $color = '#FFD700';
    elseif ($rank === 2) $color = '#C0C0C0';
    elseif ($rank === 3) $color = '#CD7F32';
    else $color = '#ff1177';
?>

    <div class="col-lg-4 col-md-6 mb-4">
      <div class="rank-card <?= $rank === 1 ? 'top1' : '' ?>">

        <span class="rank-badge" style="background:<?= $color ?>;">
          #<?= $rank ?>
        </span>

        <iframe src="<?= $tutorial->videoUrl ?>" allowfullscreen></iframe>

        <h4><?= htmlspecialchars($tutorial->title) ?></h4>

        <div class="rank-interactions">
           <?= number_format($tutorial->interactions_count) ?> interactions
        </div>

        <div class="rank-btn">
          <a href="router.php?action=show&id=<?= $tutorial->id ?>">
            Voir plus
          </a>
        </div>

      </div>
    </div>

<?php endforeach; ?>

  </div>
</div>

<?php require './view/front/footer.php'; ?>
