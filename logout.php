<?php
session_start();
include 'includes/init.php';

$conn = connectToDatabase();

try {
    // حذف سشن کاربر از جدول users_sessions
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $delete_session_sql = "DELETE FROM users_sessions WHERE user_id = :user_id"; // اصلاح کوئری حذف
        $stmt = $conn->prepare($delete_session_sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // حذف سشن ادمین از جدول admin_sessions
    if (isset($_SESSION['is_admin'])) {
        $admin_id = $_SESSION['is_admin'];
        $delete_session_sql = "DELETE FROM admin_sessions WHERE admin_id = :admin_id";
        $stmt = $conn->prepare($delete_session_sql);
        $stmt->bindParam(':admin_id', $admin_id, PDO::PARAM_INT);
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo "Error deleting session: " . $e->getMessage();
}

// از بین بردن تمام متغیرهای جلسه
$_SESSION = array();

// اگر از کوکی جلسه استفاده می‌شود، آن را نیز حذف کنید
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// نابود کردن جلسه
session_destroy();

// هدایت کاربر به صفحه اصلی
header("Location: index.php");
exit();
?>
