<?php
// Database configuration
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'giving_plate';

    // Create connection using mysqli
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }


$conn->set_charset("utf8mb4");

error_reporting(0);
ini_set('display_errors', 0);
?>
