    <?php

    require_once __DIR__ . '/../src/Core/Session.php';
    Session::start();

    $action = isset($_GET['action']) ? $_GET['action'] : 'show_login';

    if ($action == 'show_login' || $action == 'login') {
        require_once __DIR__ . '/../src/Controllers/AuthController.php';
        $authController = new AuthController();
        if ($action == 'login') {
            $authController->login();
        } else {
            $authController->showLoginForm();
        }
        return; // Stop script execution
    }

    if (Session::get('user_id') === null) {
        header('Location: index.php?action=show_login');
        exit();
    }

    $role = Session::get('user_role');

    switch ($action) {
        case 'logout':
            require_once __DIR__ . '/../src/Controllers/AuthController.php';
            $authController = new AuthController();
            $authController->logout();
            break;

    case 'admin-dashboard':
    case 'manage-courses':
    case 'add-course':
    case 'update-course':
    case 'delete-course':
    case 'manage-users':
    case 'add-user':
    case 'update-user':
    case 'delete-user':
    case 'assign-courses':
    case 'update-assigned-courses':
    case 'import-students-csv':
    case 'view-all-results':
    case 'export-results-csv':
        if ($role !== 'admin') die("Access Denied.");
        require_once __DIR__ . '/../src/Controllers/AdminController.php';
        $controller = new AdminController();
        
        if ($action === 'admin-dashboard') $controller->dashboard();
        if ($action === 'manage-courses') $controller->manageCourses();
        if ($action === 'add-course') $controller->addCourse();
        if ($action === 'update-course') $controller->updateCourse();
        if ($action === 'delete-course') $controller->deleteCourse();
        if ($action === 'manage-users') $controller->manageUsers();
        if ($action === 'add-user') $controller->addUser();
        if ($action === 'update-user') $controller->updateUser();
        if ($action === 'delete-user') $controller->deleteUser();
        if ($action === 'assign-courses') $controller->assignCourses();
        if ($action === 'update-assigned-courses') $controller->updateAssignedCourses();
        if ($action === 'import-students-csv') $controller->importStudentsCsv();
        if ($action === 'view-all-results') $controller->viewAllResults();
        if ($action === 'export-results-csv') $controller->exportResultsCsv();
        break;
        
    // --- STAFF-ONLY ROUTES ---
    case 'staff-dashboard':
    case 'manage-tests':
    case 'get-questions-for-course': 
    case 'create-test':
    case 'update-test-status': 
    case 'delete-test':
    case 'view-results': 
    case 'grade-test':   
    case 'save-grades': 
        if ($role !== 'staff') die("Access Denied.");
        require_once __DIR__ . '/../src/Controllers/StaffController.php';
        $controller = new StaffController();

        if ($action === 'staff-dashboard') $controller->dashboard();
        if ($action === 'manage-tests') $controller->manageTests();
        if ($action === 'get-questions-for-course') $controller->getQuestionsForCourse();
        if ($action === 'create-test') $controller->createTest();
        if ($action === 'update-test-status') $controller->updateTestStatus(); 
        if ($action === 'delete-test') $controller->deleteTest();
        if ($action === 'view-results') $controller->viewResults(); 
        if ($action === 'grade-test') $controller->gradeTest();     
        if ($action === 'save-grades') $controller->saveGrades();
        break;

    // --- SHARED QUESTION MANAGEMENT ROUTES ---
    case 'manage-questions':
    case 'add-question':
    case 'edit-question':   
    case 'update-question': 
    case 'delete-question': 
        // This block is accessible to both Admin and Staff
        if ($role === 'admin') {
            require_once __DIR__ . '/../src/Controllers/AdminController.php';
            $controller = new AdminController();
        } elseif ($role === 'staff') {
            require_once __DIR__ . '/../src/Controllers/StaffController.php';
            $controller = new StaffController();
        } else {
            die("Access Denied."); // If any other role tries to access
        }
        
        // Call the appropriate method on the loaded controller
        if ($action === 'manage-questions') $controller->manageQuestions();
        if ($action === 'add-question') $controller->addQuestion();
        if ($action === 'edit-question') $controller->editQuestion();
        if ($action === 'update-question') $controller->updateQuestion(); 
        if ($action === 'delete-question') $controller->deleteQuestion(); 
        break;


    // --- STUDENT ROUTES ---
    case 'student-dashboard':
    case 'take-test':
    case 'submit-test':
    case 'my-results':
    case 'view-attempt-details':
    case 'show-instructions':
        if ($role !== 'student') die("Access Denied.");
        require_once __DIR__ . '/../src/Controllers/StudentController.php';
        $controller = new StudentController();

        if ($action === 'student-dashboard') $controller->dashboard();
        if ($action === 'take-test') $controller->takeTest();
        if ($action === 'submit-test') $controller->submitTest();
        if ($action === 'my-results') $controller->myResults();
        if ($action === 'view-attempt-details') $controller->viewAttemptDetails();
        if ($action === 'show-instructions') $controller->showInstructions();
        break;

    default:
        if ($role === 'admin') header('Location: index.php?action=admin-dashboard');
        elseif ($role === 'staff') header('Location: index.php?action=staff-dashboard');
        elseif ($role === 'student') header('Location: index.php?action=student-dashboard');
        else header('Location: index.php?action=show_login');
        break;
}