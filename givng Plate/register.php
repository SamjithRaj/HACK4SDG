<?php
session_start();
require_once 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Validate inputs
        if (empty($_POST['orgName']) || empty($_POST['email']) || empty($_POST['password']) || 
            empty($_POST['location']) || empty($_POST['userType'])) {
            throw new Exception("All fields are required");
        }

        $orgName = htmlspecialchars($_POST['orgName'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $location = htmlspecialchars($_POST['location'], ENT_QUOTES, 'UTF-8');
        $userType = htmlspecialchars($_POST['userType'], ENT_QUOTES, 'UTF-8');

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception($conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            header("Location: login_registration.html?error=email_exists");
            exit();
        }

        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (org_name, email, password, location, user_type) VALUES (?, ?, ?, ?, ?)");
        if (!$stmt) {
            throw new Exception($conn->error);
        }
        
        $stmt->bind_param("sssss", $orgName, $email, $password, $location, $userType);

        if ($stmt->execute()) {
            $userId = $stmt->insert_id;
            
            // Set session variables
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_type'] = $userType;
            $_SESSION['username'] = $orgName;

            // Redirect to dashboard
            header("Location: dashboard.html");
            exit();
        } else {
            throw new Exception($conn->error);
        }

    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        header("Location: login_registration.html?error=registration_failed");
        exit();
    }
}
?>
