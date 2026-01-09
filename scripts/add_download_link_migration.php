<?php
require_once __DIR__ . '/../config.php';

try {
    $pdo = config::getConnexion();

    // Check if column exists
    $stmt = $pdo->prepare("SELECT COUNT(*) AS cnt FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = :db AND TABLE_NAME = 'games' AND COLUMN_NAME = 'download_link'");
    $dbName = 'gameact';
    $stmt->execute(['db' => $dbName]);
    $row = $stmt->fetch();

    if ($row && isset($row['cnt']) && (int)$row['cnt'] > 0) {
        echo "Column 'download_link' already exists in table 'games'.\n";
        exit(0);
    }

    // Add the column
    $sql = "ALTER TABLE games ADD COLUMN download_link VARCHAR(1024) DEFAULT ''";
    $pdo->exec($sql);
    echo "Added column 'download_link' to 'games' table successfully.\n";
    exit(0);

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}
