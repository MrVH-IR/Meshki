<?php
// Register.tpl
$template = file_get_contents('template/register.tpl');
include 'includes/init.php';
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
        $conn = connectToDatabase();

        if ($conn) {
            // Check for duplicate username and email
            try {
                $check_query = "SELECT * FROM tblusers WHERE username = :username OR email = :email";
                $check_stmt = $conn->prepare($check_query);
                $check_stmt->bindParam(':username', $username);
                $check_stmt->bindParam(':email', $email);
                $check_stmt->execute();
                $result = $check_stmt->fetchAll();
            } catch (PDOException $e) {
                die("<p style='color: red;'>Error checking for duplicate username and email: " . $e->getMessage() . "</p>");
            }

            if (count($result) > 0) {
                $error_message = "<p style='color: red;'>Username or email already exists.</p>";
                $template = str_replace('</form>', $error_message . '</form>', $template);
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Save user information to the database
                try {
                    $insert_query = "INSERT INTO tblusers (username, email, password, firstname, lastname, gender, birthdate, referral) VALUES (:username, :email, :password, :firstname, :lastname, :gender, :birthdate, :referral)";
                    $insert_stmt = $conn->prepare($insert_query);
                    $insert_stmt->bindParam(':username', $username);
                    $insert_stmt->bindParam(':email', $email);
                    $insert_stmt->bindParam(':password', $hashed_password);
                    $insert_stmt->bindParam(':firstname', $firstname);
                    $insert_stmt->bindParam(':lastname', $lastname);
                    $insert_stmt->bindParam(':gender', $gender);
                    $insert_stmt->bindParam(':birthdate', $birthdate);
                    $insert_stmt->bindParam(':referral', $referral);

                    if ($insert_stmt->execute()) {
                        // Login The User After Registration
                        session_start();
                        $_SESSION['user_id'] = $conn->lastInsertId();
                        $_SESSION['username'] = $username;
                        
                        // Close the database connection
                        $conn = null;

                        // Redirect the user to the profile page
                        header("Location: user/profile.php");
                        exit();
                    } else {
                        $error_message = "<p style='color: red;'>Registration error: " . $conn->errorInfo()[2] . "</p>";
                        $template = str_replace('</form>', $error_message . '</form>', $template);
                    }
                } catch (PDOException $e) {
                    die("<p style='color: red;'>Error inserting user into the database: " . $e->getMessage() . "</p>");
                }
            }
        } else {
            die("<p style='color: red;'>Database connection failed.</p>");
        }
    }
}

// Display the template content
echo $template;
?>
