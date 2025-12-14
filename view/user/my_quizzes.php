<?php
include 'view/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">
                
                <div class="row" style="margin-bottom: 30px;">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4><em>My</em> Quizzes</h4>
                        </div>
                    </div>
                </div>

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

                <div class="gaming-library">
                    <div class="col-lg-12">
                        <div class="heading-section">
                            <h4>Manage Your <em>Quizzes</em></h4>
                        </div>
                        
                        <?php if (empty($myQuizzes)): ?>
                            <div class="item" style="text-align: center; padding: 50px;">
                                <h5>You haven't created any quizzes yet.</h5>
                                <p style="margin-top: 20px;">
                                    <a href="quiz_list.php?page=quiz_create" class="main-border-button">
                                        Create Your First Quiz
                                    </a>
                                </p>
                            </div>
                        <?php else: ?>
                            
                            <table class="table table-dark table-striped" id="myQuizzesTable" style="margin-top: 20px;">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
                                        <th>Questions</th>
                                        <th>Duration</th>
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
                                            <td>
                                                <?php 
                                                $durationSeconds = 180 + (max(0, $quiz['nombre_questions'] - 8) * 15);
                                                $durationMinutes = ceil($durationSeconds / 60);
                                                echo $durationMinutes . ' min';
                                                ?>
                                            </td>
                                            <td><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <?php
                                                $statusColors = [
                                                    'active' => 'success',
                                                    'pending' => 'warning',
                                                    'inactive' => 'warning',
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
                                                <a href="quiz_list.php?page=user_edit_quiz&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Edit Quiz">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                
                                                <a href="quiz_list.php?page=user_delete_quiz&id=<?php echo $quiz['id_quiz']; ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Are you sure you want to delete this quiz?');"
                                                   title="Delete Quiz">
                                                    <i class="fa fa-trash"></i>
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
    background: #e75e8d !important;
    color: white !important;
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

.dataTables_wrapper {
    padding: 20px;
    background: linear-gradient(145deg, #27292a, #1f2122);
    border-radius: 15px;
    margin-top: 20px;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter,
.dataTables_wrapper .dataTables_info,
.dataTables_wrapper .dataTables_paginate {
    color: #fff !important;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    background: #1f2122;
    border: 2px solid #e75e8d;
    color: #fff;
    padding: 8px 15px;
    border-radius: 10px;
    margin: 0 10px;
}

.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: #e84057;
    box-shadow: 0 0 10px rgba(232, 64, 87, 0.3);
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    color: #fff;
    font-weight: 500;
    margin-right: 10px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: linear-gradient(145deg, #27292a, #1f2122) !important;
    border: 2px solid #e75e8d !important;
    color: #fff !important;
    padding: 8px 15px !important;
    margin: 0 5px !important;
    border-radius: 10px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    background: linear-gradient(145deg, #e75e8d, #d64077) !important;
    border-color: #c4356a !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(145deg, #e75e8d, #d64077) !important;
    border-color: #c4356a !important;
    color: #fff !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
    cursor: pointer;
    position: relative;
}

table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    position: absolute;
    right: 10px;
    color: #fff;
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
}

table.dataTable thead .sorting:after {
    content: "\f0dc";
    opacity: 0.5;
}

table.dataTable thead .sorting_asc:after {
    content: "\f0de";
}

table.dataTable thead .sorting_desc:after {
    content: "\f0dd";
}

.dataTables_wrapper .dataTables_info {
    padding-top: 15px;
    color: #aaa !important;
}
</style>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable) {
        console.error('DataTables library not loaded!');
        return;
    }
    
    var table = $('#myQuizzesTable').DataTable({
        responsive: true,
        order: [[7, 'desc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        language: {
            search: "Search:",
            searchPlaceholder: "Search your quizzes...",
            lengthMenu: "Show _MENU_ quizzes",
            info: "Showing _START_ to _END_ of _TOTAL_ quizzes",
            infoEmpty: "No quizzes found",
            infoFiltered: "(filtered from _MAX_ total)",
            zeroRecords: "No matching quizzes found",
            emptyTable: "You haven't created any quizzes yet",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next »",
                previous: "« Previous"
            }
        },
        columnDefs: [
            { orderable: false, targets: [0, 8] },
            { className: "text-center", targets: [4, 5] }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        initComplete: function() {
            console.log('DataTables initialized successfully!');
        }
    });
    
    $('#myQuizzesTable_filter input').attr('placeholder', 'Search quizzes...');
});
</script>

<?php
include 'view/footer.php';
?>