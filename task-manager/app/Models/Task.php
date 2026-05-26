<?php

namespace App\Models;

use App\Interfaces\TaskRepositoryInterface;

class Task implements TaskRepositoryInterface {

    private $db;

    public function __construct() {
        $this->db = new \App\Models\DBORM('mysql:host=localhost;dbname=task_manager', 'root', '1234');
    }

    public function getAllByUserId($user_id) {
        return $this->db->select()
                        ->from('tasks')
                        ->where('user_id', $user_id)
                        ->orderBy('id', 'ASC')
                        ->get();
    }

    public function getAll() {
        return $this->db->select()
                        ->from('tasks')
                        ->orderBy('id', 'ASC')
                        ->get();
    }

    public function getById($id) {
        $result = $this->db->select()
                           ->from('tasks')
                           ->where('id', $id)
                           ->get();

        return !empty($result) ? $result[0] : null;
    }

    public function create($title, $user_id = null, $description = '') {
        return $this->db->table('tasks')
                        ->insert([
                            'title' => $title, 
                            'description' => $description,
                            'user_id' => $user_id
                        ])
                        ->save();
    }

    public function updateTask($id, $title, $description) {
        return $this->db->table('tasks')
                        ->update([
                            'title' => $title,
                            'description' => $description
                        ])
                        ->where('id', $id)
                        ->save();
    }

    public function complete($id) {
        return $this->db->table('tasks')
                        ->update(['status' => 'completed'])
                        ->where('id', $id)
                        ->save();
    }

    public function delete($id) {
        return $this->db->table('tasks')
                        ->delete()
                        ->where('id', $id)
                        ->save();
    }

    public function createWithManualId($id, $title, $user_id, $description) {
        return $this->db->table('tasks')
                        ->insert([
                            'id' => $id, 
                            'title' => $title,
                            'description' => $description,
                            'user_id' => $user_id
                        ])
                        ->save();
    }

    public function updateStatus($id, $status) {
        return $this->db->table('tasks')
                        ->update(['status' => $status])
                        ->where('id', $id)
                        ->save();
    }
}