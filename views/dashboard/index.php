<div>

    <!-- PAGE TITLE -->
    <h2>Community Dashboard</h2>
    <p>Monitor community activity and manage posts.</p>

    <!-- STATS -->
    <div class="stats-grid">
        <div class="stat-box">
            <h3><?= $totalPosts ?></h3> Total Posts 
            <i class="fa fa-file"></i>
        </div>

        <div class="stat-box">
            <h3><?= $totalLikes ?></h3> Total Likes 
            <i class="fa fa-heart"></i>
        </div>

        <div class="stat-box">
            <h3><?= $totalComments ?></h3> Total Comments 
            <i class="fa fa-comment"></i>
        </div>

        <div class="stat-box">
            <h3><?= $totalShares ?></h3> Total Shares 
            <i class="fa fa-share"></i>
        </div>
    </div>

    <!-- TABLE -->
    <table id="feedTable">
        <thead>
            <tr>
                <th>Author</th>
                <th>Text</th>
                <th>Likes</th>
                <th>Comments</th>
                <th>Shares</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
        <?php foreach ($latestPosts as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['username']) ?></td>
                <td><?= htmlspecialchars(substr($p['content'], 0, 50)) ?></td>
                <td><?= $p['likes'] ?></td>
                <td><?= $p['comment_count'] ?></td>
                <td><?= $p['shares'] ?></td>
                <td><?= $p['created_at'] ?></td>

                <td>
                    <a href="index.php?action=posts_edit&id=<?= $p['id'] ?>" class="btn-edit btn btn-sm">Edit</a>
                    <a href="index.php?action=posts_delete&id=<?= $p['id'] ?>" class="btn-delete btn btn-sm"
                       onclick="return confirm('Delete post?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>

    </table>

</div>
