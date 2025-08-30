<?php
// src/Models/Staff.php
require_once __DIR__ . '/../Core/Database.php';

class Staff {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getDashboardStats($staffId) {
        $stats = [];
        $stats['my_questions'] = $this->db->query("SELECT COUNT(*) FROM questions WHERE staff_id = $staffId")->fetchColumn();
        $stats['my_tests'] = $this->db->query("SELECT COUNT(*) FROM tests WHERE staff_id = $staffId")->fetchColumn();
        // This is the most important one: how many tests need grading?
        $stats['pending_grading'] = $this->db->query("SELECT COUNT(*) FROM student_test_attempts sta JOIN tests t ON sta.test_id = t.id WHERE t.staff_id = $staffId AND sta.status = 'completed'")->fetchColumn();
        return $stats;
    }

    public function getRecentSubmissions($staffId) {
        // Gets the 5 most recent submissions that need grading
        $sql = "
            SELECT sta.id, sta.end_time, u.full_name as student_name, t.test_title
            FROM student_test_attempts sta
            JOIN tests t ON sta.test_id = t.id
            JOIN users u ON sta.student_id = u.id
            WHERE t.staff_id = ? AND sta.status = 'completed'
            ORDER BY sta.end_time DESC
            LIMIT 5
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$staffId]);
        return $stmt->fetchAll();
    }
    
    public function getAssignedCourseIds($staffId) {
        $stmt = $this->db->prepare("SELECT course_id FROM staff_courses WHERE user_id = :staffId");
        $stmt->execute(['staffId' => $staffId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN); // Returns a simple array of IDs [1, 3, 5]
    }
    
    public function updateAssignedCourses($staffId, $courseIds) {
       
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM staff_courses WHERE user_id = :staffId");
            $stmt->execute(['staffId' => $staffId]);

            $stmt = $this->db->prepare("INSERT INTO staff_courses (user_id, course_id) VALUES (:staffId, :courseId)");
            foreach ($courseIds as $courseId) {
                $stmt->execute(['staffId' => $staffId, 'courseId' => $courseId]);
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    public function getCompletedAttempts($staffId) {
    $sql = "
        SELECT 
            sta.id, sta.status, sta.score, sta.end_time,
            u.full_name as student_name,
            t.test_title
        FROM student_test_attempts sta
        JOIN users u ON sta.student_id = u.id
        JOIN tests t ON sta.test_id = t.id
        WHERE t.staff_id = :staffId AND sta.status IN ('completed', 'graded')
        ORDER BY sta.end_time DESC
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute(['staffId' => $staffId]);
    return $stmt->fetchAll();
}

public function getAttemptDetails($attemptId) {
    // This is a complex query to get everything needed for grading in one go
    $sql = "
        SELECT 
            q.id as question_id, q.question_text, q.question_type, q.points,
            sa.answer_text, sa.selected_option_id,
            GROUP_CONCAT(qo.option_text SEPARATOR '|||') as options,
            (SELECT id FROM question_options WHERE question_id = q.id AND is_correct = 1) as correct_option_id
        FROM student_answers sa
        JOIN questions q ON sa.question_id = q.id
        LEFT JOIN question_options qo ON q.id = qo.question_id
        WHERE sa.attempt_id = ?
        GROUP BY q.id
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->execute([$attemptId]);
    return $stmt->fetchAll();
}

// In src/Models/Staff.php

// In src/Models/Staff.php

// In src/Models/Staff.php

public function saveAndFinalizeGrades($attemptId, $grades) {
    // --- PHASE 1: READ ALL DATA FROM DATABASE ---
    // We fetch everything we need first, before any calculations or writes.
    try {
        $stmt_test = $this->db->prepare("SELECT t.settings FROM tests t JOIN student_test_attempts sta ON t.id = sta.test_id WHERE sta.id = ?");
        $stmt_test->execute([$attemptId]);
        $testSettings = json_decode($stmt_test->fetchColumn(), true);
        $negativeMarkingEnabled = isset($testSettings['enable_negative_marking']) && $testSettings['enable_negative_marking'];

        $stmt_answers = $this->db->prepare("
            SELECT 
                q.id as question_id,
                q.points, 
                q.negative_points,
                q.question_type,
                sa.selected_option_id,
                (SELECT id FROM question_options WHERE question_id = q.id AND is_correct = 1) as correct_option_id
            FROM student_answers sa
            JOIN questions q ON sa.question_id = q.id
            WHERE sa.attempt_id = ?
        ");
        $stmt_answers->execute([$attemptId]);
        $all_answers = $stmt_answers->fetchAll();

    } catch (Exception $e) {
        die("<h1>Database Error during data fetch!</h1><pre>" . $e->getMessage() . "</pre>");
    }

    // --- PHASE 2: PERFORM ALL CALCULATIONS PURELY IN PHP ---
    $totalScore = 0;
    $marksToUpdateInDB = []; // This will store the final mark for EVERY question.

    foreach ($all_answers as $answer) {
        $questionId = $answer['question_id'];

        if ($answer['question_type'] === 'multiple_choice') {
            // Use strict, type-safe comparison for IDs
            if ((int)$answer['selected_option_id'] === (int)$answer['correct_option_id']) {
                $totalScore += $answer['points'];
                $marksToUpdateInDB[$questionId] = $answer['points'];
            } else if ($negativeMarkingEnabled) {
                $totalScore -= $answer['negative_points'];
                $marksToUpdateInDB[$questionId] = -$answer['negative_points'];
            } else {
                $marksToUpdateInDB[$questionId] = 0;
            }
        } else { // This is a Short Answer question
            // Use the mark from the form ($grades array) as the source of truth.
            $awardedMark = isset($grades[$questionId]) ? (float)$grades[$questionId] : 0;
            $totalScore += $awardedMark;
            $marksToUpdateInDB[$questionId] = $awardedMark;
        }
    }

    // --- PHASE 3: WRITE ALL RESULTS TO THE DATABASE ---
    $this->db->beginTransaction();
    try {
        // Update the 'marks_awarded' for every single question based on our PHP calculation.
        if (!empty($marksToUpdateInDB)) {
            $stmt_update_marks = $this->db->prepare(
                "UPDATE student_answers SET marks_awarded = ? WHERE attempt_id = ? AND question_id = ?"
            );
            foreach ($marksToUpdateInDB as $qid => $mark) {
                $stmt_update_marks->execute([$mark, $attemptId, $qid]);
            }
        }
        
        // Update the final total score in the main attempts table.
        $stmt_final = $this->db->prepare(
            "UPDATE student_test_attempts SET score = ?, status = 'graded', graded_by = ? WHERE id = ?"
        );
        $staffId = Session::get('user_id'); 
        $stmt_final->execute([$totalScore, $staffId, $attemptId]);

        $this->db->commit();
        return true;

    } catch (Exception $e) {
        $this->db->rollBack();
        die("<h1>Database Error during final save!</h1><pre>" . $e->getMessage() . "</pre>");
    }
}
}