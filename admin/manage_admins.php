<?php
// Path to the admin file
$adminFile = 'admins.txt';

// Adding a new admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_admin'])) {
    $newAdmin = trim($_POST['new_admin']);
    if (!empty($newAdmin)) {
        file_put_contents($adminFile, $newAdmin . PHP_EOL, FILE_APPEND);
        $message = "New admin added successfully: " . htmlspecialchars($newAdmin);
    } else {
        $message = "Please enter the admin's name.";
    }
}

// Reading the list of admins
$admins = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <link rel="stylesheet" href="CSS/styles.css">
</head>
<body>
    <div class="manage-admins-container">
        <h1>Manage Admins</h1>
        
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>

        <!-- Form to add a new admin -->
        <form action="manage_admins.php" method="post">
            <input type="text" name="new_admin" placeholder="New Admin Name" required>
            <button type="submit">Add Admin</button>
        </form>

        <!-- Displaying the list of admins -->
        <h2>Admin List:</h2>
        <ul>
            <?php if (!empty($admins)) { ?>
                <?php foreach ($admins as $admin) { ?>
                    <li><?php echo htmlspecialchars($admin); ?></li>
                <?php } ?>
            <?php } else { ?>
                <li>No admins are registered.</li>
            <?php } ?>
        </ul>
    </div>
</body>
</html>
