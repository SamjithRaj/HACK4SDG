<?php
// Database connection
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'foodbridge';

try {
    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Prepare SQL statement
        $sql = "INSERT INTO food_requests (food_item, food_type, quantity, category, dietary_type, location, required_by) 
                VALUES (:food_item, :food_type, :quantity, :category, :dietary_type, :location, :required_by)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':food_item', $_POST['foodItem']);
        $stmt->bindParam(':food_type', $_POST['foodType']);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':category', $_POST['category']);
        $stmt->bindParam(':dietary_type', $_POST['dietaryType']);
        $stmt->bindParam(':location', $_POST['location']);
        $stmt->bindParam(':required_by', $_POST['requiredBy']);
        
        // Execute the statement
        $stmt->execute();
        
        // Redirect with success message
        header("Location: food_log.html?status=success&type=request");
        exit();
    } catch(PDOException $e) {
        // Redirect with error message
        header("Location: food_log.html?status=error&type=request&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
