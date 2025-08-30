<?php
// src/Controllers/StaffController.php
require_once __DIR__ . '/../Models/Staff.php';
require_once __DIR__ . '/../Models/Question.php';
require_once __DIR__ . '/../Models/Test.php';
require_once __DIR__ . '/../Core/Session.php';

class StaffController {
    private $staffModel;
    private $questionModel;
    private $staffId;
    private $assignedCourseIds;
    private $testModel;

    public function __construct() {
        // --- ADD THIS SESSION CHECK ---
        $this->staffId = Session::get('user_id');
        
        // If there's no staff ID in the session, they are not logged in.
        // Redirect them to the login page and stop all further execution.
        if (!$this->staffId) {
            // Assuming your login action is 'login'
            header('Location: index.php?action=login'); 
            exit();
        }
        // --- END OF SESSION CHECK ---

        // The rest of the constructor will now only run if the user is logged in.
        $this->staffModel = new Staff();
        $this->questionModel = new Question();
        
        // Fetch assigned courses once and store them
        $this->assignedCourseIds = $this->staffModel->getAssignedCourseIds($this->staffId);

        $this->testModel = new Test();
    }

    public function dashboard() {
        $data['stats'] = $this->staffModel->getDashboardStats($this->staffId);
        $data['recent_submissions'] = $this->staffModel->getRecentSubmissions($this->staffId);
        extract($data);
        require_once __DIR__ . '/../Views/staff/dashboard.php';
    }

    public function manageQuestions() {
        // Get all questions for the courses this staff member is assigned to
        $data['questions'] = $this->questionModel->getByCourseIds($this->assignedCourseIds);
        
        // Get course details for the dropdown in the "add question" form
        $data['my_courses'] = $this->getAssignedCourseDetails();
        
        extract($data);
        require_once __DIR__ . '/../Views/staff/manage-questions.php';
    }

    public function addQuestion() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Security check: ensure staff is assigned to the course
            if (!in_array($_POST['course_id'], $this->assignedCourseIds)) {
                die("ACCESS DENIED: You are not assigned to this course.");
            }

            $attachmentPath = null;
            $uploadOk = 1;

            // --- FILE UPLOAD LOGIC ---
            // Check if a file was actually uploaded
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
                
                $uploadDir = __DIR__ . '/../../public/uploads/attachments/';
                // Create a unique filename to prevent overwriting
                $uniqueName = uniqid('q_') . '_' . basename($_FILES['attachment']['name']);
                $targetFile = $uploadDir . $uniqueName;
                
                // Check file size (e.g., 5MB limit)
                if ($_FILES['attachment']['size'] > 5000000) {
                    echo "Sorry, your file is too large."; // In a real app, use session feedback
                    $uploadOk = 0;
                }
                
