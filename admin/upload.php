<?php
include '../includes/init.php';
if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}

// Checking if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Checking if the song file is uploaded
    if (isset($_FILES['song'])) {
        $fileType = $_FILES['song']['type'];
        $uploadDir = '../mdata/';
        $artist = $_POST['artist'];
        $songName = $_POST['songName'];
        $genre = $_POST['genre'];
        $songPath = 'mdata/' . basename($_FILES['song']['name']);
        $posterPath = 'admin/posters/' . basename($_FILES['poster']['name']);
        $description = $_POST['description'];
        $tags = $_POST['tags'];
        try {
            global $conn;
            $conn = connectToDatabase();
            $sql = "INSERT INTO tblsongs (artist, songName, genre, songPath, posterPath, description, tags , upload_Date) VALUES (:artist, :songName, :genre, :songPath, :posterPath, :description, :tags, :upload_Date)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':artist' => $artist,
                ':songName' => $songName,
                ':genre' => $genre,
                ':songPath' => $songPath,
                ':posterPath' => $posterPath,
                ':description' => $description,
                ':tags' => $tags,
                ':upload_Date' => date('Y-m-d')
            ]);
        } catch (PDOException $e) {
            $message = "Error uploading song.";
        }

        // Ensuring the storage directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Getting the file information
        $fileName = basename($_FILES['song']['name']);
        $fileTmpPath = $_FILES['song']['tmp_name'];
        $targetFilePath = $uploadDir . $fileName;

        // Transferring the file from temporary to storage directory
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            $message = "Song uploaded successfully: " . $fileName;
        } else {
            $message = "Error uploading song.";
        }
    }

    // Checking if the poster file is uploaded
    if (isset($_FILES['poster'])) {
        $fileType = $_FILES['poster']['type'];
        $uploadDir = 'posters/';

        // Ensuring the storage directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Getting the file information
        $fileName = basename($_FILES['poster']['name']);
        $fileTmpPath = $_FILES['poster']['tmp_name'];
        $targetFilePath = $uploadDir . $fileName;

        // Transferring the file from temporary to storage directory
        if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
            $message = "Poster uploaded successfully: " . $fileName;
        } else {
            $message = "Error uploading poster.";
        }
    }

    // Checking if the video file is uploaded
    if (isset($_FILES['video'])) {
        $fileType = $_FILES['video']['type'];
        $uploadDirVideo = '../mdata/uploads/videos/';
        $uploadDirPoster = '../mdata/uploads/posters/';

        // Ensuring the storage directories exist
        if (!is_dir($uploadDirVideo)) {
            mkdir($uploadDirVideo, 0755, true);
        }
        if (!is_dir($uploadDirPoster)) {
            mkdir($uploadDirPoster, 0755, true);
        }

        // Getting the video file information
        $videoFileName = basename($_FILES['video']['name']);
        $videoFileTmpPath = $_FILES['video']['tmp_name'];
        $targetVideoFilePath = $uploadDirVideo . $videoFileName;

        // Transferring the video file from temporary to storage directory
        if (move_uploaded_file($videoFileTmpPath, $targetVideoFilePath)) {
            $videoPath = ltrim(str_replace('..', '', $targetVideoFilePath), '/');
        } else {
            $message = "Error uploading video.";
        }

        // Getting the poster file information
        $posterFileName = basename($_FILES['poster']['name']);
        $posterFileTmpPath = $_FILES['poster']['tmp_name'];
        $targetPosterFilePath = $uploadDirPoster . $posterFileName;

        // Transferring the poster file from temporary to storage directory
        $thumbnailPath = ltrim(str_replace('..', '', $targetPosterFilePath), '/');
        var_dump($thumbnailPath);

        // Saving data to database
        try {
            global $conn;
            $conn = connectToDatabase();
            $sql = "INSERT INTO tblvids (title, description, videoPath, thumbnailPath, upload_date)
                    VALUES (:title, :description, :videoPath, :thumbnailPath, CURDATE())";

            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':title' => $_POST['title'],
                ':description' => $_POST['description'],
                ':videoPath' => $videoPath,
                ':thumbnailPath' => $thumbnailPath,
            ]);

            $message = "Video uploaded successfully: " . $videoFileName;
        } catch (PDOException $e) {
            $message = "Error uploading video: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="upload-container">
        <h1>Upload Song</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="text" name="artist" placeholder="Artist Name" required>
            <input type="text" name="songName" placeholder="Song Name" required>
            <input type="file" name="song" id="songFile" accept="audio/*" required style="display: none;">
            <label for="songFile" class="custom-file-upload">Select Song File</label>
            <span id="songFileName"></span>
            <input type="file" name="poster" id="posterFile" accept="image/*" required style="display: none;">
            <label for="posterFile" class="custom-file-upload">Select Poster Image</label>
            <span id="posterFileName"></span>
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
            <button type="submit">Upload</button>
        </div>
    </form>
    <div class="upload-video">
        <h1>Upload Video</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <input type="text" name="artist" placeholder="Artist Name" required>
            <input type="text" name="title" placeholder="Title" required>
            <input type="text" name="description" placeholder="Description" required>
            <input type="text" name="tags" placeholder="Tags (separated by commas)" required>
            <input type="file" name="poster" id="poster" accept="image/*" required style="display: none;">
            <label for="poster" class="custom-file-upload">Select Poster Image</label>
            <span id="posterFileName"></span>
            <input type="file" name="video" id="video" accept=".mp4" required style="display: none;">
            <label for="video" class="custom-file-upload">Select Video File</label>
            <span id="videoFileName"></span>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
