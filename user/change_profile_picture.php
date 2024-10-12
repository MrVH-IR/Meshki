<?php
session_start();
include '../includes/init.php';
include 'profile.php';

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
    } catch (PDOException $e) {
        echo "خطا در دریافت مسیر تصویر: " . $e->getMessage();
        exit();
    }
    
    // بررسی آپلود فایل
    if (isset($_FILES["change_profile_picture"]) && is_array($_FILES["change_profile_picture"])) {
        $file = $_FILES["change_profile_picture"];
        
        if ($file["error"] == 0) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($file["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
            // بررسی اعتبار تصویر
            $check = getimagesize($file["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "فایل انتخاب شده تصویر نیست.";
                $uploadOk = 0;
            }
            
            // اگر آپلود مجاز باشد
            if ($uploadOk == 1) {
                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    // حذف تصویر قبلی از سرور
                    if (!empty($current_imgpath) && file_exists($current_imgpath)) {
                        unlink($current_imgpath);
                    }
                    
                    try {
                        // به‌روزرسانی مسیر تصویر در دیتابیس
                        $update_query = "UPDATE tblusers SET imgpath = :imgpath WHERE id = :user_id";
                        $stmt = $conn->prepare($update_query);
                        $stmt->bindParam(':imgpath', $target_file, PDO::PARAM_STR);
                        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                        if ($stmt->execute()) {
                            echo "تصویر پروفایل با موفقیت تغییر کرد.";
                        } else {
                            echo "خطا در به‌روزرسانی دیتابیس: " . $conn->errorInfo()[2];
                        }
                    } catch (PDOException $e) {
                        echo "خطا در به‌روزرسانی دیتابیس: " . $e->getMessage();
                    }
                } else {
                    echo "خطا در آپلود فایل " . $file["error"];
                }
            }
        } else {
            echo "خطا در آپلود فایل " . $file["error"];
        }
    } else {
        echo "هیچ فایلی برای آپلود انتخاب نشده است.";
    }
    
    // بازگشت به صفحه پروفایل
    header("Location: profile.php");
    exit();
}
?>