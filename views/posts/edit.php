<h2>Edit Post</h2>

<!-- AFFICHAGE DES ERREURS BACKEND -->
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <?php foreach($_SESSION['errors'] as $e): ?>
            <p><?= $e ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="index.php?action=posts_update" method="POST" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $post['id'] ?>">

    <label>User</label>
    <select name="user_id" class="form-control">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $u['id'] == $post['user_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['username']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label class="mt-2">Content</label>
    <textarea name="content" class="form-control"><?= htmlspecialchars($post['content']) ?></textarea>

    <label class="mt-2">Image</label><br>

    <?php if ($post['image']): ?>
        <img src="public/uploads/posts/<?= $post['image'] ?>" width="100">
    <?php endif; ?>

    <input type="file" name="image" class="form-control">

    <!-- ZONE D'ERREURS JS -->
    <div class="error-box"></div>

    <button class="btn btn-primary mt-3">Update</button>
</form>

<script src="public/assets/js/projet.js"></script>
