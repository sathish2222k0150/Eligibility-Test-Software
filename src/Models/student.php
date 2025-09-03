<?php
// src/Models/Student.php
require_once __DIR__ . '/../Core/Database.php'; // The ONLY require statement needed in this file.

class Student {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function isEnrolled($studentId, $courseId) {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM student_courses WHERE student_id = ? AND course_id = ?");
    $stmt->execute([$studentId, $courseId]);
    return $stmt->fetchColumn() > 0;
    }

    /**
     * Enrolls an existing student in a new course.
     */
    public function enrollInCourse($studentId, $courseId) {
        try {
            $stmt = $this->db->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)");
            return $stmt->execute([$studentId, $courseId]);
        } catch (Exception $e) {
            // This will fail if they are already enrolled due to the UNIQUE key, which is good.
            return false;
        }
    }
        public function register($fullName, $email, $password, $courseId) {
        // --- TRANSACTION LOGIC REMOVED FROM THIS METHOD ---
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO students (full_name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$fullName, $email, $hashedPassword]);
            $studentId = $this->db->lastInsertId();

            $stmt_course = $this->db->prepare("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)");
            $stmt_course->execute([$studentId, $courseId]);

            // On success, return the ID. If anything fails, an exception will be thrown
            // and caught by the calling function (createBulkFromCsv).
            return $studentId;
        } catch (Exception $e) {
            // Re-throw the exception so the calling function's transaction can handle it.
            throw $e;
        }
    }
    
    public function startAttempt($studentId, $testId) {
        $stmt = $this->db->prepare("INSERT INTO student_test_attempts (student_id, test_id, start_time, status) VALUES (?, ?, NOW(), 'in_progress')");
        $stmt->execute([$studentId, $testId]);
        return $this->db->lastInsertId();
    }

    public function submitTest($attemptId, $answers) {
        $this->db->beginTransaction();
        try {
            if (!empty($answers)) {
                foreach ($answers as $qid => $answer) {
                    if (is_array($answer) && isset($answer['option'])) { // MCQ
                        $stmt = $this->db->prepare("INSERT INTO student_answers (attempt_id, question_id, selected_option_id) VALUES (?, ?, ?)");
                        $stmt->execute([$attemptId, $qid, $answer['option']]);
                    } else { // Short Answer
                        $stmt = $this->db->prepare("INSERT INTO student_answers (attempt_id, question_id, answer_text) VALUES (?, ?, ?)");
                        $stmt->execute([$attemptId, $qid, $answer]);
                    }
                }
            }
            
            $stmt_end = $this->db->prepare("UPDATE student_test_attempts SET end_time = NOW(), status = 'completed' WHERE id = ?");
            $stmt_end->execute([$attemptId]);
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack(); 
            return false;
        }
    }

    public function getCompletedAttempts($studentId) {
        $sql = "SELECT 
                    sta.id, sta.status, sta.score, sta.end_time, sta.test_id,
                    t.test_title, c.course_name
                FROM student_test_attempts sta
                JOIN tests t ON sta.test_id = t.id
                JOIN courses c ON t.course_id = c.id
                WHERE sta.student_id = ? AND sta.status IN ('completed', 'graded')
                ORDER BY sta.end_time DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$studentId]);
        return $stmt->fetchAll();
    }

    public function getAttemptDetails($attemptId, $studentId) {
        $sql = "
            SELECT 
                q.id as question_id, q.question_text, q.attachment_path, q.question_type, q.points,
                sa.answer_text, sa.selected_option_id, sa.marks_awarded,
                (SELECT GROUP_CONCAT(CONCAT(id, ':::', option_text) SEPARATOR '|||') FROM question_options WHERE question_id = q.id) as options,
                (SELECT id FROM question_options WHERE question_id = q.id AND is_correct = 1) as correct_option_id
            FROM student_answers sa
            JOIN questions q ON sa.question_id = q.id
            JOIN student_test_attempts sta ON sa.attempt_id = sta.id
            WHERE sa.attempt_id = ? AND sta.student_id = ?
            GROUP BY q.id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$attemptId, $studentId]);
        return $stmt->fetchAll();
    }
}