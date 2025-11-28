<?php
class Database {
    private $host = "localhost";
    private $dbname = "gameact";
    private $username = "root";
    private $password = "";
    private static $connection;

    public function getConnection() {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                    $this->username,
                    $this->password
                );

                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("❌ Database Connection Error: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}
