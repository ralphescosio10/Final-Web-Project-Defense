<?php

namespace App\Models;

use PDO;
use Exception;

class DBORM {
    protected $pdo;
    protected $table;
    protected $query;
    protected $params = [];
    protected $orderPart = ""; // Added to track sorting

    public function __construct($dsn, $username, $password) {
        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die("Database Connection failed: " . $e->getMessage());
        }
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($columns = '*') {
        $this->query = "SELECT $columns";
        return $this;
    }

    public function from($table) {
        $this->table = $table;
        $this->query .= " FROM $table";
        return $this;
    }

    public function where($column, $value) {
        if (strpos($this->query, 'WHERE') === false) {
            $this->query .= " WHERE $column = ?";
        } else {
            $this->query .= " AND $column = ?";
        }
        $this->params[] = $value;
        return $this;
    }

    // New Method: Handles numerical sorting for your dashboard
    public function orderBy($column, $direction = 'ASC') {
        $this->orderPart = " ORDER BY $column $direction";
        return $this;
    }

    public function insert($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $this->query = "INSERT INTO $this->table ($columns) VALUES ($placeholders)";
        $this->params = array_values($data);
        return $this;
    }

    public function update($data) {
        $setPart = [];
        $updateParams = [];
        foreach ($data as $column => $value) {
            $setPart[] = "$column = ?";
            $updateParams[] = $value;
        }
        $this->query = "UPDATE $this->table SET " . implode(', ', $setPart);
        $this->params = array_merge($updateParams, $this->params);
        return $this;
    }

    public function delete() {
        $this->query = "DELETE FROM $this->table";
        return $this;
    }

    public function get() {
        // We append the orderPart to the end of the query before preparing
        $stmt = $this->pdo->prepare($this->query . $this->orderPart);
        $stmt->execute($this->params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->reset();
        return $result;
    }

    public function save() {
        $stmt = $this->pdo->prepare($this->query);
        $result = $stmt->execute($this->params);
        $this->reset();
        return $result;
    }

    protected function reset() {
        $this->query = "";
        $this->params = [];
        $this->orderPart = ""; // Reset sorting for the next query
    }
}