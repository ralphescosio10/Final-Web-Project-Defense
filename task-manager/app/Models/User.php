<?php

namespace App\Models;

use Core\Database\Database;

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    
    public function authenticate(string $username, string $password): array|bool {
        $user = $this->findByUsername($username);

        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function findByUsername(string $username) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->connection()->prepare($sql);
        $stmt->execute(['username' => $username]);
        return $stmt->fetch();
    }

    public function create(string $username, string $password): bool {
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $this->db->connection()->prepare($sql);
        
   
        return $stmt->execute([
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);
    }
}