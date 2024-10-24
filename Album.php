<?php
include 'CSS/Album_functions.php';
include 'template/Album.tpl';
// include 'includes/init.php';

global $conn;
$conn = connectToDatabase();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // دریافت اطلاعات فرم
        $artist = $_POST['artist'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $tags = $_POST['tags'];
        
        // آپلود فولدر آلبوم
        $albumPath = 'aldata/' . $title;
        if (!file_exists($albumPath)) {
            mkdir($albumPath, 0777, true);
        }
        
        // آپلود عکس کاور
        $coverPath = 'aldata/Cover/' . $title . '.jpg';
        move_uploaded_file($_FILES['thumbnailFile']['tmp_name'], $coverPath);
        
        // ذخیره در دیتابیس
        $sql = "INSERT INTO tblalbums (album_name, artist_id, release_date, genre, imgPath, albumPath, description, created_at, updated_at) 
                VALUES (:title, :artist_id, NOW(), :genre, :imgPath, :albumPath, :description, NOW(), NOW())";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':artist_id', $artist);
        $stmt->bindParam(':genre', $tags);
        $stmt->bindParam(':imgPath', $coverPath);
        $stmt->bindParam(':albumPath', $albumPath);
        $stmt->bindParam(':description', $description);
        
        $stmt->execute();
        
        echo "<div class='success-message'>آلبوم با موفقیت آپلود شد</div>";
    } catch(PDOException $e) {
        echo "خطا در آپلود آلبوم: " . $e->getMessage();
    }
}

// نمایش آلبوم‌ها
// $sql = "SELECT a.album_name, a.imgPath, ar.name as artist_name 
//         FROM tblalbums a 
//         JOIN tblartists ar ON a.artist_id = ar.id";
// $stmt = $conn->prepare($sql);
// $stmt->execute();

// echo "<div class='album-container'>";
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//     echo "<div class='album'>";
//     echo "<img src='" . $row['imgPath'] . "' alt='" . $row['album_name'] . "'>";
//     echo "<h3>" . $row['album_name'] . "</h3>";
//     echo "<p>" . $row['artist_name'] . "</p>";
//     echo "</div>";
// }
// echo "</div>";


echo generate_header("Album");
echo generate_footer();
?>