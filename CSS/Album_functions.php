<?php
session_start();
include './includes/init.php';

$user = null;
$admin = null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$admin = isset($_SESSION['is_admin']) ? $_SESSION['is_admin'] : null;

global $conn;

function generate_header($title, $search_query = '') {
    global $admin;
    $header = ''; 
    
        $header .= <<<EOT
        <!DOCTYPE html>
        <html lang="en" dir="rtl">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>$title | Meshki</title>
            <link rel="stylesheet" href="CSS/Album.css">
            <link rel="stylesheet" href="CSS/pagination.css">
        </head>
        <body>
            <header>
                <nav>
                    <div class="menu-toggle" onclick="toggleMenu()">
                        <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="menu-text">Menu</span>
                    </div>
                    <ul class="menu">
                        <li><a href="index.php">Home Page</a></li>
                        <li><a href="music.php">Music</a></li>
                        <li><a href="music-videos.php">Music Video</a></li>
                        <li><a href="artists.php">Artists</a></li>
                        <li><a href="playlists.php">Playlists</a></li>
                        <li><a href="aboutus.php">About Us</a></li>
    EOT;
    
        if (isset($_SESSION['user_id']) || isset($_SESSION['is_admin'])) {
            $header .= <<<EOT
                        <li><a href="user/profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
    EOT;
        } else {
            $header .= <<<EOT
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
    EOT;
        }
    
        $header .= <<<EOT
                    </ul>
    EOT;
    if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == true) {
        $header .= <<<EOT
                    <button id="uploadAlbumBtn" class="upload-btn">Upload Album</button>
                    <div id="uploadAlbumFormContainer" class="upload-form-container" style="display: none;">
                        <h3>Upload New Album</h3>
                        <form id="uploadAlbumForm" method="post" enctype="multipart/form-data">
                            <input type="text" name="artist" placeholder="Artist" required>
                            <input type="text" name="title" placeholder="Title" required>
                            <textarea name="description" placeholder="Description" required></textarea>
                            <input type="text" name="tags" placeholder="Tags (comma separated)" required>
                            <input type="file" name="albumFiles[]" id="albumFiles" placeholder="Select Album Files" webkitdirectory directory multiple/>
                            <label for="albumFiles" class="custom-file-upload">Select Album Files</label>
                            <span id="albumFolderName"></span>
                            
                            <input type="file" name="thumbnailFile" id="thumbnailFile" placeholder="Cover Album" accept="image/*" required>
                            <label for="thumbnailFile" class="custom-file-upload">Cover Album</label>
                            <span id="thumbnailFileName"></span>
                            <button type="submit">Upload</button>
                            <button type="button" id="closeFormBtn">Close</button>
                        </form>
                    </div>
EOT;
    }
    
        $header .= <<<EOT
                </nav>
                <div class="search-container">
                    <form action="searchvid.php" method="GET" class="search-form">
                        <input type="text" name="q" placeholder="Search For Music Video" value="$search_query" required>
                        <button type="submit">Search</button>
                    </form>
                </div>
            </header>
            <div class="background-banner">
                <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
            </div>
    EOT;
    
        return $header;
    }

function generate_footer() {
    $footer = <<<EOT
        <footer>
            <!-- Footer goes here -->
        </footer>
        <script>
            function toggleMenu() {
                var menu = document.querySelector('.menu');
                menu.classList.toggle('active');
            }
            function togglePlayer(button) {
                var player = button.nextElementSibling;
                if (player.style.display === "none") {
                    player.style.display = "block";
                    button.textContent = "Show Less";
                } else {
                    player.style.display = "none";
                    button.textContent = "Show More";
                }
            }
        </script>
    </body>
    </html>
    EOT;
    return $footer;
}

function generate_music_container($song) {
    $music_container = <<<EOT
    <div class="music-container">
        <h2 class="music-title">{$song["songName"]} - {$song["artist"]}</h2>
        <img src="{$song["posterPath"]}" alt="{$song["songName"]} Poster" class="music-poster">
        <p class="music-description">{$song["description"]}</p>
        <div class="music-tags">
    EOT;
    
    $tags = explode(',', $song["tags"]);
    foreach ($tags as $tag) {
        $music_container .= '<span>' . trim($tag) . '</span>';
    }
    
    $music_container .= <<<EOT
        </div>
        <button class="show-more-btn" onclick="togglePlayer(this)">Show More</button>
        <div class="music-player" style="display: none;">
            <audio controls>
                <source src="{$song["songPath"]}" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>
    </div>
    EOT;
    
    return $music_container;
}

function generate_pagination($total_pages, $current_page, $base_url) {
    $pagination = '<div class="pagination-container"><div class="pagination">';
    if ($current_page > 1) {
        $pagination .= "<a href=\"{$base_url}&page=" . ($current_page-1) . "\" class=\"prev\">Previous</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= "<a href=\"{$base_url}&page={$i}\" " . ($current_page == $i ? 'class="active"' : '') . ">{$i}</a>";
    }
    if ($current_page < $total_pages) {
        $pagination .= "<a href=\"{$base_url}&page=" . ($current_page+1) . "\" class=\"next\">Next</a>";
    }
    $pagination .= '</div></div>';
    return $pagination;
}
?>