<?php
require '../includes/init.php';

session_start();
$userId = $_SESSION['user_id'];

global $conn;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputCode = $_POST['verification_code'];
    
    // Check the code against the one in the database
    $sql = "SELECT verification_code FROM tblusers WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$userId]);
    $dbCode = $stmt->fetchColumn();
    
    if ($inputCode == $dbCode) {
        // Mark the user as verified
        $sql = "UPDATE tblusers SET is_verified = 1 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        
        echo "Your email has been verified!";
    } else {
        echo "Invalid verification code.";
    }
}

?>