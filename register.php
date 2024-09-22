<?php
// خواندن محتوای قالب register.tpl
$template = file_get_contents('template/register.tpl');

// پردازش فرم ثبت نام در صورت ارسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // بررسی یکسان بودن رمز عبور و تکرار آن
    if ($password !== $confirm_password) {
        $error_message = "<p style='color: red;'>رمز عبور و تکرار آن یکسان نیستند.</p>";
        $template = str_replace('</form>', $error_message . '</form>', $template);
    } else {
        // اتصال به پایگاه داده
        $conn = new mysqli("localhost", "root", "", "meshki");

        // بررسی اتصال
        if ($conn->connect_error) {
            die("<p style='color: red;'>خطا در اتصال به پایگاه داده: " . $conn->connect_error . "</p>");
        }

        // بررسی تکراری بودن نام کاربری و ایمیل
        $check_query = "SELECT * FROM tblusers WHERE username = ? OR email = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "<p style='color: red;'>نام کاربری یا ایمیل قبلاً ثبت شده است.</p>";
            $template = str_replace('</form>', $error_message . '</form>', $template);
        } else {
            // هش کردن رمز عبور
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // ذخیره اطلاعات کاربر در پایگاه داده
            $insert_query = "INSERT INTO tblusers (username, email, password) VALUES (?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($insert_stmt->execute()) {
                $success_message = "<p style='color: green;'>ثبت نام با موفقیت انجام شد!</p>";
                $template = str_replace('</form>', $success_message . '</form>', $template);
                header("Location: index.php");
                exit();
            } else {
                $error_message = "<p style='color: red;'>خطا در ثبت نام: " . $conn->error . "</p>";
                $template = str_replace('</form>', $error_message . '</form>', $template);
            }

            $insert_stmt->close();
        }

        $check_stmt->close();
        $conn->close();
    }
}

// نمایش محتوای قالب
echo $template;
?>
