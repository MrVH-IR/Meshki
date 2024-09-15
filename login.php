<?php
session_start();

// خواندن محتوای قالب login.tpl
$template = file_get_contents('template/login.tpl');

$error_message = '';

// پردازش فرم ورود در صورت ارسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // اتصال به پایگاه داده
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // بررسی اتصال
    if ($conn->connect_error) {
        die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
    }

    // بررسی اطلاعات کاربر در پایگاه داده
    $sql = "SELECT * FROM tblusers WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // ورود موفقیت‌آمیز
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            
            // بررسی اینکه آیا کاربر ادمین است یا خیر
            $admin_sql = "SELECT * FROM tbladmins WHERE username = ?";
            $admin_stmt = $conn->prepare($admin_sql);
            $admin_stmt->bind_param("s", $username);
            $admin_stmt->execute();
            $admin_result = $admin_stmt->get_result();
            
            if ($admin_result->num_rows == 1) {
                $_SESSION['is_admin'] = true;
            } else {
                $_SESSION['is_admin'] = false;
            }
            
            $admin_stmt->close();
            
            header("Location: index.php");
            exit();
        } else {
            $error_message = "نام کاربری یا رمز عبور اشتباه است.";
        }
    } else {
        $error_message = "نام کاربری یا رمز عبور اشتباه است.";
    }

    $stmt->close();
    $conn->close();
}

// جایگزینی پیام خطا در قالب
$template = str_replace('{{error_message}}', $error_message, $template);

// نمایش محتوای قالب
echo $template;
?>
