<?php
session_start();

include '../includes/init.php';

// خواندن محتوای قالب login.tpl
$template = file_get_contents('template/login.tpl');

// نمایش محتوای قالب
echo $template;

// پردازش فرم ورود در صورت ارسال
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $conn = connectToDatabase();

        $sql = "SELECT * FROM tbladmins WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            if ($result['is_admin'] == 0) {
                $_SESSION['pending_admin'] = true;
            }
            if (password_verify($password, $result['password'])) {
                $_SESSION['admin_id'] = $result['id'];
                $_SESSION['admin_username'] = $result['username'];
                $_SESSION['is_admin'] = true;

                $session_token = bin2hex(random_bytes(32));
                $created_at = date('Y-m-d H:i:s');
                $end_at = date('Y-m-d H:i:s', strtotime('+5 hours'));

                try {
                    $sql = "INSERT INTO admin_sessions (admin_id, session_token, created_at, end_at) VALUES (:admin_id, :session_token, :created_at, :end_at)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':admin_id', $result['id'], PDO::PARAM_INT);
                    $stmt->bindParam(':session_token', $session_token, PDO::PARAM_STR);
                    $stmt->bindParam(':created_at', $created_at, PDO::PARAM_STR);
                    $stmt->bindParam(':end_at', $end_at, PDO::PARAM_STR);
                    $stmt->execute();

                    $_SESSION['session_token'] = $session_token;

                    header("Location: ./dashboard.php");
                    exit();
                } catch (PDOException $e) {
                    echo "<p style='color: red;'>خطا در ثبت نشست: " . $e->getMessage() . "</p>";
                }
            } else {
                echo "<p style='color: red;'>نام کاربری یا رمز عبور نامعتبر است.</p>";
            }
        } else {
            echo "<p style='color: red;'>نام کاربری یا رمز عبور نامعتبر است.</p>";
        }
    } catch (PDOException $e) {
        echo "<p style='color: red;'>خطا در اتصال به پایگاه داده: " . $e->getMessage() . "</p>";
    }
}
?>
