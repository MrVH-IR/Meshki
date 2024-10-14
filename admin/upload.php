<?php
// Checking if the file is uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    // File types
    $fileType = $_FILES['file']['type'];
    
    // Determining the directory based on the file type
    if (strpos($fileType, 'video/') === 0) {
        // If the file is a video
        $uploadDir = 'vdata/';
    } elseif ($fileType === 'audio/mpeg') {
        // If the file is an mp3
        $uploadDir = '../mdata/';
    } else {
        // Invalid file type
        $message = "Invalid file type. Please upload only videos or mp3 files.";
        exit;
    }

    // Ensuring the storage directory exists
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    // Getting the file information
    $fileName = basename($_FILES['file']['name']);
    $fileTmpPath = $_FILES['file']['tmp_name'];
    $targetFilePath = $uploadDir . $fileName;

    // Transferring the file from temporary to storage directory
    if (move_uploaded_file($fileTmpPath, $targetFilePath)) {
        $message = "File uploaded successfully: " . $fileName;
    } else {
        $message = "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="upload-container">
        <h1>Upload File</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="file">Select your file:</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
