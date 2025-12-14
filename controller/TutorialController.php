<?php
require_once "./model/Tutorial.php";

class TutorialController {

    private $tutorial;

    public function __construct($db) {
        $this->tutorial = new Tutorial($db);
    }

    
    public function index() {
        $data = $this->tutorial->getAll();
        include (__DIR__. "/../view/front/index.php");
    }

    
    public function show($id) {
        $item = $this->tutorial->getById($id);
        include "./view/front/tutorial.php";
    }

    
    public function adminList() {
        $data = $this->tutorial->getAll();
        include "./view/back/list.php";
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

    
    private function convertYoutube($url) {

        
        if (strpos($url, "watch?v=") !== false) {
            return str_replace("watch?v=", "embed/", $url);
        }

        
        if (strpos($url, "youtu.be/") !== false) {
            return str_replace("youtu.be/", "www.youtube.com/embed/", $url);
        }

       
        return $url;
    }
}
