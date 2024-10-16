<?php
require '../includes/init.php';

session_start();
$userId = $_SESSION['user_id'];
$conn = connectToDatabase();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inputCode = $_POST['verification_code'];
    
    // Check the code against the one in the database
    try {
        $sql = "SELECT verification_code FROM tblusers WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$userId]);
        $dbCode = $stmt->fetchColumn();
    } catch (PDOException $e) {
        die("Error fetching verification code: " . $e->getMessage());
    }
    
    if ($inputCode == $dbCode) {
        // Mark the user as verified
        try {
            $sql = "UPDATE tblusers SET is_verified = 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$userId]);
        } catch (PDOException $e) {
            die("Error updating verification status: " . $e->getMessage());
        }
        
        echo "Your email has been verified!";
        echo "<script>setTimeout(function() { window.location.href = 'profile.php'; }, 2000);</script>";
    } else {
        echo "Invalid verification code.";
    }
}

?>