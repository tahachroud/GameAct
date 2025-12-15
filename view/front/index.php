<!DOCTYPE html>
<html lang="fr">


<head>
    <meta charset="UTF-8">
    <title>Tutoriels Gaming</title>

    <link rel="stylesheet" href="public/assets/templatemo-cyborg-gaming.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 

    <style>

        body {
            background:#0f0f13;
        }

        h1 {
            text-align:center;
            margin-top:40px;
            margin-bottom:40px;
            color:#ff1177;
            font-size:45px;
            font-weight:900;
            text-shadow:0 0 20px #ff1177;
        }

        .card-gaming {
            background:#1b1c22;
            border-radius:12px;
            padding:18px;
            transition:0.35s;
            border:1px solid #2a2b32;
            box-shadow:0 0 10px rgba(255,0,90,0.1);
        }

        .card-gaming:hover {
            transform:scale(1.03);
            border-color:#ff1177;
            box-shadow:0 0 18px #ff1177;
        }

        .thumb iframe {
            width:100%;
            height:200px;
            border-radius:10px;
        }

        .tuto-title {
            color:#ff4c4c;
            margin-top:10px;
            font-size:20px;
            font-weight:bold;
            text-transform:capitalize;
        }

        .tuto-desc {
            color:#6aff6a;
            margin-top:6px;
            font-size:15px;
        }

        .btn-gaming {
            width:100%;
            border:1px solid #ff1177;
            color:#ff1177;
            margin-top:10px;
            transition:0.3s;
        }

        .btn-gaming:hover {
            background:#ff1177;
            color:white;
            box-shadow:0 0 12px #ff1177;
        }

        /* --- STYLES EXISTANTS POUR LA RECHERCHE --- */
        .tutorial-card {
            opacity: 1;
            max-height: 1000px;
            overflow: hidden;
            transition: opacity 0.4s ease-in-out, max-height 0.4s ease-in-out;
        }

        .tutorial-card.hidden-by-search {
            opacity: 0;
            max-height: 0;
            margin-bottom: 0; 
            padding-top: 0;
            padding-bottom: 0;
        }
        
        #no-results-message {
            display: none;
            color: #ff1177;
            padding: 40px;
            text-align: center;
            font-size: 1.8em;
            width: 100%;
        }
        /* ------------------------------------------------------------------------------------------------- */
        
        /* 🌟 NOUVEAUX STYLES POUR LA BARRE DE RECHERCHE (Effet Néon) 🌟 */
        
        /* Vous devez aussi ajouter ces styles à votre CSS principal si ce n'est pas fait */
        .header-search-container {
            max-width: 380px; 
            margin-right: 30px !important; 
        }

        .header-search-container .input-group-text {
            border-right: none;
            border-radius: 23px 0 0 23px;
            background-color: #1a1a1f; 
            border: 2px solid #363a3d;
            color: #ff1177; /* Accentuer l'icône */
        }
        
        .header-search-container .form-control {
            border-radius: 0 23px 23px 0; 
            border: 2px solid #363a3d; 
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.5); 
        }

        .header-search-container .form-control:focus {
            background-color: #2c2f31; 
            border-color: #ff1177; 
            /* OMBRE EFFET NÉON */
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
        }

        .btn-clear-search:hover {
            background-color: #ff1177; 
            color: #ffffff;
            border-color: #ff1177;
        }
        /* ------------------------------------------------------------------------------------------------- */

    </style>
</head>

<body>
<?php include "./view/front/header.php"; ?>   

<h1>Tutoriels Gaming – Explore & Apprends</h1>

<div class="container mt-4">
    <div class="row" id="tutorial-list-container">

        <?php while($t = $data->fetch()): ?>
        
        <div class="col-lg-4 col-md-6 mb-4 tutorial-card" data-title="<?= htmlspecialchars($t['title']) ?>">
            <div class="card-gaming">
                
                <div class="thumb">
                    <iframe src="<?= $t['videoUrl'] ?>" allowfullscreen></iframe>
                </div>

                <h4 class="tuto-title"><?= htmlspecialchars($t['title']) ?></h4>

                <p class="tuto-desc">
                    <?= htmlspecialchars(substr($t['content'], 0, 50)) ?>...
                </p>

                <a href="router.php?action=show&id=<?= $t['id'] ?>" 
                    class="btn btn-gaming">
                    Voir plus
                </a>

            </div>
        </div>

        <?php endwhile; ?>

        <div id="no-results-message" class="col-12 text-center">
            <i class="fa fa-frown-o"></i> Oups ! Aucun tutoriel trouvé.
        </div>
        
    </div>
</div>

<?php include "./view/front/footer.php"; ?>   

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('tutorial-search-input');
        const clearButton = document.getElementById('clear-search-btn');
        const tutorialCards = document.querySelectorAll('.tutorial-card'); 
        const noResultsMessage = document.getElementById('no-results-message');

        // ** 1. Fonction utilitaire de Debouncing **
        const debounce = (func, delay) => {
            let timeoutId;
            return function(...args) {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    func.apply(this, args);
                }, delay);
            };
        };

        // ** 2. Logique principale de recherche et de filtrage **
        const filterTutorials = () => {
            const searchTerm = searchInput.value.trim().toLowerCase();
            let resultsCount = 0;

            clearButton.style.display = searchTerm.length > 0 ? 'flex' : 'none';

            if (searchTerm === "") {
                tutorialCards.forEach(card => {
                    card.classList.remove('hidden-by-search');
                });
                noResultsMessage.style.display = 'none';
                return;
            }

            tutorialCards.forEach(card => {
                const title = card.getAttribute('data-title').toLowerCase();
                
                if (title.includes(searchTerm)) {
                    card.classList.remove('hidden-by-search');
                    resultsCount++;
                } else {
                    card.classList.add('hidden-by-search');
                }
            });

            if (resultsCount === 0) {
                noResultsMessage.style.display = 'block';
            } else {
                noResultsMessage.style.display = 'none';
            }
        };

        // ** 4. Écouteurs d'événements **

        // Déclencher la recherche avec Debounce (300ms) sur la saisie
        searchInput.addEventListener('input', debounce(filterTutorials, 300));

        // NOUVEL ÉCOUTEUR : Gérer la touche Entrée pour naviguer
        searchInput.addEventListener('keydown', (event) => {
            // Vérifie si la touche pressée est 'Enter'
            if (event.key === 'Enter') {
                event.preventDefault(); // Empêcher la soumission du formulaire par défaut
                
                // Trouver le premier tutoriel visible
                const firstVisibleCard = document.querySelector('.tutorial-card:not(.hidden-by-search)');

                if (firstVisibleCard) {
                    // Trouver le lien "Voir plus" à l'intérieur de cette carte
                    const link = firstVisibleCard.querySelector('.btn-gaming');
                    
                    if (link) {
                        // Rediriger l'utilisateur vers le lien de la première vidéo
                        window.location.href = link.href;
                    }
                }
            }
        });

        // Fonctionnalité du bouton d'effacement
        clearButton.addEventListener('click', () => {
            searchInput.value = ''; 
            filterTutorials();       
            searchInput.focus();     
        });
    });
</script>

</body>
</html>