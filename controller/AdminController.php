<?php
require_once "./model/Tutorial.php";
require_once "./model/Feedback.php";

class AdminController {

    public function dashboard() {
        $tutorial = new Tutorial();
        $feedback = new Feedback();

        $totalTutorials = $tutorial->countAll();
        $totalFeedbacks = $feedback->countAll();
        $totalLikes = $feedback->sumLikes();

        require "./view/back/dashboardtutorials.php";
    }
}
