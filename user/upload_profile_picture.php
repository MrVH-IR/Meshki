<?php
session_start();
include '../configure.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Process the profile picture upload form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $user_id = $_SESSION['user_id'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Check if the file is an image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check === false) {
        die("File is not an image.");
    }

    // File size limits
    if ($_FILES["profile_picture"]["size"] > 500000) {
        die("Size is too large.");
    }

    // Allowed file types
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
    }

    // Move the file to the target directory
    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
        // Update the image path in the database
        $sql = "UPDATE tblusers SET imgpath = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $target_file, $user_id);
        $stmt->execute();
        $stmt->close();
        
        echo "Profile picture uploaded successfully.";
    } else {
        die("Sorry, there was an error uploading your file.");
    }
}
header("Location: profile.php");
?>
