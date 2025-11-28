<h2>Add Post</h2>

<!-- AFFICHAGE DES ERREURS BACKEND -->
<?php if (!empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger">
        <?php foreach($_SESSION['errors'] as $e): ?>
            <p><?= $e ?></p>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form action="index.php?action=posts_store" method="POST" enctype="multipart/form-data">

    <label>User</label>
    <select name="user_id" class="form-control">
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['username']) ?></option>
        <?php endforeach; ?>
    </select>

    <label class="mt-2">Content</label>
    <textarea name="content" class="form-control"></textarea>

    <label class="mt-2">Image</label>
    <input type="file" name="image" class="form-control">

    <!-- ZONE D'ERREURS JS -->
    <div class="error-box"></div>

    <button class="btn btn-primary mt-3">Save</button>
</form>

<script src="public/assets/js/projet.js"></script>
