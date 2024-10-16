<?php
include '../includes/init.php';
if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}
// Directory path for uploads
$uploadSongDir = '../mdata/';
$uploadVideoDir = '../mdata/uploads/videos/';
$uploadImageDir = '../user/uploads/';
// $uploadDir = '../mdata/';
// $uploadDir = '../mdata/uploads/';
// $uploadDir = '../mdata/posters/';
// $uploadDir = '../mdata/videos/';

function getuser(){
    $conn = connectToDatabase();
    $sql = "SELECT * FROM tblusers";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $users;
}

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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ImageToDelete'])) {
    $fileToDelete = basename($_POST['ImageToDelete']);
    $targetFilePath = $uploadImageDir . $fileToDelete;

    if (file_exists($targetFilePath)) {
        if (unlink($targetFilePath)) {
            try {
                $conn = connectToDatabase();
                $sql = "DELETE FROM tblusers WHERE imgpath = :imgpath";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':imgpath' => $targetFilePath]);
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['UserToDelete'])) {
    $fileToDelete = basename($_POST['UserToDelete']);
    $targetFilePath = $uploadImageDir . $fileToDelete;

    if (file_exists($targetFilePath)) {
        if (unlink($targetFilePath)) {
            try {
                $conn = connectToDatabase();
                $sql = "DELETE FROM tblusers WHERE username = :username";
                $stmt = $conn->prepare($sql);
                $stmt->execute([':username' => $fileToDelete]);
                $message = "User deleted successfully: " . $fileToDelete;
            } catch (PDOException $e) {
                $message = "Error deleting user: " . $e->getMessage();
            }
        } else {
            $message = "Error deleting user.";
        }
    } else {
        $message = "User does not exist.";
    }
}



// Getting the list of files in the directory
$Songfiles = array_diff(scandir($uploadSongDir), array('.', '..'));
$VideoFiles = array_diff(scandir($uploadVideoDir), array('.', '..'));
$ImageFiles = array_diff(scandir($uploadImageDir), array('.', '..'));
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
    <div class="delete-container">
        <h1>Delete Image</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

        <?php if (!empty($ImageFiles)) { ?>
            <form action="delete.php" method="post">
                <label for="ImageToDelete">Select the file you want to delete:</label>
                <select name="ImageToDelete" id="ImageToDelete" required>
                    <?php foreach ($ImageFiles as $file) { ?>
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
        <h1>Delete User</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <?php if (!empty(getuser())) { ?>
            <form action="delete.php" method="post">
                <label for="UserToDelete">Select the user you want to delete:</label>
                <select name="UserToDelete" id="UserToDelete" required>
                    <?php foreach (getuser() as $user) { ?>
                        <option value="<?php echo $user['username']; ?>"><?php echo $user['username']; ?></option>
                    <?php } ?>
                </select>
                <button type="submit">Delete</button>
            </form>
        <?php } else { ?>
            <p>No users to delete.</p>
        <?php } ?>
    </div>
</body>
</html>
