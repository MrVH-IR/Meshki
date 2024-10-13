<?php
session_start();
include '../includes/init.php';
global $conn;
// include 'profile.tpl';
$template = file_get_contents('profile.tpl');
// Check if user is logged in
if ($_SESSION['user_id'] == null) {
    // error_log("User not logged in. Session data: " . print_r($_SESSION, true));
    header("Location: ../login.php");
    exit();
}

// Debug information
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// echo "<pre>";
// print_r($_SESSION);
// echo "</pre>";


// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$query = "SELECT imgpath, reg_date ,gender FROM tblusers WHERE id = :user_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

$imgpath = $user_data['imgpath'];
$gender = $user_data['gender'];
$registration_date = !empty($user_data['reg_date']) ? $user_data['reg_date'] : 'Not Available';
$template = str_replace('{registration_date}', $registration_date, $template);

// Display user profile picture
if (!empty($imgpath) && file_exists('uploads/' . basename($imgpath))) {
    echo '<img src="uploads/' . htmlspecialchars(basename($imgpath)) . '" alt="تصویر نمایه" style="width:150px; height:auto;">';
} else {
    if ($gender == 'female') {
        echo '<img src="uploads/default1.png" alt="Default Female Profile Picture" style="width:150px; height:auto;">';
    } else {
        echo '<img src="uploads/default.png" alt="Default Male Profile Picture" style="width:150px; height:auto;">';
    }
}

// Get user's favorite music genre and play a random song from that genre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['genre'])) {
    $selected_genre = $_POST['genre'];
    
    // Search for a song with the selected genre
    $query = "SELECT songPath, songName, artist FROM tblsongs WHERE genre = :genre ORDER BY RAND() LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':genre', $selected_genre, PDO::PARAM_STR);
    $stmt->execute();
    $song = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($song) {
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
                  <p>Playing: ' . htmlspecialchars($song['songName']) . ' - ' . htmlspecialchars($song['artist']) . '</p>
                  <a href="' . htmlspecialchars($relative_path) . '" download>Download Song</a>'; // Download button
            // echo '<p>مسیر فایل: ' . htmlspecialchars($relative_path) . '</p>';
        } else {
            echo '<p>Song file not found or not readable. Path: ' . htmlspecialchars($song_path) . '</p>';
        }
    } else {
        echo '<p>No song found with this genre.</p>';
    }
    exit; // Exit after sending response
}
// ---- Verification Process ---- //

// Check if the user's email is verified
try {
    $query = "SELECT is_verified, email FROM tblusers WHERE id = :user_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $userEmail = $user['email'];
} catch (PDOException $e) {
    die("Error fetching user data: " . $e->getMessage());
}

if ($user['is_verified'] == 0) {
    echo '<div class="hamburger-menu" style="position: fixed; bottom: 10px; right: 10px;">
            <button class="hamburger-button" onclick="toggleForm()">☰ Verify</button>
            <div id="verificationForm" style="display: none;">
                <form action="verify.php" method="post">
                    <input type="text" name="verification_code" placeholder="Enter verification code">
                    <button type="submit">Verify</button>
                    <button type="button" onclick="sendCode()">Send Code (Email)</button>
                </form>
            </div>
          </div>
          <script>
            function toggleForm() {
                var form = document.getElementById("verificationForm");
                if (form.style.display === "none") {
                    form.style.display = "block";
                } else {
                    form.style.display = "none";
                }
            }
            function sendCode() {
                alert("Verification code sent to your email.");
            }
          </script>';
} else {
    echo "<p style='color:pink; position: fixed; bottom: 10px; right: 10px;'>You Are Verified</p>";
}




if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_code'])) {

    // Generate a random verification code
    $verificationCode = rand(100000, 999999);

    require '../vendor/autoload.php'; // or the path to PHPMailer files if added manually

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // mail host
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Your mail email
        $mail->Password = ''; // mail password
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('', 'Meshki'); // Your mail email
        $mail->addAddress($userEmail); // User's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Verification Code';
        $mail->Body    = 'Your verification code is: ' . $verificationCode;

        $mail->send();
        echo 'Verification code has been sent to your email.';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    // Save the code in the database for this user (you can store it in a separate table or directly in `tblusers`)
    $sql = "UPDATE tblusers SET verification_code = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$verificationCode, $user_id]);
}

echo $template;
?>
