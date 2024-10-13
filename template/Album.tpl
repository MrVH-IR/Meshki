<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download and Online Music | Meshki</title>
    <meta name="description" content="Best place to download, listen and watch online new music. The widest music collection with high quality.">
    <meta name="keywords" content="Download Music, Online Music, New Song, Free Download">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Download and Online Music | Meshki">
    <meta property="og:description" content="Best place to download, listen and watch online new music. The widest music collection with high quality.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.meshki.com">
    <link rel="canonical" href="https://www.meshki.com">
    <link rel="stylesheet" href="../CSS/Album.css">
    <script src="./JS/Album.js"></script>
</head>
<body>
    <header>
        <nav>
            <div class="menu-toggle">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="menu-text">منو</span>
            </div>
            <ul class="menu">
                <li><a href="../meshki/index.php">Home</a></li>
                <li><a href="../meshki/Album.php">Album</a></li>
                <li><a href="../meshki/music-videos.php">Music Videos</a></li>
                <li><a href="../meshki/artists.php">Artists</a></li>
                <li><a href="../meshki/playlists.php">Playlists</a></li>
                <li><a href="../meshki/aboutus.php">About Us</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="../meshki/profile.php">Profile</a></li>
                    <li><a href="../meshki/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="../meshki/login.php">Login</a></li>
                    <li><a href="../meshki/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="بنر پس زمینه" class="background-image">
        </div>
        <div id="uploadedSongs"></div>
    </main>
    
</body>
</html>
