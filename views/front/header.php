<header class="gaming-header">
    <div class="container-header">

        <!-- LOGO -->
        <div class="logo">
            <span class="icon">🎮</span> 
            <span class="text">Tutoriels Gaming</span>
        </div>

        <!-- NAVIGATION -->
        <nav id="navMenu">
            <a href="router.php" class="nav-link">Accueil</a>
            <a href="router.php?action=index" class="nav-link">Tutoriels</a>
            <a href="#" class="nav-link">Catégories</a>
            <a href="#" class="nav-link">À propos</a>
        </nav>

        <!-- MENU MOBILE -->
        <div class="burger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </div>
</header>

<style>
/* 🔥 HEADER NEON GAMING */
.gaming-header {
    width: 100%;
    padding: 15px 0;
    background: rgba(10, 10, 15, 0.85);
    backdrop-filter: blur(10px);
    border-bottom: 2px solid #ff1177;
    box-shadow: 0 0 20px #ff117722;
    position: sticky;
    top: 0;
    z-index: 900;
}

/* CONTENU DU HEADER */
.container-header {
    width: 90%;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

/* 🕹️ LOGO */
.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 28px;
    color: #ff1188;
    font-weight: 900;
    text-shadow: 0 0 10px #ff1188;
}

.logo .icon {
    font-size: 34px;
}

/* 🔗 NAVIGATION */
nav {
    display: flex;
    gap: 35px;
}

.nav-link {
    color: #eee;
    font-weight: 500;
    font-size: 17px;
    text-decoration: none;
    transition: 0.3s;
    position: relative;
}

/* Effet neon hover */
.nav-link:hover {
    color: #ff1188;
    text-shadow: 0 0 8px #ff1188;
}

/* Burger menu (mobile) */
.burger {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 5px;
}
.burger span {
    width: 28px;
    height: 3px;
    background: #fff;
    border-radius: 5px;
    transition: 0.3s;
}

/* 📱 Version mobile */
@media (max-width: 900px) {

    nav {
        position: absolute;
        top: 70px;
        right: 0;
        background: #111;
        width: 200px;
        flex-direction: column;
        padding: 20px;
        border-left: 2px solid #ff1188;
        border-bottom: 2px solid #ff1188;
        display: none;
    }

    nav.open {
        display: flex;
    }

    .burger {
        display: flex;
    }
}
</style>

<script>
function toggleMenu() {
    document.getElementById("navMenu").classList.toggle("open");
}
</script>
