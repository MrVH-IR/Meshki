<?php
// اتصال به دیتابیس
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meshki";

// ایجاد اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// بررسی اینکه آیا فرم ارسال شده است
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت اطلاعات فرم
    $artist = mysqli_real_escape_string($conn, $_POST['artist']); // نام هنرمند
    $title = mysqli_real_escape_string($conn, $_POST['title']); // نام ویدیو
    $description = mysqli_real_escape_string($conn, $_POST['description']); // توضیحات
    $tags = mysqli_real_escape_string($conn, $_POST['tags']); // تگ‌ها
    
    // مسیر ذخیره‌سازی فایل‌ها
    $targetDir = "mdata/uploads/videos/";
    $posterDir = "mdata/uploads/posters/";

    // ذخیره فایل ویدیو
    $videoFile = $targetDir . basename($_FILES["videoFile"]["name"]);
    $videoFileType = strtolower(pathinfo($videoFile, PATHINFO_EXTENSION));

    // ذخیره فایل پوستر
    $posterFile = $posterDir . basename($_FILES["thumbnailFile"]["name"]);
    $posterFileType = strtolower(pathinfo($posterFile, PATHINFO_EXTENSION));

    // بررسی اینکه فایل ویدیو معتبر است
    if($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov" && $videoFileType != "mkv") {
        echo json_encode(["success" => false, "message" => "فقط فرمت‌های MP4, AVI, MOV, MKV پشتیبانی می‌شوند."]);
        exit;
    }

    // بررسی اینکه فایل پوستر معتبر است
    if($posterFileType != "jpg" && $posterFileType != "png" && $posterFileType != "jpeg") {
        echo json_encode(["success" => false, "message" => "فقط فرمت‌های JPG, JPEG, PNG پشتیبانی می‌شوند."]);
        exit;
    }

    // انتقال فایل‌ها به مسیر نهایی
    if (move_uploaded_file($_FILES["videoFile"]["tmp_name"], $videoFile) && move_uploaded_file($_FILES["thumbnailFile"]["tmp_name"], $posterFile)) {
        // ثبت اطلاعات در دیتابیس
        $uploadDate = date('Y-m-d H:i:s'); // گرفتن تاریخ و زمان آپلود

        $sql = "INSERT INTO tblvids (title, description, videoPath, thumbnailPath, upload_date)
                VALUES ('$title', '$description', '$videoFile', '$posterFile', '$uploadDate')";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["success" => true, "message" => "ویدیو با موفقیت آپلود شد.", "video" => ["name" => $title]]);
        } else {
            echo json_encode(["success" => false, "message" => "خطا در ثبت اطلاعات در دیتابیس: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "خطا در آپلود فایل‌ها."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "فقط درخواست POST معتبر است."]);
}

// بستن اتصال به دیتابیس
$conn->close();
?>
