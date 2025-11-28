<h2>Add User</h2>

<form method="POST" action="index.php?action=users_store" enctype="multipart/form-data">

    <label>Username</label>
    <input type="text" name="username" class="form-control" required>

    <label class="mt-2">Avatar</label>
    <input type="file" name="avatar" class="form-control">

    <label class="mt-2">Level</label>
    <input type="number" name="level" value="1" class="form-control">

    <label class="mt-2">XP</label>
    <input type="number" name="xp" value="0" class="form-control">

    <label class="mt-2">Badges</label>
    <input type="text" name="badges" class="form-control">

    <button class="btn btn-primary mt-3">Save</button>
</form>
