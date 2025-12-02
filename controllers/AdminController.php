<?php
require_once "./models/Tutorial.php";
require_once "./models/Feedback.php";

class AdminController {

    public function dashboard() {
        $tutorial = new Tutorial();
        $feedback = new Feedback();

        $totalTutorials = $tutorial->countAll();
        $totalFeedbacks = $feedback->countAll();
        $totalLikes = $feedback->sumLikes();

        require "./views/back/dashboard.php";
    }
}
