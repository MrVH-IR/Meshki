<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // دریافت اطلاعات فرم
    $artist = $_POST['artist'];
    $songName = $_POST['songName'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];

    // آپلود فایل آهنگ
    $target_dir_song = "mdata/";
    $target_file_song = $target_dir_song . basename($_FILES["song"]["name"]);
    move_uploaded_file($_FILES["song"]["tmp_name"], $target_file_song);

    // آپلود فایل پوستر
    $target_dir_poster = "admin/posters/";
    $target_file_poster = $target_dir_poster . basename($_FILES["poster"]["name"]);
    move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file_poster);

    // ذخیره اطلاعات در دیتابیس
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO tblsongs (artist, songName, songPath, posterPath, description, tags)
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $artist, $songName, $target_file_song, $target_file_poster, $description, $tags);

    if ($stmt->execute()) {
        echo "<script>alert('آهنگ با موفقیت آپلود شد.'); window.location.href = 'index.php';</script>";
    } else {
        echo "خطا در آپلود آهنگ: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>