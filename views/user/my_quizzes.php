<?php
/**
 * User My Quizzes Management Page
 */
include 'views/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                
                <!-- Page Header -->
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4><em>My</em> Quizzes</h4>
                        </div>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">
                        ✓ Quiz updated successfully!
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['deleted'])): ?>
                    <div class="alert alert-success">
                        ✓ Quiz deleted successfully!
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        ✗ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Quizzes Table -->
                <div class="gaming-library">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4>Manage Your <em>Quizzes</em></h4>
                        </div>
                        
                        <?php if (empty($myQuizzes)): ?>
                            <div class="item" style="text-align: center; padding: 50px;">
                                <h5>You haven't created any quizzes yet.</h5>
                                <p style="margin-top: 20px;">
                                    <a href="index.php?page=quiz_create" class="main-border-button">
                                        Create Your First Quiz
                                    </a>
                                </p>
                            </div>
                        <?php else: ?>
                            
                            <table class="table table-dark table-striped" style="margin-top: 20px;">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
                                        <th>Questions</th>
                                        <th>Completions</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($myQuizzes as $quiz): ?>
                                        <tr>
                                            <td>
                                                <img src="assets/images/<?php echo htmlspecialchars($quiz['image_url']); ?>" 
                                                     alt="<?php echo htmlspecialchars($quiz['titre']); ?>"
                                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($quiz['titre']); ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-primary">
                                                    <?php echo ucfirst($quiz['categorie']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $difficultyColors = [
                                                    'easy' => 'success',
                                                    'medium' => 'warning',
                                                    'hard' => 'danger'
                                                ];
                                                $color = $difficultyColors[$quiz['difficulte']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?php echo $color; ?>">
                                                    <?php echo ucfirst($quiz['difficulte']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo $quiz['nombre_questions']; ?></td>
                                            <td><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'pending' => 'warning',
                                                    'deleted' => 'danger'
                                                ];
                                                $statusColor = $statusColors[$quiz['statut']] ?? 'secondary';
                                                ?>
                                                <span class="badge badge-<?php echo $statusColor; ?>">
                                                    <?php echo ucfirst($quiz['statut']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo date('M d, Y', strtotime($quiz['date_creation'])); ?></td>
                                            <td>
                                                <a href="index.php?page=user_edit_quiz&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit Quiz">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                
                                                <a href="index.php?page=user_delete_quiz&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this quiz?');"
                                                   title="Delete Quiz">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            
                        <?php endif; ?>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 10px;
    font-weight: 500;
}

.alert-success {
    background: linear-gradient(145deg, #28a745, #218838);
    color: white;
    border: 2px solid #1e7e34;
}

.alert-danger {
    background: linear-gradient(145deg, #dc3545, #c82333);
    color: white;
    border: 2px solid #bd2130;
}

.table {
    border-radius: 10px;
    overflow: hidden;
}

.table th {
    background: #e75e8d;
    color: white;
    font-weight: 600;
    padding: 15px;
    border: none;
}

.table td {
    padding: 12px;
    vertical-align: middle;
    border-color: #333;
}

.badge {
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    border-radius: 5px;
}

.badge-primary { background: #007bff; }
.badge-success { background: #28a745; }
.badge-warning { background: #ffc107; color: #000; }
.badge-danger { background: #dc3545; }
.badge-secondary { background: #6c757d; }

.btn-sm {
    padding: 5px 10px;
    font-size: 12px;
    border-radius: 5px;
    margin: 2px;
    display: inline-block;
}

.btn-warning {
    background: #ffc107;
    color: #000;
    border: none;
    font-weight: 600;
}

.btn-warning:hover {
    background: #e0a800;
    transform: scale(1.05);
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    font-weight: 600;
}

.btn-danger:hover {
    background: #c82333;
    transform: scale(1.05);
}
</style>

<?php
include 'views/footer.php';
?>