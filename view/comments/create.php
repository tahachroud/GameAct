<h2>Add Comment</h2>

<!-- AFFICHAGE DES ERREURS BACKEND -->
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <?php foreach ($_SESSION['errors'] as $e): ?>
            <p><?= $e ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="POST" action="index-community.php?action=comments_store">

    <label>Post</label>
    <select name="post_id" class="form-control">
        <?php foreach ($posts as $p): ?>
            <option value="<?= $p['id'] ?>">
                <?= htmlspecialchars(substr($p['content'],0,30)) ?>...
            </option>
        <?php endforeach; ?>
    </select>

    <label class="mt-2">User</label>
    <select name="user_id" class="form-control">
        <?php foreach ($users as $u): ?>
            <?php $displayName = $u['username'] ?? trim(($u['name'] ?? '') . ' ' . ($u['lastname'] ?? '')) ?: ($u['email'] ?? 'User #' . $u['id']); ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($displayName) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="mt-2">Comment</label>
    <textarea name="content" class="form-control"></textarea>

    <!-- ZONE D’ERREURS JS -->
    <div class="error-box"></div>

    <button class="btn btn-primary mt-3">Save</button>
</form>

<script src="public/assets/js/projet.js"></script>
