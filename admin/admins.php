<?php
// File path for admins
$adminFile = 'admins.txt';

include '../includes/init.php';

if (isset($_SESSION['pending_admin']) && $_SESSION['pending_admin'] == true) {
    header("Location: ./dashboard.php");
    exit();
}

// Adding a new admin
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_admin'])) {
    $newAdmin = trim($_POST['new_admin']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $role = trim($_POST['role']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $admininfo = $newAdmin . "," . $hashed_password . "," . $email . "," . $phone . "," . $role . PHP_EOL;
    if (!empty($admininfo)) {
        file_put_contents($adminFile, $admininfo, FILE_APPEND);
        $message = "New admin added successfully: " . htmlspecialchars($admininfo);
    } else {
        $message = "Please enter the admin's name.";
    }
    
    try {
        $conn = connectToDatabase();
        $sql = "INSERT INTO tbladmins (username , password , phone , email , role , is_admin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$newAdmin, $hashed_password, $phone, $email, $role, 0]);
        $message = "New admin added successfully: " . htmlspecialchars($admininfo);
    } catch (PDOException $e) {
        $message = "Error fetching admins: " . $e->getMessage();
    }
}
// Fetch Pending Admins
try {
    $conn = connectToDatabase();
    $sql = "SELECT username FROM tbladmins WHERE is_admin = 0";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $pendingAdmins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // تبدیل آرایه به رشته برای استفاده در htmlspecialchars
    $pendingAdminsString = implode(', ', array_column($pendingAdmins, 'username'));
    $message = "Pending Admins: " . htmlspecialchars($pendingAdminsString);
} catch (PDOException $e) {
    $message = "Error fetching admins: " . $e->getMessage();
}
// Remove Admin From List
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_admin'])) {
    $adminToRemove = trim($_POST['remove_admin']);
    $admins = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Check Admin In Txt File
    $adminFound = false;
    foreach ($admins as $admin) {
        $adminInfo = explode(',', $admin);
        if ($adminInfo[0] === $adminToRemove) {
            $adminFound = true;
            break;
        }
    }
    
    if ($adminFound) {
        // Delete From Txt File
        $admins = array_filter($admins, function($admin) use ($adminToRemove) {
            return strpos($admin, $adminToRemove . ',') !== 0;
        });
        file_put_contents($adminFile, implode(PHP_EOL, $admins) . PHP_EOL);
        
        try {
            $conn = connectToDatabase();
            $sql = "DELETE FROM tbladmins WHERE username = :username";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':username', $adminToRemove, PDO::PARAM_STR);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $message = "Admin Removed Successfully: " . htmlspecialchars($adminToRemove);
            } else {
                $message = "Admin Not Removed From Database. Please Contact System Manager.";
            }
        } catch (PDOException $e) {
            $message = "Error Removing Admin: " . $e->getMessage();
        }
    } else {
        $message = "Admin Not Found.";
    }
}

// Approve Pending Admins
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve_admin'])) {
    $adminToApprove = trim($_POST['approve_admin']);
    $admins = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Update Admin Status In Database
    try {
        $conn = connectToDatabase();
        $sql = "UPDATE tbladmins SET is_admin =1 WHERE username = :username";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $adminToApprove, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $message = "Admin Approved Successfully: " . htmlspecialchars($adminToApprove);
        } else {
            $message = "Admin Not Approved. Please Contact System Manager.";
        }
    } catch (PDOException $e) {
        $message = "Error Approving Admin: " . $e->getMessage();
    }
}

// Reading the list of admins
$admins = array();
$lines = file($adminFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $parts = explode(',', $line);
    if (count($parts) >= 5) {
        $admins[] = $parts[0] . ',' . $parts[2] . ',' . $parts[3] . ',' . $parts[4];
    }
}
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
            <input type="text" name="password" placeholder="Password" required>
            <input type="text" name="email" placeholder="Email" required>
            <select name="role">
                <option value="admin">Adminstrator</option>
                <option value="level1">Level 1</option>
                <option value="level2">Level 2</option>
                <option value="level3">Level 3</option>
            </select>
            <input type="text" name="phone" placeholder="Phone" required>
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
    <div class="pending-admin-container">
        <h1>Pending Admins</h1>
        <form action="admins.php" method="post">
        <ul>
            <?php if (!empty($pendingAdmins)) { ?>
                <?php foreach ($pendingAdmins as $pendingAdmin) { ?>
                    <li style="margin: 20px 0;">
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <span><?php echo htmlspecialchars($pendingAdmin['username']); ?></span>
                            <div style="display: flex; align-items: center; margin-top: 10px;">
                                <input type="checkbox" name="approve_admin[]" value="<?php echo htmlspecialchars($pendingAdmin['username']); ?>" style="margin-right: 10px;">
                                <button type="submit" name="approve_button" value="<?php echo htmlspecialchars($pendingAdmin['username']); ?>">Approve Admin</button>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            <?php } else { ?>
                <li>No pending admins.</li>
            <?php } ?>
        </ul>
        </form>
    </div>
</body>
</html>
