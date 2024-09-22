<?php
session_start();
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['videoName'];
        $artist = $_POST['artist'];
        $upload_date = date('Y-m-d H:i:s');

        // مسیر ذخیره ویدیو و پوستر
        $videoPath = 'admin/vdata/' . basename($_FILES['video']['name']);
        $thumbnailPath = 'admin/thumbnails/' . basename($_FILES['thumbnail']['name']);

        // آپلود فایل ویدیو
        if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath) && move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbnailPath)) {
            // اتصال به دیتابیس
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "meshki";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die(json_encode(["success" => false, "error" => "خطا در اتصال به پایگاه داده: " . $conn->connect_error]));
            }

            // ذخیره اطلاعات ویدیو در دیتابیس
            $sql = "INSERT INTO tblvids (title, artist, videoPath, thumbnailPath, upload_date) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $title, $artist, $videoPath, $thumbnailPath, $upload_date);

            if ($stmt->execute()) {
                echo json_encode(["success" => true]);
            } else {
                echo json_encode(["success" => false, "error" => "خطا در ذخیره اطلاعات ویدیو: " . $stmt->error]);
            }

            $stmt->close();
            $conn->close();
        } else {
            echo json_encode(["success" => false, "error" => "خطا در آپلود فایل‌ها."]);
        }
    } else {
        echo json_encode(["success" => false, "error" => "درخواست نامعتبر."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "دسترسی غیرمجاز."]);
}
?>
