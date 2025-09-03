<?php
// src/Models/Admin.php
require_once __DIR__ . '/../Core/Database.php';

class Admin {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // --- Methods to populate filter dropdowns ---
    public function getAllCourses() {
        return $this->db->query("SELECT id, course_name FROM courses ORDER BY course_name")->fetchAll();
    }
    public function getAllStaff() {
        return $this->db->query("SELECT id, full_name FROM users WHERE role = 'staff' ORDER BY full_name")->fetchAll();
    }
    public function getAllStudents() {
        return $this->db->query("SELECT id, full_name FROM users WHERE role = 'student' ORDER BY full_name")->fetchAll();
    }

    // --- The main method to get filtered results ---
    public function getFilteredResults($filters) {
        $baseSql = "
            SELECT 
                sta.id, sta.score, sta.status, sta.end_time,
                student.full_name as student_name,
                t.test_title,
                c.course_name,
                staff.full_name as staff_name
            FROM student_test_attempts sta
            JOIN users student ON sta.student_id = student.id
            JOIN tests t ON sta.test_id = t.id
            JOIN courses c ON t.course_id = c.id
            JOIN users staff ON t.staff_id = staff.id
        ";
        
        $whereClauses = [];
        $params = [];

        if (!empty($filters['course_id'])) {
            $whereClauses[] = "c.id = ?";
            $params[] = $filters['course_id'];
        }
        if (!empty($filters['staff_id'])) {
            $whereClauses[] = "staff.id = ?";
            $params[] = $filters['staff_id'];
        }
        if (!empty($filters['student_id'])) {
            $whereClauses[] = "student.id = ?";
            $params[] = $filters['student_id'];
        }
        if (!empty($filters['start_date'])) {
            $whereClauses[] = "DATE(sta.end_time) >= ?";
            $params[] = $filters['start_date'];
        }
        if (!empty($filters['end_date'])) {
            $whereClauses[] = "DATE(sta.end_time) <= ?";
            $params[] = $filters['end_date'];
        }

        if (!empty($whereClauses)) {
            $baseSql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $baseSql .= " ORDER BY sta.end_time DESC";

        $stmt = $this->db->prepare($baseSql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getDashboardStats() {
    $stats = [];
    $stats['total_students'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
    $stats['total_staff'] = $this->db->query("SELECT COUNT(*) FROM users WHERE role = 'staff'")->fetchColumn();
    $stats['total_courses'] = $this->db->query("SELECT COUNT(*) FROM courses")->fetchColumn();
    $stats['total_attempts'] = $this->db->query("SELECT COUNT(*) FROM student_test_attempts")->fetchColumn();
    return $stats;
    }

    public function getRecentTestAttemptsChartData() {
        // Fetches the count of test attempts for the last 7 days
        $sql = "
            SELECT DATE(end_time) as attempt_date, COUNT(*) as attempt_count
            FROM student_test_attempts
            WHERE end_time >= CURDATE() - INTERVAL 6 DAY
            GROUP BY DATE(end_time)
            ORDER BY attempt_date ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }
}