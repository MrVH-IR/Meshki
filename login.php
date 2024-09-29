<?php
session_start();

// Read the template content from login.tpl
$template = file_get_contents('template/login.tpl');

$error_message = '';

// Process the login form if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Connect to the database
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
    }

    // Check user information in the database
    $sql = "SELECT * FROM tblusers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            $session_token = bin2hex(random_bytes(32)); // Generate session token
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            // Save user session in users_sessions
            $created_at = date('Y-m-d H:i:s');
            $end_at = date('Y-m-d H:i:s', strtotime($created_at . ' + 4 hours'));
            $insert_session_sql = "INSERT INTO users_sessions (user_id, session_token, created_at, end_at) VALUES (?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_session_sql);
            $insert_stmt->bind_param("isss", $row['id'], $session_token, $created_at, $end_at);
            $insert_stmt->execute();
            $insert_stmt->close();

            // Check if the user is an admin
            $admin_sql = "SELECT * FROM tbladmins WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_sql);
            $admin_stmt->bind_param("s", $username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            
            if ($admin_result->num_rows == 1) {
                $_SESSION['is_admin'] = true;

                // Save admin session in admin_sessions
                $admin_row = $admin_result->fetch_assoc();
                $admin_session_token = bin2hex(random_bytes(32)); // Generate admin session token
                $insert_admin_session_sql = "INSERT INTO admin_sessions (admin_id, session_token, created_at, end_at) VALUES (?, ?, ?, ?)";
                $insert_admin_stmt = $conn->prepare($insert_admin_session_sql);
                $insert_admin_stmt->bind_param("isss", $admin_row['id'], $admin_session_token, $created_at, $end_at);
                $insert_admin_stmt->execute();
                $insert_admin_stmt->close();
            } else {
                $_SESSION['is_admin'] = false;
            }
            
            $admin_stmt->close();
            
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Username or password is incorrect.";
        }
    } else {
        $error_message = "Username or password is incorrect.";
    }

    $stmt->close();
    $conn->close();
}

// Replace error message in the template
$template = str_replace('{{error_message}}', $error_message, $template);

// Display the template content
echo $template;
?>
