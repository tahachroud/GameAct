<h2>Delete User</h2>
<p>Are you sure?</p>

<a href="index.php?action=users" class="btn btn-secondary">Cancel</a>
<form method="POST" action="index.php?action=user_destroy&id=<?= $id ?>" style="display:inline;">
    <button class="btn btn-danger">Delete</button>
</form>
