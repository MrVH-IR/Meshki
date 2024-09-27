<?php
session_start();
include '../configure.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // دریافت مسیر تصویر فعلی از دیتابیس
    $query = "SELECT imgpath FROM tblusers WHERE id = '$user_id'";
    $result = $conn->query($query);
    $user_data = $result->fetch_assoc();
    $current_imgpath = $user_data['imgpath'];
    
    // حذف تصویر از سرور
    if (!empty($current_imgpath) && file_exists($current_imgpath)) {
        unlink($current_imgpath);
    }
    
    // به‌روزرسانی دیتابیس
    $update_query = "UPDATE tblusers SET imgpath = NULL WHERE id = '$user_id'";
    if ($conn->query($update_query) === TRUE) {
        echo '<p style="color: green;">تصویر پروفایل با موفقیت حذف شد.</p>';
    } else {
        echo '<p style="color: red;">خطا در حذف تصویر پروفایل: ' . $conn->error . '</p>';
    }
    
    // بازگشت به صفحه پروفایل
    header("Location: profile.php");
    exit();
} else {
    echo '<p style="color: red;">درخواست نامعتبر.</p>';
}
?>
