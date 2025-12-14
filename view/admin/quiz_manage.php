<?php
$pageTitle = "Manage Quiz - Admin Panel";
$page = 'admin_quiz_manage';

$customCSS = '
.manage-container {
    background: linear-gradient(135deg, #1a1d1f 0%, #252829 50%, #1f2122 100%);
    border-radius: 15px;
    padding: 30px;
    margin: 30px 0;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
}

.table {
    color: #fff;
    font-size: 14px;
    margin-bottom: 0;
}

.table thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.table thead th {
    border: none;
    color: #fff !important;
    font-weight: 700;
    background: linear-gradient(135deg, #e84057 0%, #d63346 100%) !important;
    padding: 16px 12px !important;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 12px;
    box-shadow: 0 4px 6px rgba(232, 64, 87, 0.2);
}

.table tbody tr {
    border-bottom: 1px solid #333;
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: rgba(232, 64, 87, 0.1);
    box-shadow: 0 2px 8px rgba(232, 64, 87, 0.15);
}

.table tbody td {
    border-color: #333;
    vertical-align: middle;
    padding: 14px 12px !important;
    color: #e0e0e0;
}

.table tbody td:first-child {
    font-weight: 600;
    color: #e84057;
}

.badge-category {
    background: linear-gradient(135deg, #e84057 0%, #d63346 100%);
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
    box-shadow: 0 2px 4px rgba(232, 64, 87, 0.2);
}

.badge-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.badge-active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.2);
}

.badge-pending {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #000;
    box-shadow: 0 2px 4px rgba(255, 193, 7, 0.2);
}

.badge-deleted {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
}

.action-buttons {
    display: flex;
    gap: 6px;
    white-space: nowrap;
    min-width: 110px;
}

.action-buttons .btn {
    padding: 6px 10px !important;
    font-size: 12px;
    border-radius: 6px;
    border: none;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.action-buttons .btn-info {
    background: #17a2b8 !important;
}

.action-buttons .btn-info:hover {
    background: #138496 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(23, 162, 184, 0.3);
}

.action-buttons .btn-warning {
    background: #ffc107 !important;
    color: #000 !important;
}

.action-buttons .btn-warning:hover {
    background: #ffb300 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.action-buttons .btn-danger {
    background: #dc3545 !important;
}

.action-buttons .btn-danger:hover {
    background: #c82333 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

.status-dropdown {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    border: 1px solid #444 !important;
    border-radius: 6px;
    padding: 6px 10px !important;
    padding-right: 30px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
}

.status-dropdown:hover {
    border-color: #555 !important;
}

.status-dropdown:focus {
    outline: none;
    box-shadow: 0 0 8px rgba(232, 64, 87, 0.3);
}

.status-dropdown:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.dataTables_wrapper {
    padding: 15px 0;
    color: #fff;
}

.dataTables_wrapper .dataTables_length,
.dataTables_wrapper .dataTables_filter {
    margin-bottom: 20px;
}

.dataTables_wrapper .dataTables_length select,
.dataTables_wrapper .dataTables_filter input {
    background: #1f2122;
    border: 2px solid #e75e8d;
    color: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    margin-left: 8px;
    transition: all 0.3s ease;
    font-size: 13px;
}

.dataTables_wrapper .dataTables_length select:focus,
.dataTables_wrapper .dataTables_filter input:focus {
    outline: none;
    border-color: #e84057;
    box-shadow: 0 0 12px rgba(232, 64, 87, 0.4);
}

.dataTables_wrapper .dataTables_length label,
.dataTables_wrapper .dataTables_filter label {
    color: #aaa;
    font-weight: 500;
    margin-right: 0;
}

.dataTables_wrapper .dataTables_paginate {
    margin-top: 20px;
    text-align: right;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    background: linear-gradient(135deg, #27292a 0%, #1f2122 100%) !important;
    border: 2px solid #444 !important;
    color: #fff !important;
    padding: 8px 12px !important;
    margin: 0 3px !important;
    border-radius: 8px !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    font-weight: 600;
    font-size: 12px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
    background: linear-gradient(135deg, #e75e8d 0%, #e84057 100%) !important;
    border-color: #e84057 !important;
    color: #fff !important;
    transform: translateY(-2px);
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: linear-gradient(135deg, #e84057 0%, #d63346 100%) !important;
    border-color: #d63346 !important;
    color: #fff !important;
    box-shadow: 0 4px 8px rgba(232, 64, 87, 0.3) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
    opacity: 0.4;
    cursor: not-allowed !important;
}

table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc {
    cursor: pointer;
    position: relative;
    user-select: none;
}

table.dataTable thead .sorting:after,
table.dataTable thead .sorting_asc:after,
table.dataTable thead .sorting_desc:after {
    position: absolute;
    right: 10px;
    color: #fff;
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    opacity: 0.6;
}

table.dataTable thead .sorting:hover:after {
    opacity: 1;
}

table.dataTable thead .sorting:after {
    content: "\f0dc";
}

table.dataTable thead .sorting_asc:after {
    content: "\f0de";
    opacity: 1;
}

table.dataTable thead .sorting_desc:after {
    content: "\f0dd";
    opacity: 1;
}

.dataTables_wrapper .dataTables_info {
    padding-top: 10px;
    color: #999 !important;
    font-size: 13px;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar {
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #1f2122;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #e84057;
    border-radius: 10px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #d63346;
}
';

include 'view/admin/header.php';
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
                        <a href="quiz_list.php?page=quiz_create" class="btn btn-success" style="padding: 12px 25px; border-radius: 23px;">
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
                                        <th>Duration</th>
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
                                            <td class="text-center"><?php 
                                                $durationSeconds = 180 + (max(0, $quiz['nombre_questions'] - 8) * 15);
                                                $durationMinutes = ceil($durationSeconds / 60);
                                                echo $durationMinutes . ' min';
                                            ?></td>
                                            <td class="text-center"><?php echo $quiz['nombre_completions']; ?></td>
                                            <td>
                                                <select class="status-dropdown" 
                                                        data-quiz-id="<?php echo $quiz['id_quiz']; ?>"
                                                        data-original-status="<?php echo $quiz['statut']; ?>"
                                                        onchange="updateQuizStatus(this)"
                                                        style="padding: 5px 10px; 
                                                               border-radius: 5px; 
                                                               border: 1px solid #444;
                                                               background: <?php 
                                                                   if ($quiz['statut'] === 'active') {
                                                                       echo '#4CAF50';
                                                                   } elseif ($quiz['statut'] === 'pending') {
                                                                       echo '#ffc107';
                                                                   } else {
                                                                       echo '#dc3545';
                                                                   }
                                                               ?>;
                                                               color: <?php echo ($quiz['statut'] === 'pending') ? '#000' : 'white'; ?>;
                                                               font-weight: bold;
                                                               cursor: pointer;">
                                                    <option value="active" <?php echo $quiz['statut'] === 'active' ? 'selected' : ''; ?>>
                                                        Active
                                                    </option>
                                                    <option value="pending" <?php echo $quiz['statut'] === 'pending' ? 'selected' : ''; ?>>
                                                        Pending
                                                    </option>
                                                    <option value="deleted" <?php echo $quiz['statut'] === 'deleted' ? 'selected' : ''; ?>>
                                                        Deleted
                                                    </option>
                                                </select>
                                            </td>
                                            <td style="font-size: 12px; color: #aaa;">
                                                <?php echo date('M d, Y', strtotime($quiz['date_creation'])); ?>
                                            </td>
                                            <td class="action-buttons">
                                                <a href="quiz_list.php?page=quiz_play&id=<?php echo $quiz['id_quiz']; ?>" 
                                                    class="btn btn-sm btn-info" title="Preview" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="admin.php?page=quiz_edit&id=<?php echo $quiz['id_quiz']; ?>" 
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

<?php include 'view/admin/footer.php'; ?>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable) {
        console.error('DataTables library not loaded!');
        return;
    }
    
    var table = $('#quizManageTable').DataTable({
        responsive: false,
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
        scrollX: true,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        initComplete: function() {
            console.log('DataTables initialized successfully!');
        }
    });
    
    $('#quizManageTable_filter input').attr('placeholder', 'Search quizzes...');
});

function getStatusColor(status) {
    if (status === 'active') return '#4CAF50';
    if (status === 'pending') return '#ffc107';
    if (status === 'deleted') return '#dc3545';
    return '#6c757d';
}

function getStatusTextColor(status) {
    return (status === 'pending') ? '#000' : 'white';
}

function updateQuizStatus(selectElement) {
    const quizId = selectElement.getAttribute('data-quiz-id');
    const newStatus = selectElement.value;
    const originalStatus = selectElement.getAttribute('data-original-status');
    
    // Update UI immediately for better UX
    const bgColor = getStatusColor(newStatus);
    const textColor = getStatusTextColor(newStatus);
    selectElement.style.background = bgColor;
    selectElement.style.color = textColor;
    selectElement.disabled = true;
    
    fetch('admin.php?page=update_quiz_status', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_quiz=${quizId}&status=${newStatus}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        selectElement.disabled = false;
        if (data.success) {
            // Update the original status attribute to the new status
            selectElement.setAttribute('data-original-status', newStatus);
            // Keep the color updated
            selectElement.style.background = bgColor;
            selectElement.style.color = textColor;
            showNotification('Status updated successfully!', 'success');
        } else {
            // Revert to original status on failure
            selectElement.value = originalStatus;
            const revertColor = getStatusColor(originalStatus);
            const revertTextColor = getStatusTextColor(originalStatus);
            selectElement.style.background = revertColor;
            selectElement.style.color = revertTextColor;
            showNotification('Failed to update status: ' + data.message, 'error');
        }
    })
    .catch(error => {
        selectElement.disabled = false;
        // Revert to original status on error
        selectElement.value = originalStatus;
        const revertColor = getStatusColor(originalStatus);
        const revertTextColor = getStatusTextColor(originalStatus);
        selectElement.style.background = revertColor;
        selectElement.style.color = revertTextColor;
        console.error('Error:', error);
        showNotification('Error updating status: ' + error.message, 'error');
    });
}

function showNotification(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertBg = type === 'success' ? '#28a745' : '#dc3545';
    
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" 
             style="background: ${alertBg}; color: white; border-radius: 10px; padding: 15px; position: fixed; 
                    top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter: brightness(0) invert(1);"></button>
        </div>
    `;
    
    document.body.insertAdjacentHTML('afterbegin', alertHtml);
    
    // Auto-remove after 4 seconds
    setTimeout(() => {
        const alert = document.querySelector('.alert.alert-dismissible');
        if (alert) {
            alert.remove();
        }
    }, 4000);
}

function deleteQuiz(quizId) {
    if (confirm('Are you sure you want to PERMANENTLY delete this quiz?\n\nThis will remove:\n- The quiz\n- All questions\n- All user results\n\nThis action cannot be undone!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'admin.php?page=quiz_delete';
        
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