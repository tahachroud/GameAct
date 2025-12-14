<?php if (!empty($topHashtags)): ?>
<div class="trending-card">
    <h5>🔥 Trending Hashtags</h5>
    <small>Most used in community</small>

    <?php $rank = 1; ?>
<?php foreach ($topHashtags as $tag => $count): ?>
    <div class="trending-item <?= $rank === 1 ? 'top-hashtag' : '' ?>">
        <div class="trending-meta">
            <a href="index-community.php?action=community&tag=<?= urlencode($tag) ?>"
               class="hashtag">
                #<?= htmlspecialchars($tag) ?>
            </a>
            <div class="trending-sub"><?= $count ?> posts</div>
        </div>

        <span class="trend-badge"><?= $rank ?></span>
    </div>
<?php $rank++; ?>
<?php endforeach; ?>

</div>
<?php endif; ?>
