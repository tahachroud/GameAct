<?php
require_once "./model/Tutorial.php";

// Le code a été corrigé en fusionnant les deux déclarations de classe.
// Si votre classe étend une base "Controller", veuillez ajouter "extends Controller" ici.
// Par défaut, je laisse la structure simple que vous aviez.
class TutorialController {

    private $tutorial;

    public function __construct($db) {
        // Initialisation du modèle Tutorial
        $this->tutorial = new Tutorial($db);
    }

    
    public function index() {
        $data = $this->tutorial->getAll();
        include (__DIR__. "/../view/front/indextuto.php");
    }

    
    public function show($id) {
        $item = $this->tutorial->getById($id);
        include "./view/front/tutorial.php";
    }

    
    public function adminList() {
        $data = $this->tutorial->getAll();
        include "./view/back/listtutorials.php";
    }

    
    public function create() {
        if ($_POST) {

            
            if (isset($_POST["videoUrl"])) {
                $_POST["videoUrl"] = $this->convertYoutube($_POST["videoUrl"]);
            }

            $this->tutorial->create($_POST);
            header("Location: router.php?action=adminList");
            exit;
        }

        include "./view/back/add.php";
    }

    
    public function edit($id) {
        if ($_POST) {

            
            if (isset($_POST["videoUrl"])) {
                $_POST["videoUrl"] = $this->convertYoutube($_POST["videoUrl"]);
            }

            $this->tutorial->update($id, $_POST);
            header("Location: router.php?action=adminList");
            exit;
        }

        $item = $this->tutorial->getById($id);
        include "./view/back/edit.php";
    }

    
    public function delete($id) {
        $this->tutorial->delete($id);
        header("Location: router.php?action=adminList");
        exit;
    }

    /**
     * NOUVELLE MÉTHODE : Récupère la liste des tutoriels triés par nombre de likes (pour la page Rank).
     */
    public function getRankedTutorials() {
        // J'appelle une nouvelle méthode dans le modèle qui effectuera le tri SQL.
        // J'utilise le nom 'getAllRanked' par cohérence avec 'getAll'.
        return $this->tutorial->getAllRanked(); 
    }

    
    private function convertYoutube($url) {

        
        if (strpos($url, "watch?v=") !== false) {
            return str_replace("watch?v=", "embed/", $url);
        }

        
        if (strpos($url, "youtu.be/") !== false) {
            return str_replace("youtu.be/", "www.youtube.com/embed/", $url);
        }

        
        return $url;
    }

    /**
     * Point de terminaison AJAX pour la recherche en temps réel (Approche Côté Serveur - OPTIONNELLE)
     * NOTE: L'approche Côté Client (JavaScript) est recommandée et n'utilise pas cette méthode.
     * Cette méthode est fournie si vous souhaitez passer à une recherche Côté Serveur.
     */
    public function searchTutorials() {
        // 1. Vérifier la requête AJAX et POST (Sécurité)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            header('Content-Type: application/json');
            http_response_code(403);
            echo json_encode(['error' => 'Requête invalide ou non-AJAX.']);
            exit;
        }

        // 2. Validation et assainissement de l'entrée
        $searchTerm = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (!$searchTerm) {
            $searchTerm = '';
        }
        
        // 3. Exécuter la recherche en utilisant le modèle $this->tutorial
        try {
            // Utilisation de la propriété $this->tutorial déjà initialisée dans le constructeur.
            $tutorials = $this->tutorial->searchByTitle($searchTerm);

            // 4. Envoyer la réponse JSON
            header('Content-Type: application/json');
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'count' => count($tutorials),
                'tutorials' => $tutorials
            ]);
        } catch (\PDOException $e) {
            // 5. Gestion des erreurs de base de données (masquer les détails en production)
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => 'Erreur de base de données.']);
        }
        exit;
    }
}
// Le fichier se termine ici. N'ajoutez pas de balise de fermeture ?>