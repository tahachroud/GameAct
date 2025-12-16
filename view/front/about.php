<?php require './view/front/headertuto.php'; ?>

<style>
/* ================= BASE ================= */
body {
  background: radial-gradient(circle at top, #1a1a22, #0f0f13);
  color: #ddd;
  font-family: 'Poppins', sans-serif;
}

/* ================= TITRE ================= */
.page-title {
  text-align: center;
  margin: 90px 0 60px;
}

.page-title h1 {
  color: #ff1177;
  font-size: clamp(32px, 5vw, 48px);
  font-weight: 900;
  text-shadow: 0 0 25px #ff1177;
}

.page-title p {
  color: #bbb;
  font-size: 16px;
}

/* ================= SECTIONS ================= */
.section {
  background: linear-gradient(180deg, #1b1c22, #15161b);
  border-radius: 22px;
  padding: 35px;
  margin-bottom: 50px;
  border: 1px solid #2a2b32;
  box-shadow: 0 0 25px rgba(255,17,119,0.12);
  transition: 0.4s ease;
  position: relative;
  overflow: hidden;
}

/* Glow progressif */
.section::before {
  content: "";
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at var(--x,50%) var(--y,50%), rgba(255,17,119,0.18), transparent 60%);
  opacity: 0;
  transition: opacity 0.4s;
}

.section:hover::before {
  opacity: 1;
}

.section h3 {
  color: #ff1177;
  margin-bottom: 15px;
}

/* ================= FEATURES ================= */
.features {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 22px;
}

.feature {
  background: #1b1c22;
  border-radius: 18px;
  padding: 25px;
  border: 1px solid #2a2b32;
  box-shadow: 0 0 15px rgba(255,17,119,0.15);
  transition: 0.4s ease;
}

.feature h4 {
  color: #ff4c8b;
}

.feature:hover {
  transform: scale(1.06) rotate(-1deg);
  box-shadow: 0 0 35px rgba(255,17,119,0.6);
}

/* ================= TEAM ================= */
.team {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 25px;
}

.team-card {
  background: #1b1c22;
  border-radius: 22px;
  padding: 30px;
  text-align: center;
  border: 1px solid #2a2b32;
  box-shadow: 0 0 18px rgba(255,17,119,0.15);
  transition: 0.4s ease;
}

.team-card img {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  border: 2px solid #ff1177;
  margin-bottom: 15px;
}

.team-card h4 {
  color: #ff1177;
}

.team-card:hover {
  transform: translateY(-10px) scale(1.05);
  box-shadow: 0 0 40px rgba(255,17,119,0.7);
}

/* ================= SCROLL ANIMATION ================= */
.reveal {
  opacity: 0;
  transform: translateY(40px);
  transition: 0.8s ease;
}

.reveal.active {
  opacity: 1;
  transform: translateY(0);
}

/* ================= RESPONSIVE ================= */
@media (max-width: 768px) {
  .section {
    padding: 25px;
  }
}
</style>

<div class="container">

  <!-- TITRE -->
  <div class="page-title reveal">
    <h1>À propos de Tutoriels Gaming</h1>
    <p>Une plateforme pensée pour améliorer votre skill et votre performance</p>
  </div>

  <!-- MISSION -->
  <div class="section reveal">
    <h3> Notre mission</h3>
    <p>
      Tutoriels Gaming aide les joueurs à apprendre plus vite et jouer plus intelligemment.
      Nos contenus sont basés sur l’analyse des mécaniques de jeu, des stratégies avancées
      et des interactions communautaires.
    </p>
  </div>

  <!-- FEATURES -->
  <div class="section reveal">
    <h3> Ce que propose la plateforme</h3>
    <div class="features mt-4">
      <div class="feature">
        <h4>Tutoriels ciblés</h4>
        <p>Guides orientés performance.</p>
      </div>
      <div class="feature">
        <h4>Système de feedback</h4>
        <p>Likes et commentaires utiles.</p>
      </div>
      <div class="feature">
        <h4>Classement dynamique</h4>
        <p>Les meilleurs contenus mis en avant.</p>
      </div>
      <div class="feature">
        <h4>Expérience fluide</h4>
        <p>Rapide, claire et agréable.</p>
      </div>
    </div>
  </div>

 <!-- GAME ACT -->
<div class="section reveal">
  <h3> Game Act</h3>

  <div class="team mt-4">
    <div class="team-card" style="grid-column: 1 / -1;">
      <div style="
        width: 120px;
        height: 120px;
        margin: 0 auto 20px;
        border-radius: 50%;
        border: 2px solid #ff1177;
        box-shadow: 0 0 25px #ff1177;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 900;
        color: #ff1177;
        text-shadow: 0 0 15px #ff1177;
      ">
        GA
      </div>

      <h4 style="font-size: 24px;">Game Act</h4>

      <p class="small" style="max-width: 700px; margin: 15px auto; color:#ccc;">
        <strong>Game Act</strong> est une équipe passionnée par le jeu vidéo, la performance
        et l’analyse stratégique. Notre objectif est de transformer l’expérience
        des joueurs en proposant des contenus clairs, efficaces et orientés résultats.
        <br><br>
        Chaque décision, chaque tutoriel et chaque interaction est pensée pour
        aider les joueurs à progresser concrètement et durablement.
      </p>
    </div>
  </div>
</div>


<script>
/* ===== Scroll Animation ===== */
const reveals = document.querySelectorAll('.reveal');
const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('active');
    }
  });
}, { threshold: 0.15 });

reveals.forEach(r => observer.observe(r));

/* ===== Glow dynamique ===== */
document.querySelectorAll('.section').forEach(sec => {
  sec.addEventListener('mousemove', e => {
    const r = sec.getBoundingClientRect();
    sec.style.setProperty('--x', `${e.clientX - r.left}px`);
    sec.style.setProperty('--y', `${e.clientY - r.top}px`);
  });
});
</script>

<?php require './view/front/footertuto.php'; ?>
