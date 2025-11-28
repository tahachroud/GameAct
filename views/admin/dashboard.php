<?php
$pageTitle = "Admin Dashboard - Gaming Quiz";
$page = 'admin_dashboard';

$customCSS = '
.stats-container {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 40px;
    margin: 30px 0;
}

.stat-card {
    background: #1f2122;
    border-radius: 23px;
    padding: 30px;
    text-align: center;
    transition: all 0.3s ease;
    border: 2px solid #333;
}

.stat-card:hover {
    transform: translateY(-5px);
    border-color: #e84057;
}

.stat-icon {
    font-size: 48px;
    color: #e84057;
    margin-bottom: 15px;
}

.stat-number {
    font-size: 42px;
    font-weight: 700;
    color: #fff;
    margin-bottom: 10px;
}

.stat-label {
    color: #666;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.recent-quiz-table {
    background: linear-gradient(to bottom, #1f2122 0%, #27292a 100%);
    border-radius: 23px;
    padding: 40px;
    margin: 30px 0;
}

.table {
    color: #fff;
}

.table thead th {
    border-color: #333;
    color: #e84057;
    font-weight: 600;
}

.table tbody td {
    border-color: #333;
    vertical-align: middle;
}

.badge-category {
    background: #e84057;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 12px;
}

.badge-status {
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 12px;
}

.badge-active {
    background: #28a745;
}

.badge-pending {
    background: #ffc107;
    color: #000;
}
';

include 'views/admin/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <!-- Page Header -->
                <div class="row" style="margin: 30px 0;">
                    <div class="col-lg-12">
                        <h2 style="color: #fff; font-size: 32px; font-weight: 700;">
                            <i class="fa fa-dashboard" style="color: #e84057;"></i> Admin Dashboard
                        </h2>
                        <p style="color: #666;">Overview of quiz platform statistics</p>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="stats-container">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-gamepad"></i></div>
                                <div class="stat-number"><?php echo $totalQuiz; ?></div>
                                <div class="stat-label">Total Quiz</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-question-circle"></i></div>
                                <div class="stat-number"><?php echo $totalQuestions; ?></div>
                                <div class="stat-label">Total Questions</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-check-circle"></i></div>
                                <div class="stat-number"><?php echo $totalCompletions; ?></div>
                                <div class="stat-label">Completions</div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-4">
                            <div class="stat-card">
                                <div class="stat-icon"><i class="fa fa-trophy"></i></div>
                                <div class="stat-number"><?php echo round($avgScore); ?>%</div>
                                <div class="stat-label">Avg Score</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Quiz Table -->
                <div class="recent-quiz-table">
                    <h4 style="color: #fff; margin-bottom: 30px;">
                        <i class="fa fa-list"></i> Recent Quiz
                    </h4>
                    
                    <?php if(empty($recentQuizzes)): ?>
                        <p style="color: #666; text-align: center; padding: 50px;">No quiz available yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Questions</th>
                                        <th>Completions</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($recentQuizzes as $quiz): ?>
                                        <tr>
                                            <td>#<?php echo $quiz['id_quiz']; ?></td>
                                            <td><strong><?php echo htmlspecialchars($quiz['titre']); ?></strong></td>
                                            <td><span class="badge-category"><?php echo strtoupper($quiz['categorie']); ?></span></td>
                                            <td><?php echo $quiz['nombre_questions']; ?></td>
                                            <td><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <span class="badge-status badge-<?php echo $quiz['statut']; ?>">
                                                    <?php echo ucfirst($quiz['statut']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="index.php?page=admin_quiz_edit&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="index.php?page=quiz_play&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-info">
                                                    <i class="fa fa-play"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Category Distribution -->
                <?php if(!empty($categoryCount)): ?>
                <div class="stats-container">
                    <h4 style="color: #fff; margin-bottom: 30px;">
                        <i class="fa fa-pie-chart"></i> Category Distribution
                    </h4>
                    <div class="row">
                        <?php foreach($categoryCount as $category => $count): ?>
                            <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                                <div style="background: #1f2122; padding: 20px; border-radius: 15px; text-align: center;">
                                    <div style="color: #e84057; font-size: 32px; font-weight: 700;"><?php echo $count; ?></div>
                                    <div style="color: #666; text-transform: uppercase; font-size: 14px;"><?php echo $category; ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>

<?php include 'views/admin/footer.php'; ?>