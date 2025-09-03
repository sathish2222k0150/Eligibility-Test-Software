<?php
// src/Models/Test.php
require_once __DIR__ . '/../Core/Database.php';

class Test {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
     public function create($data, $questionIds) {
        $this->db->beginTransaction();
        try {
            // Prepare the settings array
            $settings = [
                'randomize_questions' => isset($data['randomize_questions']) ? 1 : 0,
                'one_question_per_page' => isset($data['one_question_per_page']) ? 1 : 0,
                'enable_negative_marking' => isset($data['enable_negative_marking']) ? 1 : 0,
            ];

            $stmt = $this->db->prepare(
                "INSERT INTO tests (course_id, staff_id, test_title, duration_minutes, settings, status) VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $data['course_id'], 
                $data['staff_id'], 
                $data['test_title'], 
                $data['duration'], 
                json_encode($settings), // Encode settings array to JSON string
                $data['status']
            ]);
            $testId = $this->db->lastInsertId();

            $stmt_link = $this->db->prepare("INSERT INTO test_questions (test_id, question_id) VALUES (?, ?)");
            foreach ($questionIds as $qid) {
                $stmt_link->execute([$testId, $qid]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            // For debugging: die($e->getMessage());
            return false;
        }
    }

        public function getAvailableForStudent($studentId) {
            // This new query strictly checks for the student's actual course enrollments.
            $sql = "
                SELECT t.*, c.course_name 
                FROM tests t
                JOIN courses c ON t.course_id = c.id
                WHERE t.course_id IN (
                    -- THIS IS THE FIX: The column is 'student_id', not 'user_id'.
                    SELECT course_id FROM student_courses WHERE student_id = ?
                )
                AND t.status = 'published' 
                AND t.id NOT IN (
                    -- This one also needs to be 'student_id'.
                    SELECT test_id FROM student_test_attempts WHERE student_id = ?
                )
            ";
            $stmt = $this->db->prepare($sql);
            // Bind the studentId twice for the two subqueries
            $stmt->execute([$studentId, $studentId]);
            return $stmt->fetchAll();
        }

public function getDetailsById($testId) {
    $stmt = $this->db->prepare("SELECT * FROM tests WHERE id = ?");
    $stmt->execute([$testId]);
    return $stmt->fetch();
}

public function getQuestionsForTest($testId) {
    $test = $this->findById($testId);
    $sql = "
        SELECT q.* 
        FROM questions q
        JOIN test_questions tq ON q.id = tq.question_id
        WHERE tq.test_id = ?
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$testId]);
    $questions = $stmt->fetchAll();

    // --- RANDOMIZATION LOGIC ---
    // If the setting is enabled, shuffle the array of questions
    if ($test && isset($test['settings']['randomize_questions']) && $test['settings']['randomize_questions']) {
        shuffle($questions);
    }
    // --- END OF LOGIC ---

    // For MCQs, fetch their options
    foreach ($questions as $key => $q) {
        if ($q['question_type'] === 'multiple_choice') {
            $stmt_opts = $this->db->prepare("SELECT id, option_text FROM question_options WHERE question_id = ?");
            $stmt_opts->execute([$q['id']]);
            $questions[$key]['options'] = $stmt_opts->fetchAll();
        }
    }
    return $questions;
}

    public function getByStaffId($staffId) {
        $sql = "SELECT t.*, c.course_name FROM tests t 
                JOIN courses c ON t.course_id = c.id 
                WHERE t.staff_id = ? ORDER BY t.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }
    public function findById($testId) {
        $stmt = $this->db->prepare("SELECT * FROM tests WHERE id = ?");
        $stmt->execute([$testId]);
        $test = $stmt->fetch();
        // Decode the settings from JSON into an associative array for easier use
        if ($test && $test['settings']) {
            $test['settings'] = json_decode($test['settings'], true);
        }
        return $test;
    }

    public function updateStatus($testId, $status) {
        $stmt = $this->db->prepare("UPDATE tests SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $testId]);
    }

    public function delete($testId) {
        $stmt = $this->db->prepare("DELETE FROM tests WHERE id = ?");
        return $stmt->execute([$testId]);
    }

    // In src/Models/Test.php

    public function calculateMaxScore($testId) {
        $sql = "SELECT SUM(q.points) as max_score FROM questions q
                JOIN test_questions tq ON q.id = tq.question_id
                WHERE tq.test_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$testId]);
        $result = $stmt->fetch();
        return $result['max_score'] ?? 0;
    }
}