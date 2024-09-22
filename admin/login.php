<?php
session_start();

// خواندن محتوای قالب login.tpl
$template = file_get_contents('template/login.tpl');

// نمایش محتوای قالب
echo $template;

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

    // بررسی اطلاعات ادمین در پایگاه داده
    $sql = "SELECT * FROM tbladmins WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // ورود موفقیت‌آمیز
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_username'] = $row['username'];
            $_SESSION['is_admin'] = true;

            // ایجاد توکن سشن
            $session_token = bin2hex(random_bytes(32));
            $created_at = date('Y-m-d H:i:s');
            $end_at = date('Y-m-d H:i:s', strtotime('+5 hours'));

            // ذخیره سشن در پایگاه داده
            $sql = "INSERT INTO admin_sessions (admin_id, session_token, created_at, end_at) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $row['id'], $session_token, $created_at, $end_at);
            $stmt->execute();

            // ذخیره توکن سشن در سشن
            $_SESSION['session_token'] = $session_token;

            header("Location: ../index.php");
            exit();
        } else {
            echo "<p style='color: red;'>نام کاربری یا رمز عبور اشتباه است.</p>";
        }
    } else {
        echo "<p style='color: red;'>نام کاربری یا رمز عبور اشتباه است.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
