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
        return $this->db->query("SELECT id, full_name FROM students ORDER BY full_name")->fetchAll();
    }

    // --- The main method to get filtered results ---
     public function getFilteredResults($filters, $page = 1, $perPage = 15) {
        $baseSql = "
            SELECT SQL_CALC_FOUND_ROWS
                sta.id, sta.score, sta.status, sta.end_time,
                student.full_name as student_name,
                t.test_title,
                c.course_name,
                staff.full_name as staff_name
            FROM student_test_attempts sta
            JOIN students student ON sta.student_id = student.id
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

        $offset = ($page - 1) * $perPage;
        $baseSql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$perPage;
        $params[] = (int)$offset;

        $stmt = $this->db->prepare($baseSql);
        $stmt->execute($params);
        $results = $stmt->fetchAll();
        
        $totalRows = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();

        return [
            'results' => $results,
            'total'   => $totalRows,
            'page'    => $page,
            'perPage' => $perPage
        ];
    }

    public function getAllFilteredResultsForExport($filters) {
        $baseSql = "
            SELECT 
                sta.id, sta.score, sta.status, sta.end_time,
                student.full_name as student_name,
                t.test_title,
                c.course_name,
                staff.full_name as staff_name
            FROM student_test_attempts sta
            JOIN students student ON sta.student_id = student.id
            JOIN tests t ON sta.test_id = t.id
            JOIN courses c ON t.course_id = c.id
            JOIN users staff ON t.staff_id = staff.id
        ";
        
        $whereClauses = [];
        $params = [];

        if (!empty($filters['course_id'])) { $whereClauses[] = "c.id = ?"; $params[] = $filters['course_id']; }
        if (!empty($filters['staff_id'])) { $whereClauses[] = "staff.id = ?"; $params[] = $filters['staff_id']; }
        if (!empty($filters['student_id'])) { $whereClauses[] = "student.id = ?"; $params[] = $filters['student_id']; }
        if (!empty($filters['start_date'])) { $whereClauses[] = "DATE(sta.end_time) >= ?"; $params[] = $filters['start_date']; }
        if (!empty($filters['end_date'])) { $whereClauses[] = "DATE(sta.end_time) <= ?"; $params[] = $filters['end_date']; }

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
    $stats['total_students'] = $this->db->query("SELECT COUNT(*) FROM students")->fetchColumn();
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

        public function getPaginatedStudents($filters, $page = 1, $perPage = 15) {
        // --- 1. Build the base query ---
        $sql = "
            SELECT SQL_CALC_FOUND_ROWS 
                s.id, s.full_name, s.email,
                -- THIS IS THE KEY CHANGE: Use GROUP_CONCAT to combine course names
                GROUP_CONCAT(c.course_name SEPARATOR ', ') AS enrolled_courses,
                (SELECT status FROM student_test_attempts WHERE student_id = s.id ORDER BY start_time DESC LIMIT 1) as latest_attempt_status
            FROM students s
            LEFT JOIN student_courses sc ON s.id = sc.student_id
            LEFT JOIN courses c ON sc.course_id = c.id
        ";

        // --- 2. Dynamically add WHERE clauses (This part remains the same) ---
        $whereClauses = [];
        $params = [];

        if (!empty($filters['course_id'])) {
            // This filter now checks if a student is enrolled in a specific course, even if they have others.
            $whereClauses[] = "s.id IN (SELECT student_id FROM student_courses WHERE course_id = ?)";
            $params[] = $filters['course_id'];
        }
        // ... (The rest of your filter logic remains the same) ...
        if (!empty($filters['status'])) { /* ... */ }
        if (!empty($filters['staff_id'])) { /* ... */ }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        // --- 3. ADD GROUP BY to collapse all rows for a single student ---
        $sql .= " GROUP BY s.id ORDER BY s.full_name ASC";

        // --- 4. Add pagination (This part remains the same) ---
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = (int)$perPage;
        $params[] = (int)$offset;

        // --- 5. Execute queries (This part remains the same) ---
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $students = $stmt->fetchAll();
        
        $totalRows = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();

        return [
            'students' => $students,
            'total' => $totalRows,
            'page' => $page,
            'perPage' => $perPage
        ];
    }

        public function getFilteredTests($filters = []) {
        $sql = "
            SELECT 
                t.id, t.test_title, t.status,
                c.course_name,
                u.full_name as staff_name
            FROM tests t
            JOIN courses c ON t.course_id = c.id
            JOIN users u ON t.staff_id = u.id
        ";

        $whereClauses = [];
        $params = [];

        if (!empty($filters['course_id'])) {
            $whereClauses[] = "t.course_id = ?";
            $params[] = $filters['course_id'];
        }
        if (!empty($filters['staff_id'])) {
            $whereClauses[] = "t.staff_id = ?";
            $params[] = $filters['staff_id'];
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sql .= " ORDER BY t.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}