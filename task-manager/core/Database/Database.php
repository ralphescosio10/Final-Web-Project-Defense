<?php

namespace Core\Database;

use PDO;
use PDOException;

class Database {
    private PDO $pdo;

    public function __construct() {
        $host = "localhost";
        $port = "3306"; // Default MySQL port
        $dbname = "task_manager";
        $username = "root";
        $password = "1234";

        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
            
            $this->pdo = new PDO($dsn, $username, $password);
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public function connection(): PDO {
        return $this->pdo;
    }
}