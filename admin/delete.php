<?php
include '../includes/init.php';
if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}
// Directory path for uploads
$uploadSongDir = '../mdata/';
$uploadVideoDir = '../mdata/uploads/videos/';
// $uploadDir = '../mdata/';
// $uploadDir = '../mdata/uploads/';
// $uploadDir = '../mdata/posters/';
// $uploadDir = '../mdata/videos/';

// Checking if the file is deleted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['SongToDelete'])) {
    $fileToDelete = basename($_POST['SongToDelete']);
    $targetFilePath = $uploadSongDir . $fileToDelete;

    // Checking if the file exists and deleting it
    if (file_exists($targetFilePath)) {
        if (unlink($targetFilePath)) {
            try {
                $conn = connectToDatabase();
                $sql = "DELETE FROM tblsongs WHERE songPath = :songPath";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':songPath' => $targetFilePath]);
                $message = "File deleted successfully: " . $fileToDelete;
            } catch (PDOException $e) {
                $message = "Error deleting file: " . $e->getMessage();
            }
        } else {
            $message = "Error deleting file.";
        }
    } else {
        $message = "File does not exist.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['VideoToDelete'])) {
    $fileToDelete = basename($_POST['VideoToDelete']);
    $targetFilePath = $uploadVideoDir . $fileToDelete;

    if (file_exists($targetFilePath)) {
        if (unlink($targetFilePath)) {
            try {
                $conn = connectToDatabase();
                $sql = "DELETE FROM tblvids WHERE videoPath = :videoPath";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':videoPath' => $targetFilePath]);
                $message = "File deleted successfully: " . $fileToDelete;
            } catch (PDOException $e) {
                $message = "Error deleting file: " . $e->getMessage();
            }
        } else {
            $message = "Error deleting file.";
        }
    } else {
        $message = "File does not exist.";
    }
}

// Getting the list of files in the directory
$Songfiles = array_diff(scandir($uploadSongDir), array('.', '..'));
$VideoFiles = array_diff(scandir($uploadVideoDir), array('.', '..'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete File</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="delete-container">
        <h1>Delete Song</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <?php if (!empty($Songfiles)) { ?>
            <form action="delete.php" method="post">
                <label for="SongToDelete">Select the file you want to delete:</label>
                <select name="SongToDelete" id="SongToDelete" required>
                    <?php foreach ($Songfiles as $file) { ?>
                        <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Delete</button>
            </form>
        <?php } else { ?>
            <p>No files to delete.</p>
        <?php } ?>
    </div>
    <div class="delete-container">
        <h1>Delete Video</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <?php if (!empty($VideoFiles)) { ?>
            <form action="delete.php" method="post">
                <label for="VideoToDelete">Select the file you want to delete:</label>
                <select name="VideoToDelete" id="VideoToDelete" required>
                    <?php foreach ($VideoFiles as $file) { ?>
                        <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Delete</button>
            </form>
        <?php } else { ?>
            <p>No files to delete.</p>
        <?php } ?>
    </div>
</body>
</html>