                // Allow certain file formats
                $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
                    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                    $uploadOk = 0;
                }

                // If all checks pass, try to upload file
                if ($uploadOk == 1) {
                    if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                        // Store the relative path for the database
                        $attachmentPath = 'public/uploads/attachments/' . $uniqueName;
                    } else {
                        echo "Sorry, there was an error uploading your file."; // Use session feedback
                        $uploadOk = 0;
                    }
                }
            }
            // --- END OF FILE UPLOAD LOGIC ---

            if ($uploadOk == 1) {
                $_POST['staff_id'] = $this->staffId;
                // Pass the data and the file path to the model
                $this->questionModel->create($_POST, $attachmentPath);
            }
        }
        header('Location: index.php?action=manage-questions');
        exit();
    }

    private function getAssignedCourseDetails() {
        if (empty($this->assignedCourseIds)) {
            return [];
        }
        $db = Database::getInstance()->getConnection();
        $in_placeholders = implode(',', array_fill(0, count($this->assignedCourseIds), '?'));
        $stmt = $db->prepare("SELECT id, course_name FROM courses WHERE id IN ($in_placeholders)");
        $stmt->execute($this->assignedCourseIds);
        return $stmt->fetchAll();
    }
    
    public function manageTests() {
        $data['tests'] = $this->testModel->getByStaffId($this->staffId); 
        $data['my_courses'] = $this->getAssignedCourseDetails();
        extract($data);
        require_once __DIR__ . '/../Views/staff/manage-tests.php';
    }

    public function getQuestionsForCourse() {
        $courseId = $_POST['course_id'];
        if (!in_array($courseId, $this->assignedCourseIds)) die("Access Denied.");
        
        $allQuestions = $this->questionModel->getByCourseIds([$courseId]);
        $data['questions'] = $allQuestions;
        extract($data);
        require_once __DIR__ . '/../Views/staff/ajax-questions.php';
    }

    public function createTest() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (empty($_POST['question_ids'])) {
            // In a real app, use session feedback to show an error
            die("Error: You must select at least one question for the test.");
        }
        $_POST['staff_id'] = $this->staffId;
        $this->testModel->create($_POST, $_POST['question_ids']);
    }
    header('Location: index.php?action=manage-tests');
    exit();
    }

    public function updateTestStatus() {
    if (isset($_GET['id']) && isset($_GET['status'])) {
        // Add a security check here to ensure this test belongs to the logged-in staff
        $this->testModel->updateStatus($_GET['id'], $_GET['status']);
    }
    header('Location: index.php?action=manage-tests');
    exit();
    }

    public function deleteTest() {
    if (isset($_GET['id'])) {
        // Add a security check here as well
        $this->testModel->delete($_GET['id']);
    }
    header('Location: index.php?action=manage-tests');
    exit();
    }
    // In StaffController.php, add these methods:

    public function viewResults() {
        $data['attempts'] = $this->staffModel->getCompletedAttempts($this->staffId);
        extract($data);
        require_once __DIR__ . '/../Views/staff/view-results.php';
    }

    public function gradeTest() {
        $attemptId = $_GET['id'];
        // You could add a security check here to ensure the staff owns this test attempt
        $data['details'] = $this->staffModel->getAttemptDetails($attemptId);
        extract($data);
        require_once __DIR__ . '/../Views/staff/grade-test.php';
    }

    public function saveGrades() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $attemptId = $_POST['attempt_id'];
        $grades = $_POST['grades'] ?? []; // Grades for short answers
        
        try {
            // Call the model and store the result (true on success, false on failure)
            $is_successful = $this->staffModel->saveAndFinalizeGrades($attemptId, $grades);
        } catch (Exception $e) {
            // --- DEBUGGING CHECK ---
            // If an exception occurs, stop everything and show an error.
            die("<h1>A new error was found!</h1><p><b>Please copy this entire message and paste it in your reply.</b></p><pre>Database Error: " . $e->getMessage() . "</pre>");
        }

        // --- DEBUGGING CHECK ---
        // If the save operation failed, stop everything and show an error.
        if (!$is_successful) {
            die("<h1>A new error was found!</h1><p><b>Please copy this entire message and paste it in your reply.</b></p><pre>Database Error: Save operation failed.</pre>");
        }
        
        // --- END DEBUGGING CHECK ---
    }
    
        // If the code reaches here, it means the save was successful. Redirect.
        header('Location: index.php?action=view-results');
        exit();
    }

    public function deleteQuestion() {
    if (isset($_GET['id'])) {
        // TODO: Add security check to ensure question belongs to this staff member
        $this->questionModel->delete($_GET['id']);
    }
    header('Location: index.php?action=manage-questions');
    exit();
    }

    public function editQuestion() {
        if (isset($_POST['id'])) {
            // TODO: Add security check
            $questionData = $this->questionModel->findById($_POST['id']);
            // Send data back as JSON
            header('Content-Type: application/json');
            echo json_encode($questionData);
            exit();
        }
    }

    public function updateQuestion() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // TODO: Add security check
        
        // Handle file upload logic (similar to addQuestion)
        $attachmentPath = null;
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/attachments/';
            $uniqueName = uniqid('q_') . '_' . basename($_FILES['attachment']['name']);
            $targetFile = $uploadDir . $uniqueName;
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                $attachmentPath = 'public/uploads/attachments/' . $uniqueName;
            }
        }

        $this->questionModel->update($_POST, $attachmentPath);
    }
    header('Location: index.php?action=manage-questions');
    exit();
    }

}