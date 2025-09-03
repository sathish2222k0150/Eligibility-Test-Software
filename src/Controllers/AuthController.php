<?php


require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Session.php';
require_once __DIR__ . '/../Models/student.php';
require_once __DIR__ . '/../Models/Course.php';

class AuthController {

    public function showLoginForm() {
       
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login() {
    Session::start();
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php');
        exit();
    }
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Step 1: Check the 'users' table (for Admin/Staff)
    $userModel = new User();
    $user = $userModel->findByEmail($email);
    if ($user && password_verify($password, $user['password'])) {
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['full_name']);
        $this->redirectUser($user['role']);
    }

    // Step 2: If not found, check the 'students' table
    $studentModel = new Student();
    $student = $studentModel->findByEmail($email);
    if ($student && password_verify($password, $student['password'])) {
        Session::set('student_id', $student['id']); // Use 'student_id' for clarity
        Session::set('user_role', 'student');
        Session::set('user_name', $student['full_name']);
        $this->redirectUser('student');
    }
    
    // Step 3: If not found in either table, fail
    Session::set('error_message', 'Invalid email or password.');
    header('Location: index.php');
    exit();
}

    public function logout() {
        Session::start();
        Session::destroy();
        header('Location: index.php');
        exit();
    }

    private function redirectUser($role) {
        switch ($role) {
            case 'admin':
                header('Location: index.php?action=admin-dashboard');
                break;
            case 'staff':
                header('Location: index.php?action=staff-dashboard');
                break;
            case 'student':
                header('Location: index.php?action=student-dashboard');
                break;
            default:
                header('Location: index.php'); // Fallback
                break;
        }
        exit();
    }

    public function showRegistrationForm() {
        // We need to fetch the list of courses for the dropdown
        $courseModel = new Course();
        $data['courses'] = $courseModel->getAll(); // Assuming getAll() gets active courses
        extract($data);
        require_once __DIR__ . '/../Views/auth/register.php';
    }

    public function register() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: index.php?action=show-register');
        exit();
    }

    // We need session feedback for our messages
    Session::start();

    $studentModel = new Student();
    $email = $_POST['email'];
    $courseId = $_POST['course_id'];
    $fullName = $_POST['full_name'];
    $password = $_POST['password'];

    // Check if a student with this email already exists
    $existingStudent = $studentModel->findByEmail($email);

    if ($existingStudent) {
        // --- SCENARIO 2: STUDENT ALREADY EXISTS ---
        $studentId = $existingStudent['id'];

        // Check if they are already enrolled in THIS course
        if ($studentModel->isEnrolled($studentId, $courseId)) {
            // Sub-scenario 2a: Already enrolled
            Session::set('register_feedback', ['type' => 'danger', 'message' => 'You are already registered for this course. Please log in.']);
            header('Location: index.php?action=show-register');
            exit();
        } else {
            // Sub-scenario 2b: Enroll them in the new course
            if ($studentModel->enrollInCourse($studentId, $courseId)) {
                Session::set('register_feedback', ['type' => 'success', 'message' => 'Success! You have been enrolled in the new course. Please log in to access it.']);
                header('Location: index.php?action=show_login'); // Redirect to login
                exit();
            } else {
                Session::set('register_feedback', ['type' => 'danger', 'message' => 'An error occurred while enrolling you in the new course.']);
                header('Location: index.php?action=show-register');
                exit();
            }
        }
    } else {
        // --- SCENARIO 1: BRAND NEW STUDENT ---
        $studentId = $studentModel->register($fullName, $email, $password, $courseId);
        
        if ($studentId) {
            // Automatically log the new student in
            Session::set('student_id', $studentId);
            Session::set('user_role', 'student');
            Session::set('user_name', $fullName);
            header('Location: index.php?action=student-dashboard');
            exit();
        } else {
            Session::set('register_feedback', ['type' => 'danger', 'message' => 'An unexpected error occurred during registration.']);
            header('Location: index.php?action=show-register');
            exit();
        }
    }
}
}