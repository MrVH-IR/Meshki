<?php
session_start();
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) {
    if (isset($_GET['id'])) {
        $videoId = $_GET['id'];

        // اتصال به دیتابیس
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "meshki";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die(json_encode(["success" => false, "error" => "خطا در اتصال به پایگاه داده: " . $conn->connect_error]));
        }

        // حذف ویدیو از دیتابیس
        $sql = "DELETE FROM tblvids WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $videoId);

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "خطا در حذف ویدیو: " . $stmt->error]);
        }

        $stmt->close();
        $conn->close();
    } else {
        echo json_encode(["success" => false, "error" => "شناسه ویدیو ارسال نشده است."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "دسترسی غیرمجاز."]);
}
?>