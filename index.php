<?php
include 'configure.php';
include 'CSS/common_functions.php';

// Reading template content
$template = file_get_contents('template/index.tpl');

// Checking user login status
if (isset($_SESSION['user_id'])) {
    // User is logged in
    $user_menu = '
    <li><a href="user\profile.php">My Profile</a></li>
    <li><a href="../meshki/logout.php">Logout</a></li>
    ';
} else {
    // User is not logged in
    $user_menu = '
    <li><a href="../meshki/login.php">Login</a></li>
    <li><a href="../meshki/register.php">Register</a></li>
    ';
}

// Replacing user menu section in template
$template = str_replace('{{USER_MENU}}', $user_menu, $template);

// Adding JavaScript code to display upload form
$upload_form_script = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    var uploadBtn = document.getElementById("uploadBtn");
    if (uploadBtn) {
        uploadBtn.addEventListener("click", function() {
            var uploadForm = document.createElement("div");
            uploadForm.innerHTML = `
                <div id="uploadFormContainer" class="upload-form-container">
                    <h3>Upload New Song</h3>
                    <form id="uploadForm" enctype="multipart/form-data" method="POST" action="upload_song.php">
                        <input type="text" name="artist" placeholder="Artist Name" required>
                        <input type="text" name="songName" placeholder="Song Name" required>
                        <input type="file" name="song" accept="audio/*" required><label for="song">Upload Song</label>
                        <input type="file" name="poster" accept="image/*" required><label for="poster">Upload Poster</label>
                        <textarea name="description" placeholder="Song Description" required></textarea>
                        <input type="text" name="tags" placeholder="Tags (separated by commas)" required>
                        <select name="genre" required>
                            <option value="">Select Genre</option>
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
                        <div class="upload-form-buttons">
                            <button type="submit">Upload</button>
                            <button type="button" onclick="closeUploadForm()">Close</button>
                        </div>
                    </form>
                </div>
            `;
            document.body.appendChild(uploadForm);
        });
    }
});

function closeUploadForm() {
    var uploadFormContainer = document.getElementById("uploadFormContainer");
    if (uploadFormContainer) {
        uploadFormContainer.remove();
    }
}

function deleteSong(songId) {
    if (confirm("Are you sure you want to delete this song?")) {
        fetch("delete_song.php?id=" + songId, {
            method: "GET"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("Song deleted successfully.");
                location.reload();
            } else {
                alert("Error deleting song: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error connecting to server");
        });
    }
}
</script>
';

// Adding script to the end of the template
$template .= $upload_form_script;

// Replacing session variables in template
$template = str_replace('<?php if(isset($_SESSION[\'is_admin\']) && $_SESSION[\'is_admin\'] === true): ?>', 
    (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) ? '' : '<!--', $template);
$template = str_replace('<?php endif; ?>', 
    (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) ? '' : '-->', $template);

// Displaying template
echo $template;
// Displaying upload success message
if (isset($_GET['upload_success']) && $_GET['upload_success'] == 'true') {
    echo '<div class="success-message">Song uploaded successfully.</div>';
}

// Displaying uploaded songs
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meshki";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error connecting to database: " . $conn->connect_error);
}

// Pagination settings
$songs_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $songs_per_page;

// Query to get songs
$sql = "SELECT * FROM tblsongs ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $songs_per_page);
$stmt->execute();
$result = $stmt->get_result();

// Calculating total pages
$sql_count = "SELECT COUNT(*) as total FROM tblsongs";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $songs_per_page);

