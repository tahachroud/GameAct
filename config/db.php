<?php
/**
 * Database Configuration - Team Standard
 * Used across all GameAct modules (Events, Shop, Feed, Quiz, Tutorials)
 */

class config {
    private static $pdo = null;

    /**
     * Get database connection (Singleton pattern)
     * @return PDO Database connection
     */
    public static function getConnexion() {
        if (!isset(self::$pdo)) {
            try {
                self::$pdo = new PDO(
                    'mysql:host=localhost;dbname=gameact',
                    'root',
                    '',
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
                );
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}
?>