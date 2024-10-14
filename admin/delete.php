<?php
// Directory path for uploads
$uploadDir = 'vdata/';
$uploadDir = '../mdata/';
// $uploadDir = '../mdata/uploads/';
// $uploadDir = '../mdata/posters/';
// $uploadDir = '../mdata/videos/';

// Checking if the file is deleted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fileToDelete'])) {
    $fileToDelete = basename($_POST['fileToDelete']);
    $targetFilePath = $uploadDir . $fileToDelete;

    // Checking if the file exists and deleting it
    if (file_exists($targetFilePath)) {
        if (unlink($targetFilePath)) {
            $message = "File deleted successfully: " . $fileToDelete;
        } else {
            $message = "Error deleting file.";
        }
    } else {
        $message = "File does not exist.";
    }
}

// Getting the list of files in the directory
$files = array_diff(scandir($uploadDir), array('.', '..'));
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
        <h1>Delete Files</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <?php if (!empty($files)) { ?>
            <form action="delete.php" method="post">
                <label for="fileToDelete">Select the file you want to delete:</label>
                <select name="fileToDelete" id="fileToDelete" required>
                    <?php foreach ($files as $file) { ?>
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
