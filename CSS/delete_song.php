<?php
if (isset($_GET['id'])) {
    $songId = $_GET['id'];

    // اتصال به دیتابیس
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "error" => "خطا در اتصال به پایگاه داده: " . $conn->connect_error]));
    }

    // حذف آهنگ از دیتابیس
    $sql = "DELETE FROM tblsongs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $songId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "خطا در حذف آهنگ: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "شناسه آهنگ ارسال نشده است."]);
}
?>