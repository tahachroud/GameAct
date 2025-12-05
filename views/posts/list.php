<div class="admin-container">

<h2>Posts List</h2>

<a href="index.php?action=posts_create" class="btn-gaming btn-create-g">+ Add Post</a>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Content</th>
            <th>Image</th>
            <th style="width:150px;">Actions</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($posts as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['username']) ?></td>
            <td><?= htmlspecialchars(substr($p['content'], 0, 60)) ?>...</td>

            <td>
                <?php 
$imgs = json_decode($p['images'], true);
if ($imgs && count($imgs) > 0): ?>
    <img src="public/uploads/posts/<?= $imgs[0] ?>" width="70">
<?php else: ?>
    <span style="color:#777;">No images</span>
<?php endif; ?>

            </td>

            <td>
               <a href="index.php?action=posts_edit&id=<?= $p['id'] ?>" class="btn-gaming sm btn-edit-g">Edit</a>

<a href="index.php?action=posts_delete&id=<?= $p['id'] ?>" 
   class="btn-gaming sm btn-delete-g"
   onclick="return confirm('Delete post?');">Delete</a>

            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

</div>
