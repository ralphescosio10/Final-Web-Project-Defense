<?php

namespace App\Interfaces;

interface TaskRepositoryInterface {
    public function getAll();
    public function create($title);
    public function complete($id);
    public function delete($id);
}