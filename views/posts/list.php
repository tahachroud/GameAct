<h2>Posts List</h2>

<a href="index.php?action=posts_create" class="btn btn-success mb-3">+ Add Post</a>

<table class="table table-dark table-striped">
<tr>
    <th>ID</th>
    <th>User</th>
    <th>Content</th>
    <th>Image</th>
    <th>Actions</th>
</tr>

<?php foreach ($posts as $p): ?>
<tr>
    <td><?= $p['id'] ?></td>
    <td><?= htmlspecialchars($p['username']) ?></td>
    <td><?= htmlspecialchars($p['content']) ?></td>
    <td>
        <?php if ($p['image']): ?>
            <img src="public/uploads/posts/<?= $p['image'] ?>" width="70">
        <?php endif; ?>
    </td>
    <td>
        <a href="index.php?action=posts_edit&id=<?= $p['id'] ?>" class="btn btn-warning">Edit</a>
        <a href="index.php?action=posts_delete&id=<?= $p['id'] ?>" class="btn btn-danger">Delete</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
