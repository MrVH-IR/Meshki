<?php
session_start();

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
