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
    color: #fff !important;
    font-weight: 600;
    background: #e84057 !important;
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

.dataTables_wrapper {
    padding: 20px;
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
';

include 'views/admin/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page-content">

                <div class="row" style="margin: 30px 0;">
                    <div class="col-lg-8">
                        <h2 style="color: #fff; font-size: 32px; font-weight: 700;">
                            <i class="fa fa-cogs" style="color: #e84057;"></i> Manage Quiz
                        </h2>
                        <p style="color: #666;">View, edit, and manage all quizzes</p>
                    </div>
                    <div class="col-lg-4 text-right">
                        <a href="index.php?page=quiz_create" class="btn btn-success" style="padding: 12px 25px; border-radius: 23px;">
                            <i class="fa fa-plus"></i> Create New Quiz
                        </a>
                    </div>
                </div>

                <?php if(isset($_GET['updated'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="background: #28a745; color: white; border-radius: 10px; padding: 15px;">
                    <strong>Success!</strong> Quiz updated successfully!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(isset($_GET['deleted'])): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert" style="background: #17a2b8; color: white; border-radius: 10px; padding: 15px;">
                    <strong>Deleted!</strong> Quiz has been deleted.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="manage-container">
                    <?php if(empty($quizzes)): ?>
                        <p style="color: #666; text-align: center; padding: 50px;">No quizzes available yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-dark table-striped" id="quizManageTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Difficulty</th>
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
                                            <td>
                                                <?php
                                                $diffColors = ['easy' => '#28a745', 'medium' => '#ffc107', 'hard' => '#dc3545'];
                                                $diffColor = $diffColors[$quiz['difficulte']] ?? '#6c757d';
                                                ?>
                                                <span style="background: <?php echo $diffColor; ?>; padding: 5px 10px; border-radius: 5px; font-size: 12px; color: <?php echo $quiz['difficulte'] === 'medium' ? '#000' : '#fff'; ?>;">
                                                    <?php echo ucfirst($quiz['difficulte']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($quiz['createur'] ?? 'Unknown'); ?></td>
                                            <td class="text-center"><?php echo $quiz['nombre_questions']; ?></td>
                                            <td class="text-center"><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <select class="status-dropdown" 
                                                        data-quiz-id="<?php echo $quiz['id_quiz']; ?>"
                                                        onchange="updateQuizStatus(this)"
                                                        style="padding: 5px 10px; 
                                                               border-radius: 5px; 
                                                               border: 1px solid #444;
                                                               background: <?php echo $quiz['statut'] === 'active' ? '#4CAF50' : '#ff9800'; ?>;
                                                               color: white;
                                                               font-weight: bold;
                                                               cursor: pointer;">
                                                    <option value="active" <?php echo $quiz['statut'] === 'active' ? 'selected' : ''; ?>>
                                                        Active
                                                    </option>
                                                    <option value="inactive" <?php echo $quiz['statut'] === 'inactive' ? 'selected' : ''; ?>>
                                                        Inactive
                                                    </option>
                                                </select>
                                            </td>
                                            <td style="font-size: 12px; color: #aaa;">
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
                                                <button onclick="deleteQuiz(<?php echo $quiz['id_quiz']; ?>)" 
                                                   class="btn btn-sm btn-danger" 
                                                   title="Delete Permanently"
                                                   style="background: #dc3545; border: none; cursor: pointer; padding: 5px 10px;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
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

<script>
$(document).ready(function() {
    if (!$.fn.DataTable) {
        console.error('DataTables library not loaded!');
        return;
    }
    
    var table = $('#quizManageTable').DataTable({
        responsive: true,
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
        language: {
            search: "Search:",
            searchPlaceholder: "Search quizzes...",
            lengthMenu: "Show _MENU_ quizzes",
            info: "Showing _START_ to _END_ of _TOTAL_ quizzes",
            infoEmpty: "No quizzes found",
            infoFiltered: "(filtered from _MAX_ total)",
            zeroRecords: "No matching quizzes found",
            emptyTable: "No quizzes available yet",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next »",
                previous: "« Previous"
            }
        },
        columnDefs: [
            { orderable: false, targets: [9] },
            { className: "text-center", targets: [5, 6] }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        initComplete: function() {
            console.log('DataTables initialized successfully!');
        }
    });
    
    $('#quizManageTable_filter input').attr('placeholder', 'Search quizzes...');
});

function updateQuizStatus(selectElement) {
    const quizId = selectElement.getAttribute('data-quiz-id');
    const newStatus = selectElement.value;
    
    selectElement.style.background = newStatus === 'active' ? '#4CAF50' : '#ff9800';
    
    fetch('index.php?page=update_quiz_status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_quiz=${quizId}&status=${newStatus}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Status updated successfully!');
        } else {
            alert('Failed to update status: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating status');
    });
}

function deleteQuiz(quizId) {
    if (confirm('Are you sure you want to PERMANENTLY delete this quiz?\n\nThis will remove:\n- The quiz\n- All questions\n- All user results\n\nThis action cannot be undone!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?page=delete_quiz';
        
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id_quiz';
        input.value = quizId;
        
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>