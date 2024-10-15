<?php
session_start();

// حذف تمام متغیرهای سشن
$_SESSION = array();

// از بین بردن سشن
session_destroy();

// اطمینان از پاک شدن تمام متغیرهای سشن
unset($_SESSION['pending_admin']);
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);
unset($_SESSION['is_admin']);
unset($_SESSION['session_token']);

// تنظیم کوکی سشن برای منقضی شدن
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// هدایت کاربر به صفحه ورود
header("Location: login.php");
exit();
?>
