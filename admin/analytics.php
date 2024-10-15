<?php
if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}
// Directory paths for uploads
$uploadDirs = [
    '../mdata/',
    '../mdata/uploads/videos/',
    '../mdata/uploads/posters/',
    'posters/',
    'playlists/'
];

$totalFiles = 0;
$totalSize = 0;

foreach ($uploadDirs as $dir) {
    $files = array_diff(scandir($dir), array('.', '..'));
    $totalFiles += count($files);
    foreach ($files as $file) {
        $totalSize += filesize($dir . $file);
    }
}

// Converting the size to megabytes
$totalSizeMB = $totalSize / (1024 * 1024);

// File to save IP addresses
$viewsFile = 'views.txt'; // File name to save the number of views

// Getting the IP address of the user
$ipAddress = $_SERVER['REMOTE_ADDR'];

// Saving the IP address in the file
if (file_exists($viewsFile)) {
    $ipAddresses = file($viewsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES); // Reading IP addresses from the file
    if (!in_array($ipAddress, $ipAddresses)) {
        file_put_contents($viewsFile, $ipAddress . PHP_EOL, FILE_APPEND); // Saving the new IP address
    }
} else {
    // If the file does not exist, save the IP address in it
    file_put_contents($viewsFile, $ipAddress . PHP_EOL);
}

// Reading IP addresses for display
$ipAddresses = file($viewsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$totalUniqueVisits = count(array_unique($ipAddresses)); // Number of unique visits

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="analytics-container">
        <h1>Analytics</h1>
        <p>Number of uploaded files: <?php echo $totalFiles; ?></p>
        <p>Total size of files: <?php echo number_format($totalSizeMB, 2); ?> MB</p>
        <p>Number of unique visits: <?php echo $totalUniqueVisits; ?></p> <!-- Number of unique visits -->
        
        <h2>IP Addresses of Visitors:</h2>
        <ul>
            <?php foreach (array_unique($ipAddresses) as $ip) { ?>
                <li><?php echo htmlspecialchars($ip); ?></li> <!-- Displaying IP addresses -->
            <?php } ?>
        </ul>
    </div>
</body>
</html>
