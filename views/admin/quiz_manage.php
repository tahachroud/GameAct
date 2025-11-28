<?php
$pageTitle = "Manage Quiz - Admin Panel";
$page = 'admin_quiz_manage';

$customCSS = '
.manage-container {
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

.badge-deleted {
    background: #dc3545;
}

.action-buttons .btn {
    margin: 0 3px;
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
                    <div class="col-lg-8">
                        <h2 style="color: #fff; font-size: 32px; font-weight: 700;">
                            <i class="fa fa-cogs" style="color: #e84057;"></i> Manage Quiz
                        </h2>
                        <p style="color: #666;">View, edit, and manage all quiz</p>
                    </div>
                    <div class="col-lg-4 text-right">
                        <a href="index.php?page=quiz_create" class="btn btn-success" style="padding: 12px 25px; border-radius: 23px;">
                            <i class="fa fa-plus"></i> Create New Quiz
                        </a>
                    </div>
                </div>

                <!-- Success/Error Messages -->
                <?php if(isset($_GET['updated'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> Quiz updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(isset($_GET['deleted'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <strong>Deleted!</strong> Quiz has been deleted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- Quiz Table -->
                <div class="manage-container">
                    <?php if(empty($quizzes)): ?>
                        <p style="color: #666; text-align: center; padding: 50px;">No quiz available yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Creator</th>
                                        <th>Questions</th>
                                        <th>Completions</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($quizzes as $quiz): ?>
                                        <tr>
                                            <td>#<?php echo $quiz['id_quiz']; ?></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($quiz['titre']); ?></strong>
                                                <br>
                                                <small style="color: #666;"><?php echo htmlspecialchars(substr($quiz['description'], 0, 50)); ?>...</small>
                                            </td>
                                            <td><span class="badge-category"><?php echo strtoupper($quiz['categorie']); ?></span></td>
                                            <td><?php echo htmlspecialchars($quiz['createur'] ?? 'Unknown'); ?></td>
                                            <td><?php echo $quiz['nombre_questions']; ?></td>
                                            <td><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <span class="badge-status badge-<?php echo $quiz['statut']; ?>">
                                                    <?php echo ucfirst($quiz['statut']); ?>
                                                </span>
                                            </td>
                                            <td style="font-size: 12px; color: #666;">
                                                <?php echo date('M d, Y', strtotime($quiz['date_creation'])); ?>
                                            </td>
                                            <td class="action-buttons">
                                                <a href="index.php?page=quiz_play&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-info" title="Preview">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="index.php?page=admin_quiz_edit&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="index.php?page=admin_quiz_delete&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   title="Delete"
                                                   onclick="return confirm('Are you sure you want to delete this quiz?');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include 'views/admin/footer.php'; ?>