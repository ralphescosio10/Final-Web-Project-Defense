<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller {

    private User $user;

    public function __construct() {
        $this->user = new User(); 
    }

  
    public function showLogin() {
        return $this->view('login'); 
    }

    
    public function showRegister() {
        return $this->view('register');
    }

    
    public function login() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header("Location: /login?error=Please enter both username and password");
            exit();
        }

        $user = $this->user->authenticate($username, $password);

        if (!$user) {
            header("Location: /login?error=Invalid username or password");
            exit();
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['user_id'] = $user['id'];
        header("Location: /");
        exit();
    }

    
    public function register() {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            header("Location: /register?error=All fields are required");
            exit();
        }

        if (strlen($password) < 4) {
            header("Location: /register?error=Password must be at least 4 characters");
            exit();
        }

        if ($this->user->findByUsername($username)) {
            header("Location: /register?error=Username already taken");
            exit();
        }

        $this->user->create($username, $password);
        header("Location: /login?success=Account created! Please login.");
        exit();
    }

    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /login");
        exit();
    }
}