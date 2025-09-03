<?php
// src/Controllers/AdminController.php

require_once __DIR__ . '/../Models/Course.php';
require_once __DIR__ . '/../Models/User.php'; // Required for user management
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Models/Staff.php';
require_once __DIR__ . '/../Models/Admin.php'; 
require_once __DIR__ . '/../Models/Question.php';

class AdminController {
    private $courseModel;
    private $userModel; // Property for the User model
    private $staffModel; // Property for the Staff model
    private $adminModel;
    private $questionModel;

    

    public function __construct() {
        $this->courseModel = new Course();
        $this->userModel = new User(); // Instantiate the User model
        $this->staffModel = new Staff();
        $this->adminModel = new Admin();
        $this->questionModel = new Question();
    }

    public function dashboard() {
        $data['stats'] = $this->adminModel->getDashboardStats();
        $data['chart_data'] = $this->adminModel->getRecentTestAttemptsChartData();
        extract($data);
        require_once __DIR__ . '/../Views/admin/dashboard.php';
    }

    // --- Course Management Methods ---

    public function manageCourses() {
        $courses = $this->courseModel->getAll();
        // Note: The new controller used 'manage-courses.php'. Adjust if needed.
        require_once __DIR__ . '/../Views/admin/manage-courses.php';
    }

