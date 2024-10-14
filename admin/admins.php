<?php
// File path for admins
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

// Removing an admin from the list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_admin'])) {
    $adminToRemove = trim($_POST['remove_admin']);
    $admins = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (in_array($adminToRemove, $admins)) {
        $admins = array_diff($admins, [$adminToRemove]);
        file_put_contents($adminFile, implode(PHP_EOL, $admins) . PHP_EOL);
        $message = "Admin removed successfully: " . htmlspecialchars($adminToRemove);
    } else {
        $message = "Admin not found.";
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
        <form action="admins.php" method="post">
            <input type="text" name="new_admin" placeholder="New Admin Name" required>
            <button type="submit">Add Admin</button>
        </form>

        <!-- Form to remove an admin -->
        <form action="admins.php" method="post">
            <input type="text" name="remove_admin" placeholder="Admin Name to Remove" required>
            <button type="submit">Remove Admin</button>
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
