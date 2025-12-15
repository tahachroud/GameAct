<header class="gaming-header">
    <div class="container-header">

        <div class="logo">
            <span class="icon"></span> 
            <span class="text">Tutoriels Gaming</span>
        </div>

        <nav id="navMenu">
            <a href="router.php" class="nav-link">Accueil</a>
            <a href="router.php?action=index" class="nav-link">Tutoriels</a>
            <a href="router.php?action=rank" class="nav-link">Rank</a>
            <a href="router.php?action=about" class="nav-link">À propos</a>
        </nav>

        <div class="burger" onclick="toggleMenu()">
            <span></span>
            <span></span>
            <span></span>
        </div>

    </div>
    
    <div class="header-search-container ml-auto mr-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text"><i class="fa fa-search"></i></span>
            </div>
            <input type="text" id="tutorial-search-input" class="form-control" placeholder="Rechercher des tutoriels..." aria-label="Rechercher des tutoriels">
            <div class="input-group-append">
                <button class="btn btn-clear-search" id="clear-search-btn" style="display: none;">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</header>

<style>

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


.container-header {
    width: 90%;
    margin: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}


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

.nav-link:hover {
    color: #ff1188;
    text-shadow: 0 0 8px #ff1188;
}

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

/* Styles pour la barre de recherche */
.header-search-container {
    max-width: 380px; 
    margin: 15px auto 0 auto; 
}

/* Styles Néon de la barre de recherche (répétés pour s'assurer qu'ils sont appliqués) */
.header-search-container .input-group-text {
    background-color: #1a1a1f; 
    color: #ff1177; 
    border: 2px solid #363a3d;
    border-right: none;
    border-radius: 23px 0 0 23px;
}

.header-search-container .form-control {
    background-color: #27292a;
    color: #ffffff;
    border: 2px solid #363a3d;
    border-radius: 0 23px 23px 0;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);
    padding: 0px 15px; 
    height: 46px; 
}

.header-search-container .form-control:focus {
    background-color: #2c2f31; 
    border-color: #ff1177; 
    box-shadow: 0 0 15px rgba(255, 17, 119, 0.6), 0 0 5px rgba(255, 255, 255, 0.2); 
    color: #ffffff;
}

.btn-clear-search {
    background: #1f2122;
    color: #ffffff;
    border: 2px solid #363a3d;
    border-left: none;
    border-top-right-radius: 23px;
    border-bottom-right-radius: 23px;
    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
    display: flex; 
    align-items: center;
    justify-content: center;
    padding: 0 15px; 
}

.btn-clear-search:hover {
    background-color: #ff1177; 
    color: #ffffff;
    border-color: #ff1177;
}

</style>

<script>
function toggleMenu() {
    document.getElementById("navMenu").classList.toggle("open");
}
</script>