if ($result->num_rows > 0) {
    echo '<div class="music-containers">';
    while($row = $result->fetch_assoc()) {
        echo '<div class="music-container">';
        echo '<h2 class="music-title">' . $row["songName"] . ' - ' . $row["artist"] . '</h2>';
        echo '<img src="' . $row["posterPath"] . '" alt="' . $row["songName"] . ' poster" class="music-poster">';
        echo '<p class="music-description">' . $row["description"] . '</p>';
        echo '<div class="music-tags">';
        $tags = explode(',', $row["tags"]);
        foreach ($tags as $tag) {
            echo '<span>' . trim($tag) . '</span>';
        }
        echo '</div>';
        echo '<button class="show-more-btn" onclick="togglePlayer(this)">Show More</button>';
        echo '<div class="music-player" style="display: none;">';
        echo '<audio controls>';
        echo '<source src="' . $row["songPath"] . '" type="audio/mpeg">';
        echo 'Your browser does not support the audio element.';
        echo '</audio>';
        echo '</div>';
        if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']):
            echo '<button class="delete-btn" onclick="deleteSong(' . $row["id"] . ')">X</button>';
        endif;
        echo '</div>';
    }
    
    // Adding pagination controls
    echo '<div class="pagination-container">';
    echo '<div class="pagination">';
    if ($page > 1) {
        echo '<a href="?page='.($page-1).'" class="prev">Previous</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?page='.$i.'" '.($page == $i ? 'class="active"' : '').'>'.$i.'</a>';
    }
    if ($page < $total_pages) {
        echo '<a href="?page='.($page+1).'" class="next">Next</a>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<script>
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
    </script>';
} else {
    echo "No songs found.";
}

$conn->close();

$content = "Main content of the page";

// Creating upload_song.php file for processing song upload
$upload_song_file = <<<EOT
<?php
if (\$_SERVER["REQUEST_METHOD"] == "POST") {
    // Receiving form data
    \$artist = \$_POST['artist'];
    \$songName = \$_POST['songName'];
    \$description = \$_POST['description'];
    \$tags = \$_POST['tags'];
    \$genre = \$_POST['genre'];

    // Uploading song file
    \$target_dir_song = "mdata/";
    \$target_file_song = \$target_dir_song . basename(\$_FILES["song"]["name"]);
    move_uploaded_file(\$_FILES["song"]["tmp_name"], \$target_file_song);

    // Uploading poster file
    \$target_dir_poster = "admin/posters/";
    \$target_file_poster = \$target_dir_poster . basename(\$_FILES["poster"]["name"]);
    move_uploaded_file(\$_FILES["poster"]["tmp_name"], \$target_file_poster);

    // Saving data to database
    \$servername = "localhost";
    \$username = "root";
    \$password = "";
    \$dbname = "meshki";

    \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

    if (\$conn->connect_error) {
        die("Connection failed: " . \$conn->connect_error);
    }

    \$sql = "INSERT INTO tblsongs (artist, songName, songPath, posterPath, description, tags, genre, upload_date)
    VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())";

    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param("sssssss", \$artist, \$songName, \$target_file_song, \$target_file_poster, \$description, \$tags, \$genre);

    if (\$stmt->execute()) {
        echo "<script>alert('Song uploaded successfully.'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error uploading song: " . \$stmt->error;
    }

    \$stmt->close();
    \$conn->close();
}
?>
EOT;

file_put_contents('upload_song.php', $upload_song_file);

// Creating delete_song.php file for processing song deletion
$delete_song_file = <<<EOT
<?php
if (isset(\$_GET['id'])) {
    \$songId = \$_GET['id'];

    // Connecting to database
    \$servername = "localhost";
    \$username = "root";
    \$password = "";
    \$dbname = "meshki";

    \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

    if (\$conn->connect_error) {
        die(json_encode(["success" => false, "error" => "Error connecting to database: " . \$conn->connect_error]));
    }

    // Deleting song from database
    \$sql = "DELETE FROM tblsongs WHERE id = ?";
    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param("i", \$songId);

    if (\$stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error deleting song: " . \$stmt->error]);
    }

    \$stmt->close();
    \$conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Song ID not provided."]);
}
?>
EOT;

file_put_contents('delete_song.php', $delete_song_file);
