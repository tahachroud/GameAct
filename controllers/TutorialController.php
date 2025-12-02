<?php
require_once "./models/Tutorial.php";

class TutorialController {

    private $tutorial;

    public function __construct($db) {
        $this->tutorial = new Tutorial($db);
    }

    // FRONT — Affichage de tous les tutoriels
    public function index() {
        $data = $this->tutorial->getAll();
        include "./views/front/index.php";
    }

    // FRONT — Voir un tutoriel
    public function show($id) {
        $item = $this->tutorial->getById($id);
        include "./views/front/tutorial.php";
    }

    // BACK — Liste admin
    public function adminList() {
        $data = $this->tutorial->getAll();
        include "./views/back/list.php";
    }

    // BACK — Ajouter un tutoriel
    public function create() {
        if ($_POST) {

            // 🔥 Conversion automatique de l'URL YouTube
            if (isset($_POST["videoUrl"])) {
                $_POST["videoUrl"] = $this->convertYoutube($_POST["videoUrl"]);
            }

            $this->tutorial->create($_POST);
            header("Location: router.php?action=adminList");
            exit;
        }

        include "./views/back/add.php";
    }

    // BACK — Modifier un tutoriel
    public function edit($id) {
        if ($_POST) {

            // 🔥 Conversion automatique de l'URL YouTube
            if (isset($_POST["videoUrl"])) {
                $_POST["videoUrl"] = $this->convertYoutube($_POST["videoUrl"]);
            }

            $this->tutorial->update($id, $_POST);
            header("Location: router.php?action=adminList");
            exit;
        }

        $item = $this->tutorial->getById($id);
        include "./views/back/edit.php";
    }

    // BACK — Supprimer un tutoriel
    public function delete($id) {
        $this->tutorial->delete($id);
        header("Location: router.php?action=adminList");
        exit;
    }

    // 🔧 UTILITAIRE — Convertir automatiquement YouTube → embed
    private function convertYoutube($url) {

        // Cas 1 : URL classique → embed
        if (strpos($url, "watch?v=") !== false) {
            return str_replace("watch?v=", "embed/", $url);
        }

        // Cas 2 : URL courte youtu.be → embed
        if (strpos($url, "youtu.be/") !== false) {
            return str_replace("youtu.be/", "www.youtube.com/embed/", $url);
        }

        // Cas 3 : Déjà en embed → OK
        return $url;
    }
}
