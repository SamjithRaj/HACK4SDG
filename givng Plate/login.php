<?php
session_start();
require_once 'db_connect.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $ip_address = $_SERVER['REMOTE_ADDR'];

    try {
        // Check login attempts
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM login_attempts 
                               WHERE ip_address = ? AND success = 0 
                               AND attempt_time > DATE_SUB(NOW(), INTERVAL 15 MINUTE)");
        if (!$stmt) {
            throw new Exception($conn->error);
        }
        
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();
        $result = $stmt->get_result();
        $attempts = $result->fetch_assoc()['count'];

        if ($attempts >= 5) {
            header("Location: login_registration.html?error=too_many_attempts");
            exit();
        }

        // Check user credentials
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        if (!$stmt) {
            throw new Exception($conn->error);
        }
        
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['username'] = $user['org_name'];

                // Redirect to dashboard.php
                header("Location: dashboard.html");
                exit();
            }
        }

        // Record failed login attempt
        $stmt = $conn->prepare("INSERT INTO login_attempts (ip_address, success) VALUES (?, 0)");
        $stmt->bind_param("s", $ip_address);
        $stmt->execute();

        header("Location: login_registration.html?error=invalid_credentials");
        exit();

    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
        header("Location: login_registration.html?error=system_error");
        exit();
    }
}
?>
