<?php
// Files for different purposes
$uploadDir = '../mdata/';
$adminFile = 'admins.txt';
$ipFile = 'blocked_ips.txt';

// Getting the number of uploaded files
$uploadedFiles = array_diff(scandir($uploadDir), array('.', '..'));
$fileCount = count($uploadedFiles);

// Getting the number of admins
$admins = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$adminCount = count($admins);

// Getting the number of blocked IP addresses
$blockedIps = file($ipFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$blockedIpCount = count($blockedIps);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="home-container">
        <h1>Welcome to the Admin Panel</h1>
        <div class="stats">
            <h2>General Statistics:</h2>
            <ul>
                <li>Number of uploaded files: <?php echo $fileCount; ?></li>
                <li>Number of admins: <?php echo $adminCount; ?></li>
                <li>Number of blocked IPs: <?php echo $blockedIpCount; ?></li>
            </ul>
        </div>
    </div>
</body>
</html>
