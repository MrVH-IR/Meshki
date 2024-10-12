<?php
session_start();
include 'includes/init.php';
include 'template/playlists.tpl';

$message = '';

function getPlaylists($user_id) {
    try {
        global $conn;
        $stmt = $conn->prepare('SELECT * FROM tblPlaylists WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

function createPlaylist($user_id, $name, $thumbnailPath) {
    try {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO tblPlaylists (user_id, name, thumbnailPath, created_at, updated_at) VALUES (:user_id, :name, :thumbnailPath, NOW(), NOW())');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':thumbnailPath', $thumbnailPath, PDO::PARAM_STR);
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlistName'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['playlistName'];
    $thumbnailPath = 'admin/playlists/default.png'; // مسیر پیش‌فرض تصویر کوچک

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'admin/playlists/';
        $uploadFile = $uploadDir . basename($_FILES['thumbnail']['name']);
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadFile)) {
            $thumbnailPath = $uploadFile;
        }
    }

    $result = createPlaylist($user_id, $name, $thumbnailPath);
    if (isset($result['error'])) {
        $message = 'خطا: ' . $result['error'];
    } else {
        $message = 'پلی‌لیست با موفقیت ایجاد شد!';
        // هدایت کاربر به صفحه اصلی بعد از ایجاد پلی‌لیست
        header('Location: playlists.php');
        exit();
    }
}

$user_id = $_SESSION['user_id'];
$playlists = getPlaylists($user_id);

if (!isset($playlists['error'])) {
    echo '<div class="playlist-grid">';
    foreach ($playlists as $playlist) {
        echo '<div class="playlist-item">';
        echo '<a href="playlists.php?id=' . $playlist['id'] . '">';
        echo '<img src="' . htmlspecialchars($playlist['thumbnailPath']) . '" alt="' . htmlspecialchars($playlist['name']) . '">';
        echo '<h3>' . htmlspecialchars($playlist['name']) . '</h3>';
        echo '</a>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo 'Error: ' . $playlists['error'];
}
$genres = $playlist["name"];
if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
        $stmt->bindParam(':genre', $genres, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $songPaths = array_column($songs, 'songPath');
    } catch (PDOException $e) {
        $message = 'خطا: ' . $e->getMessage();
    }
    if (!empty($songs)) {
        echo '<div class="genre-grid">';
        foreach ($songs as $index => $song) {
            if ($index % 3 == 0) {
                if ($index != 0) {
                    echo '</div>'; // بستن ردیف قبلی
                }
                echo '<div class="genre-row">'; // شروع ردیف جدید
            }
            echo '<div class="genre-item" style="width: 30%; margin: 1%;">';
            echo '<a href="song.php?id=' . $song['id'] . '">';
            echo '<img src="' . htmlspecialchars($song['posterPath']) . '" alt="' . htmlspecialchars($song['artist']) . '" style="width: 100%; height: auto;">';
            echo '<h3>' . htmlspecialchars($song['songName']) . '</h3>';
            echo '<audio src="' . htmlspecialchars($song['songPath']) . '" controls></audio>';
                       echo '</a>';
            echo '</div>';
        }
        echo '</div>'; // بستن آخرین ردیف
        echo '</div>';
    } else {
        echo '<p>هیچ آهنگی در این ژانر یافت نشد.</p>';
    }
}
try {
    $stmt = $conn->prepare('SELECT DISTINCT genre FROM tblsongs');
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = 'خطا: ' . $e->getMessage();
}

if (!empty($genres)) {
    echo '<div class="genre-grid">';
    foreach ($genres as $genre) {
        echo '<div class="genre-item">';
        echo '<a href="playlists.php?genre=' . urlencode($genre['genre']) . '">';
        echo '<img src="admin/banners/AdobeStock_215384460_Preview.jpeg" alt="' . htmlspecialchars($genre['genre']) . '" class="genre-image">';
        echo '<h3>' . htmlspecialchars($genre['genre']) . '</h3>';
        echo '</a>';
        echo '</div>';
    }
    echo '</div>';
} else {
    echo '<p>هیچ ژانری یافت نشد.</p>';
}

if (isset($_GET['genre'])){
    $genre = $_GET['genre'];
    try{
        $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'خطا: ' . $e->getMessage();
    }
    if (!empty($songs)) {
    echo '<div class="songs-grid">';
    foreach ($songs as $song) {
        echo '<div class="song-item">';
        echo '<a href="song.php?id=' . $song['id'] . '">';
        echo '<img src="' . htmlspecialchars($song['posterPath']) . '" alt="' . htmlspecialchars($song['artist']) . '" style="width: 100%; height: auto;">';
        echo '<h3>' . htmlspecialchars($song['songName']) . '</h3>';
        echo '<audio src="' . htmlspecialchars($song['songPath']) . '" controls></audio>';
        echo '</a>';
        echo '</div>';
        }
        echo '</div>';
    }
}
var_dump($_SESSION);
?>
