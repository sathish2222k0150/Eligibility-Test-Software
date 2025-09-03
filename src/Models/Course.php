<?php


require_once __DIR__ . '/../Core/Database.php';

class Course {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM courses ORDER BY course_name ASC");
        return $stmt->fetchAll();
    }

    public function create($name, $code, $description, $status) {
    $sql = "INSERT INTO courses (course_name, course_code, description, status) VALUES (:name, :code, :description, :status)";
    $stmt = $this->db->prepare($sql);
    return $stmt->execute([
        'name' => $name,
        'code' => $code,
        'description' => $description,
        'status' => $status
    ]);
    }

    public function findById($id) {
        // This method does not need changes
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function update($id, $name, $code, $description, $status) {
        $sql = "UPDATE courses SET course_name = :name, course_code = :code, description = :description, status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'code' => $code,
            'description' => $description,
            'status' => $status
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM courses WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}