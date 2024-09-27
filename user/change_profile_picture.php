<?php
session_start();
include '../configure.php';
include 'profile.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Get Current Image Path From Database
    $query = "SELECT imgpath FROM tblusers WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $current_imgpath = $user_data['imgpath'];
    
    // Check If File Is Uploaded
    if (isset($_FILES["change_profile_picture"]) && is_array($_FILES["change_profile_picture"])) {
        $file = $_FILES["change_profile_picture"];
        
        if ($file["error"] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            
            // Check If Image Is Valid
            $check = getimagesize($file["tmp_name"]);
            if($check !== false) {
                $uploadOk = 1;
            } else {
                echo "Selected File Is Not An Image.";
                $uploadOk = 0;
            }
            
            // If Upload Is Allowed
            if ($uploadOk == 1) {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    // Delete Previous Image From Server
                    if (!empty($current_imgpath) && file_exists($current_imgpath)) {
                        unlink($current_imgpath);
                    }
                    
                    // Update Image Path In Database
                    $update_query = "UPDATE tblusers SET imgpath = ? WHERE id = ?";
                    $stmt = $conn->prepare($update_query);
                    $stmt->bind_param("si", $target_file, $user_id);
                    if ($stmt->execute()) {
                        echo "Profile Picture Successfully Changed.";
                    } else {
                        echo "Error In Updating Database: " . $conn->error;
                    }
                } else {
                    echo "Error In Uploading File " . $file["error"];
                }
            }
        } else {
            echo "Error In Uploading File " . $file["error"];
        }
    } else {
        echo "No File Selected For Upload.";
    }
    
    // Redirect To Profile Page
    header("Location: profile.php");
    exit();
}
?>