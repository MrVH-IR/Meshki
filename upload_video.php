<?php
session_start();
header('Content-Type: application/json');

// بررسی دسترسی ادمین
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo json_encode(['success' => false, 'error' => 'دسترسی غیرمجاز']);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // دریافت اطلاعات فرم
    $title = $_POST['title'];
    $description = $_POST['description'];

    // آپلود فایل ویدیو
    $target_dir_video = "admin/vdata/";
    $target_file_video = $target_dir_video . basename($_FILES["video"]["name"]);
    $uploadOk = 1;
    $videoFileType = strtolower(pathinfo($target_file_video,PATHINFO_EXTENSION));

    // بررسی نوع فایل
    if($videoFileType != "mp4" && $videoFileType != "avi" && $videoFileType != "mov" && $videoFileType != "wmv" ) {
        echo json_encode(['success' => false, 'error' => 'فقط فایل‌های MP4، AVI، MOV و WMV مجاز هستند.']);
        exit();
    }

    // قبل از آپلود فایل‌ها، مطمئن شوید که پوشه‌های مورد نظر وجود دارند
    if (!file_exists($target_dir_video)) {
        mkdir($target_dir_video, 0777, true);
    }

    // اضافه کردن بررسی خطا برای آپلود فایل ویدیو
    if (!move_uploaded_file($_FILES["video"]["tmp_name"], $target_file_video)) {
        $error = error_get_last();
        echo json_encode(['success' => false, 'error' => 'خطا در آپلود فایل ویدیو: ' . $error['message']]);
        exit();
    }

    // آپلود فایل تصویر بندانگشتی
    $target_dir_thumbnail = "admin/vdata/thumbnails/";
    $target_file_thumbnail = $target_dir_thumbnail . basename($_FILES["thumbnail"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file_thumbnail,PATHINFO_EXTENSION));

    // بررسی نوع فایل تصویر
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        echo json_encode(['success' => false, 'error' => 'فقط فایل‌های JPG، JPEG، PNG و GIF مجاز هستند.']);
        exit();
    }

    // قبل از آپلود فایل‌ها، مطمئن شوید که پوشه‌های مورد نظر وجود دارند
    if (!file_exists($target_dir_thumbnail)) {
        mkdir($target_dir_thumbnail, 0777, true);
    }

    // اضافه کردن بررسی خطا برای آپلود فایل تصویر بندانگشتی
    if (!move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $target_file_thumbnail)) {
        $error = error_get_last();
        echo json_encode(['success' => false, 'error' => 'خطا در آپلود تصویر بندانگشتی: ' . $error['message']]);
        exit();
    }

    // ذخیره اطلاعات در دیتابیس
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo json_encode(['success' => false, 'error' => 'خطا در اتصال به پایگاه داده: ' . $conn->connect_error]);
        exit();
    }

    $sql = "INSERT INTO tblvids (title, description, videoPath, thumbnailPath, upload_date)
    VALUES (?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $title, $description, $target_file_video, $target_file_thumbnail);

    if ($stmt->execute()) {
        $video_id = $conn->insert_id;
        echo json_encode(['success' => true, 'message' => 'ویدیو با موفقیت آپلود شد.', 'video' => [
            'id' => $video_id,
            'title' => $title,
            'description' => $description,
            'videoPath' => $target_file_video,
            'thumbnailPath' => $target_file_thumbnail
        ]]);
    } else {
        echo json_encode(['success' => false, 'error' => 'خطا در ذخیره اطلاعات در پایگاه داده: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'درخواست نامعتبر.']);
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آپلود ویدیو</title>
    <style>
        body {
            font-family: 'Vazir', Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #35424a;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            margin-top: 5px;
        }
        input[type="submit"] {
            background: #e8491d;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }
        input[type="submit"]:hover {
            background: #ff6a00;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>آپلود ویدیو جدید</h1>
        <form action="upload_video.php" method="post" enctype="multipart/form-data">
            <label for="title">عنوان ویدیو:</label>
            <input type="text" id="title" name="title" required>

            <label for="description">توضیحات:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="video">فایل ویدیو:</label>
            <input type="file" id="video" name="video" accept="video/*" required>

            <label for="thumbnail">تصویر بندانگشتی:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" required>

            <input type="submit" value="آپلود ویدیو">
        </form>
    </div>
</body>
</html>

