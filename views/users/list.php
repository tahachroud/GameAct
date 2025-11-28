<h2>Users List</h2>

<a href="index.php?action=users_create" class="btn btn-success mb-3">+ Add User</a>

<table class="table table-dark table-striped">
    <tr>
        <th>Avatar</th>
        <th>Username</th>
        <th>Level</th>
        <th>XP</th>
        <th>Badges</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($users as $u): ?>
        <tr>
            <td>
                <?php if ($u['avatar']): ?>
                    <img src="public/uploads/users/<?= $u['avatar'] ?>" width="60">
                <?php endif; ?>
            </td>
            <td><?= $u['username'] ?></td>
            <td><?= $u['level'] ?></td>
            <td><?= $u['xp'] ?></td>
            <td><?= $u['badges'] ?></td>
            <td>
                <a href="index.php?action=users_edit&id=<?= $u['id'] ?>" class="btn btn-warning">Edit</a>
                <a href="index.php?action=users_delete&id=<?= $u['id'] ?>" class="btn btn-danger">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
