<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Task;

class TaskController extends Controller {

    private Task $task;

    public function __construct() {
        $this->task = new Task(); 
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            header("Location: /login");
            exit(); 
        }
        
        $tasks = $this->task->getAllByUserId($_SESSION['user_id']); 
        $this->view("tasks", compact("tasks"));
    }

    public function store() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        $id = !empty($_POST['id']) ? (int)$_POST['id'] : null;
        $title = $_POST['title'];
        $description = $_POST['description'];
        $user_id = $_SESSION['user_id'];

        if ($id !== null) {
            $this->task->createWithManualId($id, $title, $user_id, $description);
        } else {
            $this->task->create($title, $user_id, $description);
        }

        header("Location: /");
        exit();
    }

    public function edit() {
        $id = $_GET['id'];
        $task = $this->task->getById($id);
        
        if (!$task) {
            header("Location: /");
            exit();
        }

        $this->view("edit_task", compact("task"));
    }


    public function update() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $id = $_POST['id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($title)) {
            header("Location: /?error=Title cannot be empty"); 
            exit();
        }

        if ($id) {
            $this->task->updateTask($id, $title, $description); 
            $_SESSION['success_message'] = "Task updated successfully!";
        }
        
        header("Location: /");
        exit();
    }

   public function complete() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    
    $id = $_GET['id'] ?? null;
    if ($id) {
        $taskData = $this->task->getById($id);
        $newStatus = ($taskData['status'] === 'completed') ? 'pending' : 'completed';
        $this->task->updateStatus($id, $newStatus);
        $_SESSION['success_message'] = "Task marked as " . $newStatus . "!";
    }
    
    header("Location: /");
    exit();
}

    public function delete() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $id = $_GET['id'];
        $this->task->delete($id);
        
        $_SESSION['success_message'] = "Task deleted successfully!";
        
        header("Location: /");
        exit();
    }
}