<?php
session_start();
include './includes/init.php';
ob_start(); // اضافه کردن این خط برای جلوگیری از ارسال هدرها قبل از خروجی
include './template/playlists.tpl';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
if (isset($_GET['id']) && ($_GET['id'])) {
    header('Location: playlist_detail.php');
    exit();
}
$playlist_detail = 'playlist_detail.php';
$message = '';
$conn = connectToDatabase();
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

function createPlaylist($user_id, $name, $thumbnailPath, $genre) {
    try {
        global $conn;
        $stmt = $conn->prepare('INSERT INTO tblPlaylists (user_id, name, thumbnailPath, genre, created_at, updated_at) VALUES (:user_id, :name, :thumbnailPath, :genre, NOW(), NOW())');
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':thumbnailPath', $thumbnailPath, PDO::PARAM_STR);
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $stmt->execute();
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

function togglePlayer() {
    echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            var showMoreButton = document.createElement("button");
            showMoreButton.textContent = "Show More";
            showMoreButton.style.display = "block";
            showMoreButton.style.margin = "20px auto";
            showMoreButton.style.padding = "10px 20px";
            showMoreButton.style.backgroundColor = "#f0f0f0";
            showMoreButton.style.border = "none";
            showMoreButton.style.cursor = "pointer";
            
            var playerContainer = document.createElement("div");
            playerContainer.style.display = "none";
            playerContainer.style.textAlign = "center";
            playerContainer.innerHTML = \'<audio controls><source src="path_to_your_song.mp3" type="audio/mpeg">Your browser does not support the audio element.</audio>\';
            
            showMoreButton.addEventListener("click", function() {
                if (playerContainer.style.display === "none") {
                    playerContainer.style.display = "block";
                    showMoreButton.textContent = "Show Less";
                } else {
                    playerContainer.style.display = "none";
                    showMoreButton.textContent = "Show More";
                }
            });
            
            document.body.appendChild(showMoreButton);
            document.body.appendChild(playerContainer);
        });
    </script>';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['playlistName'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_POST['playlistName'];
    $genre = $_POST['genre'];
    $thumbnailPath = 'admin/playlists/default.png'; // Default thumbnail path

    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'admin/playlists/';
        $uploadFile = $uploadDir . basename($_FILES['thumbnail']['name']);
        if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadFile)) {
            $thumbnailPath = $uploadFile;
        }
    }

    $result = createPlaylist($user_id, $name, $thumbnailPath, $genre);
    if (isset($result['error'])) {
        $message = 'Error: ' . $result['error'];
    } else {
        $message = 'Playlist created successfully!';
        // Redirect user to the main page after creating the playlist
        header('Location: playlists.php');
        exit();
    }
}
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $playlists = getPlaylists($user_id);
    if (!isset($playlists['error'])) {
        echo '<div class="playlist-grid" style="display: flex; flex-wrap: wrap; justify-content: space-around;">';
        $count = 0;
        foreach ($playlists as $playlist) {
            if ($count % 4 == 0) {
                if ($count != 0) {
                    echo '</div>';
                }
                echo '<div class="playlist-row" style="display: flex; justify-content: space-around; width: 100%; margin-bottom: 20px;">';
            }
            echo '<div class="playlist-item" style="width: 22%; text-align: center;">';
            echo '<a href="playlist_detail.php?id=' . $playlist['id'] . '" style="text-decoration: none; color: inherit;">';
            echo '<img src="' . htmlspecialchars($playlist['thumbnailPath']) . '" alt="' . htmlspecialchars($playlist['name']) . '" style="width: 100%; height: auto; border-radius: 10px;">';
            echo '<h3 style="margin-top: 10px;">' . htmlspecialchars($playlist['name']) . '</h3>';
            echo '</a>';
            echo '</div>';
            $count++;
        }
        if ($count % 4 != 0) {
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p style="text-align: center; color: red;">Error: ' . $playlists['error'] . '</p>';
    }
    $genres = isset($playlist["genre"]) ? $playlist["genre"] : '';
}
if (isset($_GET['id'])) {
    try {
        $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
        $stmt->bindParam(':genre', $genres, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $songPaths = array_column($songs, 'songPath');
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
    }
    if (!empty($songs)) {
        echo '<div class="genre-grid">';
        foreach ($songs as $index => $song) {
            if ($index % 3 == 0) {
                if ($index != 0) {
                    echo '</div>'; // Close previous row
                }
                echo '<div class="genre-row">'; // Start new row
            }
            echo '<div class="genre-item" style="width: 30%; margin: 1%;">';
            echo '<a href="song.php?id=' . $song['id'] . '">';
            echo '<img src="' . htmlspecialchars($song['posterPath']) . '" alt="' . htmlspecialchars($song['artist']) . '" style="width: 100%; height: auto;">';
            echo '<h3>' . htmlspecialchars($song['songName']) . '</h3>';
            echo '<audio src="' . htmlspecialchars($song['songPath']) . '" controls></audio>';
                       echo '</a>';
            echo '</div>';
        }
        echo '</div>'; // Close last row
        echo '</div>';
    } else {
        echo '<p>No songs found in this genre.</p>';
    }
}
try {
    $stmt = $conn->prepare('SELECT DISTINCT genre FROM tblsongs');
    $stmt->execute();
    $genres = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $message = 'Error: ' . $e->getMessage();
}

if (isset($_GET['genre'])) {
    $template = 'playlist_genre.php';
    $template = $_GET['genre'];
    header('Location: playlist_genre.php?genre=' . urlencode($template));
    exit();
    $selectedGenre = $_GET['genre'];
    try {
        $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
        $stmt->bindParam(':genre', $selectedGenre, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
    }
    if (!empty($songs)) {
        $uniqueSongs = [];
        echo '<div class="songs-grid">';
        foreach ($uniqueSongs as $song) {
            echo '<div class="song-item">';
            echo '<a href="song.php?id=' . $song['id'] . '">';
            echo '<img src="' . htmlspecialchars($song['posterPath']) . '" alt="' . htmlspecialchars($song['artist']) . '" style="width: 100%; height: auto;">';
            echo '<h3>' . htmlspecialchars($song['songName']) . '</h3>';
            echo '<audio src="' . htmlspecialchars($song['songPath']) . '" controls id="audioPlayer"></audio>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No songs found in this genre.</p>';
    }
} else {
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
        echo '<p>No genres found.</p>';
    }
}

if (isset($_GET['genre'])){
    $genre = $_GET['genre'];
    try{
        $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $message = 'Error: ' . $e->getMessage();
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
ob_end_flush(); // اضافه کردن این خط برای ارسال خروجی بعد از تمام شدن اسکریپت
?>
