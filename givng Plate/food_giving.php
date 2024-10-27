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
        $sql = "INSERT INTO food_donations (food_item, category, food_type, quantity, location, cooking_time, best_before) 
                VALUES (:food_item, :category, :food_type, :quantity, :location, :cooking_time, :best_before)";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':food_item', $_POST['foodItem']);
        $stmt->bindParam(':category', $_POST['category']);
        $stmt->bindParam(':food_type', $_POST['foodType']);
        $stmt->bindParam(':quantity', $_POST['quantity']);
        $stmt->bindParam(':location', $_POST['location']);
        $stmt->bindParam(':cooking_time', $_POST['cookingTime']);
        $stmt->bindParam(':best_before', $_POST['bestBefore']);
        
        // Execute the statement
        $stmt->execute();
        
        // Redirect with success message
        header("Location: food_log.html?status=success&type=donation");
        exit();
    } catch(PDOException $e) {
        // Redirect with error message
        header("Location: food_log.html?status=error&type=donation&message=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
