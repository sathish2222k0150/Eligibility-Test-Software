<?php
// src/Controllers/StudentController.php
require_once __DIR__ . '/../Models/Test.php';
require_once __DIR__ . '/../Models/Student.php';
require_once __DIR__ . '/../Core/Session.php';

class StudentController {
    private $testModel; private $studentModel; private $studentId;

    public function __construct() {
        $this->testModel = new Test();
        $this->studentModel = new Student();
        $this->studentId = Session::get('user_id');
    }

    public function dashboard() {
        $data['tests'] = $this->testModel->getAvailableForStudent($this->studentId);
        extract($data);
        require_once __DIR__ . '/../Views/student/dashboard.php';
    }

    public function takeTest() {
        $testId = $_GET['id'];
        
        if (Session::get('attempt_id') === null || Session::get('test_id') != $testId) {
            $attemptId = $this->studentModel->startAttempt($this->studentId, $testId);
            Session::set('attempt_id', $attemptId);
            Session::set('test_id', $testId);

            $testDetails = $this->testModel->getDetailsById($testId);
            $endTime = time() + ($testDetails['duration_minutes'] * 60);
            Session::set('test_end_time', $endTime);
        }

        $data['test'] = $this->testModel->getDetailsById($testId);
        $data['questions'] = $this->testModel->getQuestionsForTest($testId);
        extract($data);
        require_once __DIR__ . '/../Views/student/take-test.php';
    }

    public function submitTest() {
        $attemptId = Session::get('attempt_id');
        if ($attemptId && $_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // --- FIX: Check if 'answers' exists. If not, use an empty array. ---
            $answers = isset($_POST['answers']) ? $_POST['answers'] : [];
            // --- END FIX ---
            
            // Now, pass the safe $answers variable to the model
            $this->studentModel->submitTest($attemptId, $answers);
            
            Session::unset('attempt_id');
            Session::unset('test_end_time');
            Session::unset('test_id');
        }
        
        require_once __DIR__ . '/../Views/student/test-submitted.php';
    }

    public function myResults() {
        $attempts = $this->studentModel->getCompletedAttempts($this->studentId);
        foreach ($attempts as $key => $attempt) {
            $attempts[$key]['max_score'] = $this->testModel->calculateMaxScore($attempt['test_id']);
        }
        $data['attempts'] = $attempts;
        extract($data);
        require_once __DIR__ . '/../Views/student/my-results.php';
    }

    public function viewAttemptDetails() {
        $attemptId = $_GET['id'];
        $details = $this->studentModel->getAttemptDetails($attemptId, $this->studentId);
        if (empty($details)) {
            die("Error: Attempt not found or you do not have permission to view it.");
        }
        $data['details'] = $details;
        extract($data);
        require_once __DIR__ . '/../Views/student/view-attempt-details.php';
    }

    public function showInstructions() {
        $testId = $_GET['id'];
        $data['test'] = $this->testModel->getDetailsById($testId);
        $questions = $this->testModel->getQuestionsForTest($testId);
        $data['question_count'] = count($questions);
        $courseId = $data['test']['course_id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT course_name FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
        $data['course_name'] = $stmt->fetchColumn();
        extract($data);
        require_once __DIR__ . '/../Views/student/test-instructions.php';
    }
}