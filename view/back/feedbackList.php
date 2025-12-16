<?php require_once "./view/back/headertuto.php"; ?>

<h2 class="section-title">Gestion des Feedbacks</h2>

<table class="table-admin">
    <tr>
        <th>ID</th>
        <th>Tutoriel</th>
        <th>Nom</th>
        <th>Message</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    <?php while ($f = $data->fetch()) : ?>
        <tr>
            <td><?= $f["id"] ?></td>
            <td><?= htmlspecialchars($f["title"]) ?></td>
            <td><?= htmlspecialchars($f["username"]) ?></td>
            <td><?= htmlspecialchars($f["message"]) ?></td>
            <td><?= $f["created_at"] ?></td>

            <td>
                <a href="router.php?action=deleteFeedback&id=<?= $f['id'] ?>" class="btn-delete">
                    Supprimer
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<?php require_once "./view/back/footertuto.php"; ?>
