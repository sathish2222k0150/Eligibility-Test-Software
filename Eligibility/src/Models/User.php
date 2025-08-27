<?php
// src/Models/User.php

require_once __DIR__ . '/../Core/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function getAll() {
        // We don't want to manage the original admin user
        $stmt = $this->db->query("SELECT * FROM users WHERE role != 'admin' ORDER BY full_name ASC");
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

     public function create($fullName, $email, $password, $role, $courseId = null) {
        // The transaction logic has been removed from this method.
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$fullName, $email, $hashedPassword, $role]);
        $userId = $this->db->lastInsertId();

        if ($role === 'student' && !empty($courseId)) {
            $stmt_course = $this->db->prepare("INSERT INTO student_courses (user_id, course_id) VALUES (?, ?)");
            $stmt_course->execute([$userId, $courseId]);
        }
        
        return true;
    }

    public function update($id, $fullName, $email, $role, $password = null, $courseId = null) {
        // The transaction logic has been removed from this method.

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare(
                "UPDATE users SET full_name = ?, email = ?, role = ?, password = ? WHERE id = ?"
            );
            $stmt->execute([$fullName, $email, $role, $hashedPassword, $id]);
        } else {
            $stmt = $this->db->prepare(
                "UPDATE users SET full_name = ?, email = ?, role = ? WHERE id = ?"
            );
            $stmt->execute([$fullName, $email, $role, $id]);
        }

        if ($role === 'student') {
            $stmt_delete = $this->db->prepare("DELETE FROM student_courses WHERE user_id = ?");
            $stmt_delete->execute([$id]);

            if (!empty($courseId)) {
                $stmt_insert = $this->db->prepare("INSERT INTO student_courses (user_id, course_id) VALUES (?, ?)");
                $stmt_insert->execute([$id, $courseId]);
            }
        }
        
        return true;
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }



public function createBulkFromCsv($filePath) {
    $handle = fopen($filePath, "r");
    if ($handle === FALSE) {
        return ['success' => 0, 'failures' => [], 'error' => 'Could not open the file.'];
    }

    $this->db->beginTransaction();
    
    $successCount = 0;
    $failures = [];
    $rowNumber = 0;

    $courseCache = [];
    $getCourseId = function($code) use (&$courseCache) {
        if (isset($courseCache[$code])) {
            return $courseCache[$code];
        }
        $stmt = $this->db->prepare("SELECT id FROM courses WHERE course_code = ?");
        $stmt->execute([$code]);
        $courseId = $stmt->fetchColumn(); 
        $courseCache[$code] = $courseId;
        return $courseId;
    };

    
    fgetcsv($handle, 1000, ",");

    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $rowNumber++;
       
        if (count($data) < 4) {
            $failures[] = "Row $rowNumber: Invalid column count. Expected 4 columns (full_name, email, password, course_code).";
            continue;
        }

        $fullName = trim($data[0]);
        $email = trim($data[1]);
        $password = trim($data[2]);
        $courseCode = trim($data[3]); 

       
        if (empty($fullName) || empty($email) || empty($password) || empty($courseCode)) {
            $failures[] = "Row $rowNumber: One or more required fields are empty.";
            continue;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $failures[] = "Row $rowNumber ($email): Invalid email format.";
            continue;
        }
        if ($this->findByEmail($email)) {
            $failures[] = "Row $rowNumber ($email): Email already exists in the database.";
            continue;
        }

        
        $courseId = $getCourseId($courseCode);
        if (!$courseId) {
            $failures[] = "Row $rowNumber ($email): The course code '$courseCode' was not found in the system.";
            continue;
        }

       try {
                $this->create($fullName, $email, $password, 'student', $courseId);
                $successCount++;
            } catch (Exception $e) {
                $failures[] = "Row $rowNumber ($email): Database error - " . $e->getMessage();
            }
    }
    fclose($handle);

    if (!empty($failures)) {
        $this->db->rollBack();
        return ['success' => 0, 'failures' => $failures, 'error' => 'Import failed due to errors. No users were added.'];
    } else {
        $this->db->commit();
        return ['success' => $successCount, 'failures' => []];
    }
}
}