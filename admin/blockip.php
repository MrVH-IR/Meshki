<?php
if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}

// File to save blocked IP addresses
$ipFile = 'blocked_ips.txt';

// Adding IP to the list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['block_ip'])) {
    $ipToBlock = trim($_POST['block_ip']);

    // Checking if the IP format is correct
    if (filter_var($ipToBlock, FILTER_VALIDATE_IP)) {
        // Checking if the IP is not already blocked
        $blockedIps = file($ipFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (!in_array($ipToBlock, $blockedIps)) {
            file_put_contents($ipFile, $ipToBlock . PHP_EOL, FILE_APPEND);
            $message = "IP blocked successfully: " . $ipToBlock;
        } else {
            $message = "This IP is already blocked.";
        }
    } else {
        $message = "Please enter a valid IP.";
    }
}

// Removing IP from the list
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['unblock_ip'])) {
    $ipToUnblock = trim($_POST['unblock_ip']);
    $blockedIps = file($ipFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $blockedIps = array_diff($blockedIps, [$ipToUnblock]);
    file_put_contents($ipFile, implode(PHP_EOL, $blockedIps) . PHP_EOL);
    $message = "IP removed from blocked list: " . $ipToUnblock;
}

// Getting the list of blocked IP addresses
$blockedIps = file($ipFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Block IP</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="blockip-container">
        <h1>Block IP</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <!-- Form to block IP -->
        <form action="blockip.php" method="post">
            <label for="block_ip">Enter IP:</label>
            <input type="text" name="block_ip" id="block_ip" required>
            <button type="submit">Block</button>
        </form>

        <!-- Displaying the list of blocked IP addresses -->
        <?php if (!empty($blockedIps)) { ?>
            <h2>Blocked IP List:</h2>
            <ul>
                <?php foreach ($blockedIps as $ip) { ?>
                    <li>
                        <?php echo $ip; ?>
                        <form action="blockip.php" method="post" style="display:inline;">
                            <input type="hidden" name="unblock_ip" value="<?php echo $ip; ?>">
                            <button type="submit">Remove from list</button>
                        </form>
                    </li>
                <?php } ?>
            </ul>
        <?php } else { ?>
            <p>No IP is blocked.</p>
        <?php } ?>
    </div>
    <div class="unblockip-container">
        <h1>Unblock IP</h1>
        <?php if (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
        
        <!-- Form to unblock IP -->
        <form action="blockip.php" method="post">
            <label for="unblock_ip">Enter IP:</label>
            <input type="text" name="unblock_ip" id="unblock_ip" required>
            <button type="submit">Unblock</button>
        </form>
    </div>
</body>
</html>
