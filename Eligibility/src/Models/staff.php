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

public function saveAndFinalizeGrades($attemptId, $grades) {
    // Start a transaction to ensure all queries succeed or none do.
    $this->db->beginTransaction();
    $totalScore = 0;
    
    try {
        // Step 1: Update the manually entered marks for short-answer questions.
        $stmt_update = $this->db->prepare(
            "UPDATE student_answers SET marks_awarded = ? WHERE attempt_id = ? AND question_id = ?"
        );
        foreach ($grades as $question_id => $marks) {
            $stmt_update->execute([$marks, $attemptId, $question_id]);
        }
        
        // Step 2: Recalculate the entire score from scratch.
        $stmt_calc = $this->db->prepare("
            SELECT 
                q.points, 
                sa.selected_option_id, 
                sa.marks_awarded,
                (SELECT id FROM question_options WHERE question_id = q.id AND is_correct = 1) as correct_option_id
            FROM student_answers sa
            JOIN questions q ON sa.question_id = q.id
            WHERE sa.attempt_id = ?
        ");
        $stmt_calc->execute([$attemptId]);
        $all_answers_for_attempt = $stmt_calc->fetchAll();

        // Loop through every answer to calculate the total score.
        foreach ($all_answers_for_attempt as $answer) {
            if ($answer['correct_option_id'] !== null) { 
                // This is a Multiple Choice Question.
                if ($answer['selected_option_id'] == $answer['correct_option_id']) {
                    // Award full points if the answer is correct.
                    $totalScore += $answer['points'];
                }
            } else { 
                // This is a Short Answer Question.
                // Add the manually entered marks.
                $totalScore += $answer['marks_awarded'];
            }
        }
        
        // Step 3: Update the main attempt record with the final score and new status.
        $stmt_final = $this->db->prepare(
            "UPDATE student_test_attempts SET score = ?, status = 'graded' WHERE id = ?"
        );
        $stmt_final->execute([$totalScore, $attemptId]);

        // If all queries were successful, save the changes to the database.
        $this->db->commit();
        return true;

    } catch (Exception $e) {
        // If any query failed, undo all changes from this transaction.
        $this->db->rollBack();
        
        // This is the debugging code that should show the REAL error.
        die("<h1>A new error was found!</h1><p><b>Please copy this entire message and paste it in your reply.</b></p><pre>Database Error: " . $e->getMessage() . "</pre>");
    }
}
}