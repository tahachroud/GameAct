<h2>Comments List</h2>

<a href="index.php?action=comments_create" class="btn btn-success mb-3">+ Add Comment</a>

<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th>User</th>
            <th>Post</th>
            <th>Comment</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($comments as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['username']) ?></td>
                <td><?= htmlspecialchars(substr($c['post_content'], 0, 40)) ?>...</td>
                <td><?= htmlspecialchars($c['content']) ?></td>

                <td>
                    <!-- EDIT BUTTON (correct route) -->
                    <a href="index.php?action=comments_edit&id=<?= $c['id'] ?>" 
                       class="btn btn-warning btn-sm">
                        Edit
                    </a>

                    <!-- DELETE BUTTON -->
                    <a href="index.php?action=comments_delete&id=<?= $c['id'] ?>" 
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('Delete this comment?');">
                        Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
