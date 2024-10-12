<?php
session_start();
include '../includes/init.php';
global $conn;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    try {
        // دریافت مسیر تصویر فعلی از دیتابیس
        $query = "SELECT imgpath FROM tblusers WHERE id = :user_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_imgpath = $user_data['imgpath'];
        
        // حذف تصویر از سرور
        if (!empty($current_imgpath) && file_exists($current_imgpath)) {
            unlink($current_imgpath);
        }
        
        // به‌روزرسانی دیتابیس
        $update_query = "UPDATE tblusers SET imgpath = NULL WHERE id = :user_id";
        $stmt = $conn->prepare($update_query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        echo '<p style="color: green;">تصویر پروفایل با موفقیت حذف شد.</p>';
    } catch (PDOException $e) {
        echo '<p style="color: red;">خطا در حذف تصویر پروفایل: ' . $e->getMessage() . '</p>';
    }
    
    // بازگشت به صفحه پروفایل
    header("Location: profile.php");
    exit();
} else {
    echo '<p style="color: red;">درخواست نامعتبر.</p>';
}
?>
