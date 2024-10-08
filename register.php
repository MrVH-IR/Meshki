<?php
// Register.tpl
$template = file_get_contents('template/register.tpl');

// Register.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $referral = $_POST['referral'];
    
    // Check if the password and confirmation password are the same
    if ($password !== $confirm_password) {
        $error_message = "<p style='color: red;'>The password and confirmation password are not the same.</p>";
        $template = str_replace('</form>', $error_message . '</form>', $template);
    } else {
        // Connect to the database
        $conn = new mysqli("localhost", "root", "", "meshki");

        // Check connection
        if ($conn->connect_error) {
            die("<p style='color: red;'>Error connecting to the database: " . $conn->connect_error . "</p>");
        }

        // Check for duplicate username and email
        $check_query = "SELECT * FROM tblusers WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "<p style='color: red;'>Username or email already exists.</p>";
            $template = str_replace('</form>', $error_message . '</form>', $template);
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Save user information to the database
            $insert_query = "INSERT INTO tblusers (username, email, password, firstname, lastname, gender, birthdate, referral) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("ssssssss", $username, $email, $hashed_password, $firstname, $lastname, $gender, $birthdate, $referral);

            if ($insert_stmt->execute()) {
                // Login The User After Registration
                session_start();
                $_SESSION['user_id'] = $insert_stmt->insert_id;
                $_SESSION['username'] = $username;
                
                // Redirect the user to the profile page
                header("Location: user/profile.php");
                exit();
            } else {
                $error_message = "<p style='color: red;'>Registration error: " . $conn->error . "</p>";
                $template = str_replace('</form>', $error_message . '</form>', $template);
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
        $conn->close();
    }
}

// Display the template content
echo $template;
?>
