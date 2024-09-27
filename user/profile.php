<?php
session_start();
include '../configure.php';
// include 'profile.tpl';
$template = file_get_contents('profile.tpl');
// Check if user is logged in
if ($_SESSION['user_id'] == null) {
    error_log("User not logged in. Session data: " . print_r($_SESSION, true));
    header("Location: ../login.php");
    exit();
}

// Debug information
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT imgpath, reg_date FROM tblusers WHERE id = '$user_id'";
$result = $conn->query($query);
$user_data = $result->fetch_assoc();

$imgpath = $user_data['imgpath'];
$registration_date = !empty($user_data['reg_date']) ? $user_data['reg_date'] : 'Not Available';
$template = str_replace('{registration_date}', $registration_date, $template);

// Display user profile picture
if (!empty($imgpath) && file_exists('uploads/' . basename($imgpath))) {
    echo '<img src="uploads/' . htmlspecialchars(basename($imgpath)) . '" alt="Profile Picture" style="width:150px; height:auto;">';
} else {
    echo '<img src="uploads/default.png" alt="Default Profile Picture" style="width:150px; height:auto;">';
}

// Get user's favorite music genre and play a random song from that genre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['genre'])) {
    $selected_genre = $_POST['genre'];
    
    // Search for a song with the selected genre
    $query = "SELECT songPath, songName, artist FROM tblsongs WHERE genre = ? ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $selected_genre);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($song = $result->fetch_assoc()) {
        $base_path = realpath(__DIR__ . '/../');
        $song_path = $base_path . '/' . $song['songPath'];
        error_log("Base path: " . $base_path);
        error_log("Full song path: " . $song_path);
        error_log("File exists: " . (file_exists($song_path) ? 'Yes' : 'No'));
        error_log("File readable: " . (is_readable($song_path) ? 'Yes' : 'No'));
        error_log("File size: " . filesize($song_path) . " bytes");
        
        if (file_exists($song_path) && is_readable($song_path)) {
            $relative_path = '/Meshki/mdata/' . basename($song['songPath']);
            echo '<audio controls autoplay>
                    <source src="' . htmlspecialchars($relative_path) . '" type="audio/mpeg">
                    Your browser does not support the audio element.
                  </audio>
                  <p>Playing: ' . htmlspecialchars($song['songName']) . ' - ' . htmlspecialchars($song['artist']) . '</p>';
            echo '<p>مسیر فایل: ' . htmlspecialchars($relative_path) . '</p>';
        } else {
            echo '<p>Song file not found or not readable. Path: ' . htmlspecialchars($song_path) . '</p>';
        }
    } else {
        echo '<p>No song found with this genre.</p>';
    }
    exit; // Exit after sending response
}

echo $template;
?>
