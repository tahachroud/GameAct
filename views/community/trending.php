<?php
// expects $trending array available
?>
<div class="trending-card side-card">
    <h5>Trending</h5>
    <small class="text-muted">Most liked + commented</small>
    <div class="mt-3">
        <?php foreach ($trending as $t): ?>
            <div class="trending-item">
                <a href="index-community.php?action=posts&amp;id=<?= $t['id'] ?>" style="display:flex; gap:10px; text-decoration:none; color:inherit; width:100%;">
                    <div class="trending-thumb">
                        <?php
                        // try to show first image if available
                        $img = null;
                        if (!empty($t['images'])) {
                            $imgs = json_decode($t['images'], true);
                            if (is_array($imgs) && count($imgs) > 0) $img = $imgs[0];
                        }
                        ?>
                        <?php if ($img): ?>
                            <img src="public/uploads/posts/<?= htmlspecialchars($img) ?>" alt="thumb">
                        <?php else: ?>
                            <img src="public/assets/images/logo.png" alt="thumb">
                        <?php endif; ?>
                    </div>
                    <div class="trending-meta">
                        <div class="trending-title"><?= htmlspecialchars(mb_strimwidth($t['content'], 0, 75, '...')) ?></div>
                        <div class="trending-sub">by <?= htmlspecialchars($t['username']) ?> • <?= htmlspecialchars($t['created_at']) ?></div>
                        <div class="trend-stats">
                            <div class="trend-badge">⭐ <?= $t['trend_score'] ?></div>
                            <div class="trend-bubble">❤️ <?= $t['likes_count'] ?></div>
                            <div class="trend-bubble">💬 <?= $t['comments_count'] ?></div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
