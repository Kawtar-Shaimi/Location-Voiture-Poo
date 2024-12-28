<?php
class BaseCrud {
    protected $db;
    protected $table;

    public function __construct($table) {
       include('connection.php');
        $this->db = (new Database())->connect();
        $this->table = $table;
    }

    // Create
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    // Read
    public function read($id = null) {
        $sql = "SELECT * FROM {$this->table}" . ($id ? " WHERE id = ?" : "");
        $stmt = $this->db->prepare($sql);

        if ($id) {
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update 
    public function update($id, $data) {
        $setClause = implode(', ', array_map(fn($col) => "{$col} = ?", array_keys($data)));

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([...array_values($data), $id]);

        return $stmt->rowCount();
    }

    // Delete
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);

        return $stmt->rowCount();
    }
}
