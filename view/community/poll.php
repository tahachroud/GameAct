<?php
$activePoll = $GLOBALS['activePoll'] ?? null;
if (!$activePoll) return;

$totalVotes = array_sum($activePoll['options']);
?>
<div class="poll-card side-card">
    <h5>🗳️ Poll</h5>
    <small class="text-muted">Community vote</small>
    
    <div class="poll-content mt-3">
        <div class="poll-question"><?= htmlspecialchars($activePoll['question']) ?></div>
        
        <div class="poll-options mt-3">
            <?php foreach ($activePoll['options'] as $option => $votes): ?>
                <?php $percentage = $totalVotes > 0 ? round(($votes / $totalVotes) * 100) : 0; ?>
                <div class="poll-option">
                    <button class="poll-vote-btn" data-poll-id="<?= $activePoll['id'] ?>" data-option="<?= htmlspecialchars($option) ?>">
                        <div class="poll-label"><?= htmlspecialchars($option) ?></div>
                        <div class="poll-bar">
                            <div class="poll-bar-fill" style="width: <?= $percentage ?>%"></div>
                        </div>
                        <div class="poll-stats"><?= $votes ?> (<?= $percentage ?>%)</div>
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="poll-footer mt-2">
            <small class="text-muted">Total votes: <?= $totalVotes ?></small>
        </div>
    </div>
</div>
