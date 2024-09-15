<?php
session_start();

// // بررسی دسترسی ادمین
// if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
//     header("Location: ../login.php");
//     exit();
// }

// خواندن محتوای قالب adminer.tpl
$template = file_get_contents('template/adminer.tpl');

// نمایش محتوای قالب
echo $template;

// پردازش فرم ثبت نام ادمین در صورت ارسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    
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

    // بررسی تکراری نبودن نام کاربری
    $check_sql = "SELECT * FROM tbladmins WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<p style='color: red;'>این نام کاربری قبلاً ثبت شده است.</p>";
    } else {
        // رمزنگاری کلمه عبور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // درج اطلاعات ادمین جدید در پایگاه داده
        $insert_sql = "INSERT INTO tbladmins (username, password, email, phone) VALUES (?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ssss", $username, $hashed_password, $email, $phone);

        if ($insert_stmt->execute()) {
            // ثبت نام موفقیت آمیز بود، هدایت به صفحه index.php
            header("Location: ../index.php");
            exit();
        } else {
            echo "<p style='color: red;'>خطا در ثبت نام ادمین: " . $insert_stmt->error . "</p>";
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>
