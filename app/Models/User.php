<?php

namespace App\Models;

class User {
    private $orm;

    public function __construct() {
        $this->orm = new DBORM();
    }

    public function authenticate(string $username, string $password): array|bool {
        $user = $this->findByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function findByUsername(string $username) {
        $result = $this->orm->select('*')
                            ->from('users')
                            ->where('username', $username)
                            ->get();
        
        return !empty($result) ? $result[0] : false;
    }

    public function create(string $username, string $password): bool {
        return $this->orm->table('users')
                            ->insert([
                                'username' => $username,
                                'password' => password_hash($password, PASSWORD_DEFAULT)
                            ])
                            ->save();
    }
}