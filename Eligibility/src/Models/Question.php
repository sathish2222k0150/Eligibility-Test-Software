<?php
// src/Models/Question.php
require_once __DIR__ . '/../Core/Database.php';

class Question {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByCourseIds($courseIds) {
        if (empty($courseIds)) {
            return [];
        }
        $in_placeholders = implode(',', array_fill(0, count($courseIds), '?'));
        $sql = "SELECT q.*, c.course_name FROM questions q JOIN courses c ON q.course_id = c.id WHERE q.course_id IN ($in_placeholders) ORDER BY c.course_name, q.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($courseIds);
        return $stmt->fetchAll();
    }

    public function create($data, $attachmentPath = null) { // Add $attachmentPath parameter
    $this->db->beginTransaction();
    try {
        // Add 'difficulty' and 'attachment_path' to the SQL query
        $sql = "INSERT INTO questions (course_id, staff_id, question_text, question_type, points, difficulty, attachment_path) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['course_id'],
            $data['staff_id'],
            $data['question_text'],
            $data['question_type'],
            $data['points'],
            $data['difficulty'],    // New field
            $attachmentPath         // New field
        ]);
        $questionId = $this->db->lastInsertId();

        if ($data['question_type'] === 'multiple_choice') {
            $sql_option = "INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
            $stmt_option = $this->db->prepare($sql_option);
            
            foreach ($data['options'] as $index => $optionText) {
                if (!empty($optionText)) {
                    $isCorrect = ($data['is_correct'] == $index) ? 1 : 0;
                    $stmt_option->execute([$questionId, $optionText, $isCorrect]);
                }
            }
        }
        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        // For debugging, you can temporarily add: die($e->getMessage());
        return false;
    }
}

public function findById($questionId) {
    // Fetch the main question details
    $stmt = $this->db->prepare("SELECT * FROM questions WHERE id = ?");
    $stmt->execute([$questionId]);
    $question = $stmt->fetch();

    if (!$question) {
        return null; // Return null if question not found
    }

    // If it's a multiple-choice question, fetch its options
    if ($question['question_type'] === 'multiple_choice') {
        $stmt_options = $this->db->prepare("SELECT * FROM question_options WHERE question_id = ? ORDER BY id");
        $stmt_options->execute([$questionId]);
        $question['options'] = $stmt_options->fetchAll();
    }

    return $question;
}
public function update($data, $attachmentPath = null) {
    $this->db->beginTransaction();
    try {
        // Update the main question details
        $sql = "UPDATE questions SET course_id = ?, question_text = ?, question_type = ?, points = ?, difficulty = ?";
        $params = [
            $data['course_id'],
            $data['question_text'],
            $data['question_type'],
            $data['points'],
            $data['difficulty'],
        ];

        // Only update the attachment path if a new file was uploaded
        if ($attachmentPath !== null) {
            $sql .= ", attachment_path = ?";
            $params[] = $attachmentPath;
        }

        $sql .= " WHERE id = ?";
        $params[] = $data['question_id'];

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        // For MCQs, the easiest and safest way to update options is to delete the old ones and insert the new ones.
        if ($data['question_type'] === 'multiple_choice') {
            // 1. Delete all existing options for this question
            $stmt_delete = $this->db->prepare("DELETE FROM question_options WHERE question_id = ?");
            $stmt_delete->execute([$data['question_id']]);

            // 2. Insert the new options from the form
            $sql_option = "INSERT INTO question_options (question_id, option_text, is_correct) VALUES (?, ?, ?)";
            $stmt_option = $this->db->prepare($sql_option);
            
            foreach ($data['options'] as $index => $optionText) {
                if (!empty($optionText)) {
                    $isCorrect = (isset($data['is_correct']) && $data['is_correct'] == $index) ? 1 : 0;
                    $stmt_option->execute([$data['question_id'], $optionText, $isCorrect]);
                }
            }
        }
        
        $this->db->commit();
        return true;
    } catch (Exception $e) {
        $this->db->rollBack();
        // For debugging: die("Update failed: " . $e->getMessage());
        return false;
    }
}

    public function delete($questionId) {
        $stmt = $this->db->prepare("DELETE FROM questions WHERE id = ?");
        return $stmt->execute([$questionId]);
    }

    public function getAllFiltered($filters = []) {
        $sql = "SELECT q.*, c.course_name, u.full_name as staff_name 
                FROM questions q 
                JOIN courses c ON q.course_id = c.id 
                JOIN users u ON q.staff_id = u.id";
        
        $whereClauses = [];
        $params = [];

        if (!empty($filters['course_id'])) {
            $whereClauses[] = "q.course_id = ?";
            $params[] = $filters['course_id'];
        }
        if (!empty($filters['staff_id'])) {
            $whereClauses[] = "q.staff_id = ?";
            $params[] = $filters['staff_id'];
        }

        if (!empty($whereClauses)) {
            $sql .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $sql .= " ORDER BY c.course_name, q.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

}