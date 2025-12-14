<?php
require_once __DIR__ . '/config.php';

try {
    $pdo = config::getConnexion();
    
    // Check if column exists
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = 'games' AND COLUMN_NAME = 'download_link'");
    $dbName = 'gameact';
    $stmt->execute(['db' => $dbName]);
    $row = $stmt->fetch();
    
    if ($row && isset($row['cnt']) && (int)$row['cnt'] > 0) {
        echo "<h2 style='color: green;'>✓ Column 'download_link' already exists in table 'games'.</h2>";
        exit(0);
    }
    
    // Add the column
    $sql = "ALTER TABLE games ADD COLUMN download_link VARCHAR(1024) DEFAULT ''";
    $pdo->exec($sql);
    echo "<h2 style='color: green;'>✓ Successfully added 'download_link' column to 'games' table!</h2>";
    echo "<p>You can now submit the add game form.</p>";
    exit(0);
    
} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Migration failed:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    exit(1);
}
?>
