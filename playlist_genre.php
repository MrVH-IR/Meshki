<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Genre</title>
    <link rel="stylesheet" href="CSS/playlists.css">
    <link rel="stylesheet" href="CSS/common.css">
</head>
<body>
    <header>
        <h1>Playlist Genre</h1>
        <nav>
            <div class="menu-toggle">
                <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <span class="menu-text">Menu</span>
                </div>
                <ul class="menu">
                    <li><a href="../meshki/index.php">Home Page</a></li>
                    <li><a href="../meshki/music.php">Music</a></li>
                    <li><a href="../meshki/music-videos.php">Music Video</a></li>
                    <li><a href="../meshki/artists.php">Artists</a></li>
                    <li><a href="../meshki/playlists.php">Playlists</a></li>
                    <li><a href="../meshki/aboutus.php">About Us</a></li>
                    <?php if(isset($_SESSION["username"]) && $_SESSION["username"] !== null): ?>
                    <li><a href="../meshki/user/profile.php">Profile</a></li>
                    <li><a href="../meshki/logout.php">Logout</a></li>
                    <?php else: ?>
                    <li><a href="../meshki/login.php">Login</a></li>
                    <li><a href="../meshki/register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
        </nav>
    </header>
    <main>
        <h2>Playlist Genre</h2>
    </main>
    <?php  include './includes/init.php';
    if (isset($_GET['genre'])) {
        $selectedGenre = $_GET['genre'];
        try {
            global $conn;
            $conn = connectToDatabase();
            $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
            $stmt->bindParam(':genre', $selectedGenre, PDO::PARAM_STR);
            $stmt->execute();
            $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $message = 'Error: ' . $e->getMessage();
        }
        
        if (!empty($songs)) {
            echo '<div class="songs-grid">';
            foreach ($songs as $song) {
                echo '<div class="song-item" style="width: 50%; height: 50%; margin-right: 27%;">';
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
                echo '<a href="playlist_genre.php?genre=' . urlencode($genre['genre']) . '">';
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
    
    if (isset($message)) {
        echo '<p>' . $message . '</p>';
    }
    ?>
    <div class="background-banner">
        <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
    </div>
</body>
</html>