   public function addCourse() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $this->courseModel->create(
            $_POST['course_name'],
            $_POST['course_code'],
            $_POST['description'], // Add this
            $_POST['status']       // Add this
        );
    }
    header('Location: index.php?action=manage-courses');
    exit();
    }

    public function updateCourse() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->courseModel->update(
                $_POST['course_id'],
                $_POST['course_name'],
                $_POST['course_code'],
                $_POST['description'], // Add this
                $_POST['status']       // Add this
            );
        }
        header('Location: index.php?action=manage-courses');
        exit();
    }
    public function deleteCourse() {
        if (isset($_GET['id'])) {
            $this->courseModel->delete($_GET['id']);
        }
        header('Location: index.php?action=manage-courses');
        exit();
    }

    // --- User Management Methods ---

    public function manageUsers() {
        $data = [];

        $data['users'] = $this->userModel->getAll();

        $data['courses'] = $this->courseModel->getAll();

        extract($data);
        
        require_once __DIR__ . '/../Views/admin/manage-users.php';
    }
    
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->create(
                $_POST['full_name'],
                $_POST['email'],
                $_POST['password'],
                $_POST['role'],
                $_POST['course_id'] ?? null
            );
        }
        header('Location: index.php?action=manage-users');
        exit();
    }

    public function updateUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->userModel->update(
                $_POST['user_id'],
                $_POST['full_name'],
                $_POST['email'],
                $_POST['role'],
                $_POST['password'] ?? null,
                $_POST['course_id'] ?? null
            );
        }
        header('Location: index.php?action=manage-users');
        exit();
    }

    public function deleteUser() {
        if (isset($_GET['id'])) {
            $this->userModel->delete($_GET['id']);
        }
        header('Location: index.php?action=manage-users');
        exit();
    }
     public function assignCourses() {
        $staff_id = $_GET['id'];
        $data['staff'] = $this->userModel->findById($staff_id);
        $data['all_courses'] = $this->courseModel->getAll();
        $data['assigned_courses'] = $this->staffModel->getAssignedCourseIds($staff_id);
        extract($data);
        require_once __DIR__ . '/../Views/admin/assign-courses.php';
    }

    public function updateAssignedCourses() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $staffId = $_POST['staff_id'];
            // If no checkboxes are checked, $_POST['course_ids'] will not be set
            $courseIds = isset($_POST['course_ids']) ? $_POST['course_ids'] : [];
            $this->staffModel->updateAssignedCourses($staffId, $courseIds);
        }
        header('Location: index.php?action=manage-users');
        exit();
    }

    public function importStudentsCsv() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['student_csv'])) {
        $file = $_FILES['student_csv'];

        // 1. Basic File Validation
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['import_feedback'] = ['type' => 'danger', 'message' => 'File upload error.'];
        } else {
            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
            if (strtolower($fileExtension) !== 'csv') {
                $_SESSION['import_feedback'] = ['type' => 'danger', 'message' => 'Invalid file type. Please upload a .csv file.'];
            } else {
                // 2. Process the file
                $result = $this->userModel->createBulkFromCsv($file['tmp_name']);

                // 3. Set feedback message based on the result
                if (!empty($result['failures'])) {
                    $message = $result['error'] . ' Please correct the following errors: <ul>';
                    foreach ($result['failures'] as $failure) {
                        $message .= '<li>' . htmlspecialchars($failure) . '</li>';
                    }
                    $message .= '</ul>';
                    $_SESSION['import_feedback'] = ['type' => 'danger', 'message' => $message];
                } else {
                    $_SESSION['import_feedback'] = ['type' => 'success', 'message' => 'Successfully imported ' . $result['success'] . ' students.'];
                }
            }
        }
    } else {
        $_SESSION['import_feedback'] = ['type' => 'danger', 'message' => 'No file was uploaded.'];
    }

    header('Location: index.php?action=manage-users');
    exit();
}

 public function viewAllResults() {
        // Get the current filter values from the URL, if they exist
        $filters = [
            'course_id'  => $_GET['course_id'] ?? null,
            'staff_id'   => $_GET['staff_id'] ?? null,
            'student_id' => $_GET['student_id'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date'   => $_GET['end_date'] ?? null
        ];

        // Prepare all data needed for the view
        $data['filters'] = $filters; // To pre-fill the form
        $data['courses'] = $this->adminModel->getAllCourses();
        $data['staff'] = $this->adminModel->getAllStaff();
        $data['students'] = $this->adminModel->getAllStudents();
        $data['results'] = $this->adminModel->getFilteredResults($filters);

        extract($data);
        require_once __DIR__ . '/../Views/admin/view-all-results.php';
    }

    public function exportResultsCsv() {
    // Step 1: Get the exact same filters from the URL as the view page.
    $filters = [
        'course_id'  => $_GET['course_id'] ?? null,
        'staff_id'   => $_GET['staff_id'] ?? null,
        'student_id' => $_GET['student_id'] ?? null,
        'start_date' => $_GET['start_date'] ?? null,
        'end_date'   => $_GET['end_date'] ?? null
    ];

    // Step 2: Fetch the filtered data using the existing model method.
    $results = $this->adminModel->getFilteredResults($filters);

    // Step 3: Set the HTTP headers to trigger a file download.
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="test_results_' . date('Y-m-d') . '.csv"');

    // Step 4: Open a file handle to the PHP output stream.
    $handle = fopen('php://output', 'w');

    // Step 5: Write the header row to the CSV file.
    fputcsv($handle, [
        'Student Name',
        'Test Title',
        'Course',
        'Staff',
        'Date Completed',
        'Score',
        'Status'
    ]);

    // Step 6: Loop through the results and write each row to the CSV.
    foreach ($results as $result) {
        fputcsv($handle, [
            $result['student_name'],
            $result['test_title'],
            $result['course_name'],
            $result['staff_name'],
            date('Y-m-d H:i:s', strtotime($result['end_time'])), // Format date for clarity
            $result['score'] ?? 'N/A',
            ucfirst($result['status']) // e.g., 'Graded' or 'Completed'
        ]);
    }

    // Step 7: Close the file handle.
    fclose($handle);

    // Step 8: Stop the script to prevent any other output (like HTML) from being sent.
    exit();
    }

     public function manageQuestions() {
        $filters = [
            'course_id' => $_GET['course_id'] ?? null,
            'staff_id'  => $_GET['staff_id'] ?? null
        ];
        
        $data['filters'] = $filters;
        $data['courses'] = $this->adminModel->getAllCourses();
        $data['staff'] = $this->adminModel->getAllStaff();
        $data['questions'] = $this->questionModel->getAllFiltered($filters);

        extract($data);
        require_once __DIR__ . '/../Views/admin/manage-questions.php';
    }

    public function addQuestion() {
        // This is almost identical to the Staff controller method,
        // but we take staff_id from the form, not the session.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $attachmentPath = null;
            if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/attachments/';
                $uniqueName = uniqid('q_') . '_' . basename($_FILES['attachment']['name']);
                $targetFile = $uploadDir . $uniqueName;
                if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetFile)) {
                    $attachmentPath = 'public/uploads/attachments/' . $uniqueName;
                }
            }
            $this->questionModel->create($_POST, $attachmentPath);
        }
        header('Location: index.php?action=manage-questions');
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
