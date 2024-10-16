<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Playlist Detail</title>
    <link rel="stylesheet" href="CSS/playlists.css">
    <link rel="stylesheet" href="CSS/common.css">
</head>
<body>
    <header>
        <h1>Playlist Detail</h1>
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
        <?php
        include './includes/init.php';
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header('Location: login.php');
            exit();
        }
        
        $id = $_GET['id'];
        $user_id = $_SESSION['user_id'];
        $conn = connectToDatabase();
        
        function getPlaylist($id, $user_id) {
            global $conn;
            try {
                $stmt = $conn->prepare('SELECT * FROM tblplaylists WHERE id = :id AND user_id = :user_id');
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ['error' => $e->getMessage()];
            }
        }
        
        $playlist = getPlaylist($id, $user_id);
        $genre = $playlist['genre'];
        var_dump($genre);
        function getSongs($genre) {
            global $conn;
            try {
                $stmt = $conn->prepare('SELECT * FROM tblsongs WHERE genre = :genre');
                $stmt->bindParam(':genre' , $genre , PDO::PARAM_STR);
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return ['error' => $e->getMessage()];
            }
        }
        // artist songName genre songPath
        ?>
        <section id="playlistDetail">
            <div class="playlist-info">
                <?php
                if (isset($playlist['id'])) {
                    echo '<div class="playlist-item" style="width: 50%; height: 50%; margin: 0 auto; margin-bottom: 50px; color: white; border: 2px solid white; border-radius: 10px; padding: 20px; display: flex; flex-direction: column; align-items: center; justify-content: center; background-color: rgba(255, 105, 180, 0.8); border-color: cyan;">';
                    echo '<h3>' . htmlspecialchars($playlist['name']) . '</h3>';
                    echo '<img src="' . htmlspecialchars($playlist['thumbnailPath']) . '" alt="' . htmlspecialchars($playlist['name']) . '" style="width: 100%; height: 100%; border-radius: 10px;">';
                    echo '<p>Genre: ' . htmlspecialchars($playlist['genre']) . '</p>';
                    echo '</div>';
                } else {
                    echo '<p>Playlist not found.</p>';
                }
                $songs = getSongs($genre);
                foreach ($songs as $song) {
                    $songPath = $song['songPath'];
                    $songName = $song['songName'];
                    $artist = $song['artist'];
                    echo '<div class="song-item" id="song-item">';
                    echo '<h3 id="song-name">' . htmlspecialchars($songName) . '</h3>';
                    echo '<p style="color: cyan; text-align: center;">Artist: ' . htmlspecialchars($artist) . '</p>';
                    echo '<audio src="' . htmlspecialchars($songPath) . '" controls id="audio"></audio>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
        </div>
    </main>
    <script>
        // Menu
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.menu').classList.toggle('active');
        });
    </script>
</body>
</html>
