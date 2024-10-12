<?php
session_start();
include 'template/artists.tpl';

// Function to search artists
function searchArtists($query) {
    try {
        $db = new PDO('mysql:host=localhost;dbname=meshki', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $db->prepare('SELECT DISTINCT artist FROM tblsongs WHERE artist LIKE :query');
        $stmt->bindParam(':query', $query, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ['error' => $e->getMessage()];
    }
}

// Check search request
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = '%' . $_GET['query'] . '%';
    $results = searchArtists($query);
    
    if (!isset($results['error'])) {
        echo '<h1 style="text-align: center; color: pink;">Search results for: ' . htmlspecialchars($_GET['query']) . '</h1>';
        echo '<div class="artists-container">';
        echo '<div class="artists-grid">';
        foreach ($results as $result) {
            $artist_name = htmlspecialchars($result['artist']);
            $image_path = 'admin/artists/' . $artist_name . '.jpg';
            
            echo '<div class="artist-item">';
            echo '<a href="?artist=' . urlencode($artist_name) . '">';
            if (file_exists($image_path)) {
                echo '<img src="' . $image_path . '" alt="' . $artist_name . '">';
            } else {
                echo '<img src="admin/artists/default.jpg" alt="Default picture">';
            }
            echo '<h3>' . $artist_name . '</h3>';
            echo '</a>';
            echo '</div>';
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo 'Error: ' . $results['error'];
    }
} else {
    // Show all artists
}

$selected_artist = isset($_GET['artist']) ? $_GET['artist'] : null;

if ($selected_artist) {
    // Action for selected artist
    try {
        $db = new PDO('mysql:host=localhost;dbname=meshki', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare('SELECT * FROM tblsongs WHERE artist = :artist');
        $stmt->bindParam(':artist', $selected_artist, PDO::PARAM_STR);
        $stmt->execute();
        $songs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $path = array_column($songs, 'songPath');
        
        echo '<h1>' . htmlspecialchars($selected_artist) . '</h1>';
        echo '<div style="text-align: center;">';
        echo '<img src="admin/artists/' . htmlspecialchars($selected_artist) . '.jpg" alt="' . htmlspecialchars($selected_artist) . '" style="width: 50%; height: 50%; margin-bottom: 20px;">';
        
        echo '<h2 id="h2" style="color: pink;">Songs:</h2>';
        echo '<ul id="songs-list" style="list-style-type: none; padding: 0; display: inline-block; text-align: left;">';
        foreach ($songs as $index => $song) {
            echo '<li id="li-songs-list" style="color: pink; width: 100px; margin-bottom: 10px;">';
            echo '<a id="link" href="#" onclick="playSong(\'' . htmlspecialchars($path[$index]) . '\')" style="text-decoration: none; color: pink;">';
            echo '<p style="color: pink; margin: 0; transition: font-weight 0.3s ease;">' . htmlspecialchars($song['songName']) . '</p>';
            echo '</a>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</div>';
        echo '<div id="audioPlayerContainer" style="display:none; position: relative; text-align: center; margin-top: 20px;">';
        echo '<audio id="audioPlayer" controls style="width: 50%;">';
        echo '<source id="audioSource" src="" type="audio/mpeg">';
        echo 'Your browser does not support the audio element.';
        echo '</audio>';
        echo '<button onclick="closePlayer()" style="position: 100; top: 0; right: 0; background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;">X</button>';
        echo '</div>';
        echo '<script>';
        echo 'function playSong(songPath) {';
        echo '    var audioPlayerContainer = document.getElementById("audioPlayerContainer");';
        echo '    var audioPlayer = document.getElementById("audioPlayer");';
        echo '    var audioSource = document.getElementById("audioSource");';
        echo '    audioSource.src = songPath;';
        echo '    audioPlayerContainer.style.display = "block";';
        echo '    audioPlayer.load();';
        echo '    audioPlayer.play();';
        echo '}';
        echo 'function closePlayer() {';
        echo '    var audioPlayerContainer = document.getElementById("audioPlayerContainer");';
        echo '    var audioPlayer = document.getElementById("audioPlayer");';
        echo '    audioPlayer.pause();';
        echo '    audioPlayerContainer.style.display = "none";';
        echo '}';
        echo 'document.querySelectorAll("#songs-list p").forEach(function(song) {';
        echo '    song.addEventListener("mouseover", function() {';
        echo '        this.style.fontWeight = "bold";';
        echo '    });';
        echo '    song.addEventListener("mouseout", function() {';
        echo '        this.style.fontWeight = "normal";';
        echo '    });';
        echo '});';
        echo '</script>';
        echo '<a id="backToArtists" href="artists.php" style="display: inline-block; text-decoration: none; color: #ff69b4; font-weight: bold; padding: 10px 20px; border: 2px solid #ff69b4; border-radius: 5px; transition: all 0.3s ease;">Back To Artists</a>';
        echo '<style>';
        echo '#backToArtists:hover {';
        echo '    background-color: #ff69b4;';
        echo '    color: white;';
        echo '}';
        echo '</style>';
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    // Show artists (Old Code)
    $items_per_page = 9;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $items_per_page;

    try {
        $db = new PDO('mysql:host=localhost;dbname=meshki', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get the total number of artists
        $count_stmt = $db->query('SELECT COUNT(DISTINCT artist) as total FROM tblsongs');
        $total_artists = $count_stmt->fetchColumn();
        
        // دریافت هنرمندان منحصر به فرد با صفحه‌بندی
        $stmt = $db->prepare('SELECT DISTINCT artist, MAX(upload_date) as latest_upload FROM tblsongs GROUP BY artist ORDER BY latest_upload DESC LIMIT :offset, :items_per_page');
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
        $stmt->execute();
        $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Display artists with images
        echo '<div class="artists-grid">';
        $artist_count = 0;
        foreach ($artists as $artist) {
            if ($artist_count % 3 == 0) {
                // Start a new row for every three artists
                echo '<div class="artists-row">';
            }
            
            $artist_name = htmlspecialchars($artist['artist']);
            $image_path = 'admin/artists/' . $artist_name . '.jpg'; // Assuming the image file is named after the artist
            
            echo '<div class="artist-item">';
            echo '<a href="?artist=' . urlencode($artist_name) . '">';
            if (file_exists($image_path)) {
                echo '<img src="' . $image_path . '" alt="' . $artist_name . '">';
            } else {
                echo '<img src="admin/artists/default.jpg" alt="Default picture">';
            }
            echo '<h3>' . $artist_name . '</h3>';
            echo '</a>';
            echo '</div>';
            
            $artist_count++;
            
            if ($artist_count % 3 == 0 || $artist_count == count($artists)) {
                // Close row after every three artists or at the end of the list
                echo '</div>';
            }
        }
        echo '</div>';
        
        // Pagination
        $total_pages = ceil($total_artists / $items_per_page);
        echo '<div class="pagination">';
        
        if ($current_page > 1) {
            echo '<a href="?page=' . ($current_page - 1) . '" class="prev">Previous</a> ';
        }
        
        for ($i = 1; $i <= $total_pages; $i++) {
            echo '<a href="?page=' . $i . '">' . $i . '</a> ';
        }
        
        if ($current_page < $total_pages) {
            echo '<a href="?page=' . ($current_page + 1) . '" class="next">Next</a> ';
        }
        
        echo '</div>';
        
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
    }
}
?>