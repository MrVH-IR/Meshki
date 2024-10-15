<?php
// For saving the active menu status
session_start();
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('Location: ./login.php');
    exit;
}


// Set the active page
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

function isActive($currentPage) {
    global $page;
    return $page === $currentPage ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="CSS/style.css">
</head>
<body>
    <div class="flex h-screen bg-gray-100">
        <nav class="w-64 bg-white shadow-md">
            <div class="p-4">
                <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                <ul class="space-y-2">
                    <li class="<?php echo isActive('home'); ?>">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="<?php echo isActive('upload'); ?>">
                        <a href="?page=upload">Upload Files</a>
                    </li>
                    <li class="<?php echo isActive('delete'); ?>">
                        <a href="?page=delete">Delete Files</a>
                    </li>
                    <li class="<?php echo isActive('analytics'); ?>">
                        <a href="?page=analytics">Analytics</a>
                    </li>
                    <li class="<?php echo isActive('blockip'); ?>">
                        <a href="?page=blockip">Block IP</a>
                    </li>
                    <li class="<?php echo isActive('admins'); ?>">
                        <a href="?page=admins">Manage Admins</a>
                    </li>
                    <li class="<?php echo isActive('logout'); ?>">
                        <a href="?page=logout">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="content">
            <?php
            // Display the content based on the active page
            switch ($page) {
                case 'upload':
                    include 'upload.php';
                    break;
                case 'delete':
                    include 'delete.php';
                    break;
                case 'analytics':
                    include 'analytics.php';
                    break;
                case 'blockip':
                    include 'blockip.php';
                    break;
                case 'admins':
                    include 'admins.php';
                    break;
                default:
                    include 'home.php';
                    break;
                case 'logout':
                    include 'logout.php';
                    break;
            }
            ?>
        </div>
    </div>
</body>
</html>
