<?php
// expects $topContributors array with user info and engagement scores
$badges = ['🏆 Gold', '🥈 Silver', '🥉 Bronze'];
?>
<div class="contributor-card side-card">
    <h5>Top Contributors</h5>
    <small class="text-muted">This month</small>
    <div class="mt-3">
        <?php foreach ($topContributors as $index => $contributor): ?>
            <div class="contributor-item">
                <div class="contributor-badge"><?= $badges[$index] ?? '⭐' ?></div>
                <div class="contributor-info">
                    <div class="contributor-name"><?= htmlspecialchars($contributor['username'] ?? 'User ' . $contributor['id']) ?></div>
                    <div class="contributor-stats">
                        <span class="stat-item">📝 <?= $contributor['posts'] ?></span>
                        <span class="stat-item">💬 <?= $contributor['comments'] ?></span>
                        <span class="stat-item">❤️ <?= $contributor['likes'] ?></span>
                    </div>
                    <div class="contributor-score">
                        ⚡ Score: <?= $contributor['engagement_score'] ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
