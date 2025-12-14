<h2>Edit Comment</h2>

<!-- AFFICHAGE DES ERREURS BACKEND -->
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <?php foreach ($_SESSION['errors'] as $e): ?>
            <p><?= $e ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="index-community.php?action=comments_update" method="POST">

    <!-- HIDDEN ID -->
    <input type="hidden" name="id" value="<?= $comment['id'] ?>">

    <label>Post</label>
    <select name="post_id" class="form-control">
        <?php foreach ($posts as $p): ?>
            <option value="<?= $p['id'] ?>" <?= ($p['id'] == $comment['post_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars(substr($p['content'], 0, 40)) ?>...
            </option>
        <?php endforeach; ?>
    </select>

    <label class="mt-3">User</label>
    <select name="user_id" class="form-control">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= ($u['id'] == $comment['user_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($u['username']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label class="mt-3">Content</label>
    <textarea name="content" class="form-control" rows="4"><?= htmlspecialchars($comment['content']) ?></textarea>

    <!-- ZONE D’ERREURS JS -->
    <div class="error-box"></div>

    <button class="btn btn-primary mt-3">Update Comment</button>
</form>

<script src="public/assets/js/projet.js"></script>
