<?php


require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Core/Session.php';

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

        
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        
        if ($user && password_verify($password, $user['password'])) {
            
            Session::set('user_id', $user['id']);
            Session::set('user_role', $user['role']);
            Session::set('user_name', $user['full_name']);

            
            $this->redirectUser($user['role']);
        } else {
           
            Session::set('error_message', 'Invalid email or password.');
            header('Location: index.php');
            exit();
        }
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
}