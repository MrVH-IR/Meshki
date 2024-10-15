<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Playlists</title>
    <link rel="stylesheet" href="CSS/playlists.css">
    <link rel="stylesheet" href="CSS/common.css">
</head>
<body>
    <header>
        <h1>Create New Playlists</h1>
        <button id="createPlaylistBtn">Create New Playlist</button>
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
                    <?php if(isset($_SESSION["user_id"])): ?>
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
        <?php if(isset($_SESSION["user_id"])): ?>
        <section id="playlists">
            <h2>Your Playlists</h2>
            <div class="playlist-grid">
                <!-- Users Playlists -->
            </div>
        </section>
        <section id="createPlaylistForm" style="display: none;">
            <h2 style="font-size: 20px;">Create New Playlist</h2>
            <form method="POST" enctype="multipart/form-data" id="Playlistform">
                <label for="playlistName">Playlist Name:</label>
                <input type="text" id="playlistName" name="playlistName" required>
                <label for="thumbnail">Upload Image:</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                <label for="genre">Genre:</label>
                <select id="genre" name="genre">
                    <option value="">Choose genre</option>
                    <option value="pop">Pop</option>
                    <option value="rock">Rock</option>
                    <option value="hip-hop">Hip Hop</option>
                    <option value="electronic">Electronic</option>
                    <option value="classical">Classical</option>
                    <option value="jazz">Jazz</option>
                    <option value="country">Country</option>
                    <option value="r-and-b">R&B</option>
                    <option value="indie">Indie</option>
                    <option value="folk">Folk</option>
                    <option value="other">Other</option>
                </select>
                <button type="submit">Create</button>
            </form>
        </section>
        <?php endif; ?>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
        </div>
        <section id="OriginalPlaylists">
            <div class="OriginalPlaylists-grid">
                <!-- Original Playlists -->
            </div>
        </section>
    </main>
    <script>
        document.getElementById('createPlaylistBtn').addEventListener('click', function() {
            document.getElementById('createPlaylistForm').style.display = 'block';
        });

        // Close Form
        var closeButton = document.createElement('button');
        closeButton.textContent = 'X';
        closeButton.style.marginLeft = '10px';
        closeButton.addEventListener('click', function() {
            document.getElementById('createPlaylistForm').style.display = 'none';
        });
        document.getElementById('createPlaylistForm').querySelector('h2').appendChild(closeButton);

        // Menu
        document.querySelector('.menu-toggle').addEventListener('click', function() {
            document.querySelector('.menu').classList.toggle('active');
        });
    </script>
</body>
</html>
