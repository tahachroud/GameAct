<h2>Edit User</h2>

<form method="POST" action="index.php?action=users_update" enctype="multipart/form-data">

    <input type="hidden" name="id" value="<?= $data['id'] ?>">

    <label>Username</label>
    <input type="text" name="username" value="<?= $data['username'] ?>" class="form-control">

    <label class="mt-2">Avatar</label><br>

    <?php if ($data['avatar']): ?>
        <img src="public/uploads/users/<?= $data['avatar'] ?>" width="100"><br>
    <?php endif; ?>

    <input type="file" name="avatar" class="form-control">

    <label class="mt-2">Level</label>
    <input type="number" name="level" value="<?= $data['level'] ?>" class="form-control">

    <label class="mt-2">XP</label>
    <input type="number" name="xp" value="<?= $data['xp'] ?>" class="form-control">

    <label class="mt-2">Badges</label>
    <input type="text" name="badges" value="<?= $data['badges'] ?>" class="form-control">

    <button class="btn btn-primary mt-3">Update</button>
</form>
