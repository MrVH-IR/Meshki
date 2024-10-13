<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Videos | Meshki</title>
    <script src="../JS/artists.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');
            
            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('active');
                menuToggle.classList.toggle('active');
            });
        });
    </script>
    <style>
       body {
            padding-top: 60px;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        nav {
            background-color: #000;
            padding: 8px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu {
            display: none;
            list-style-type: none;
            padding: 0;
            margin: 0;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #000;
            width: 200px;
        }
        .menu.active {
            display: block;
        }
        .menu li {
            margin-bottom: 8px;
        }
        .menu a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
            display: block;
            padding: 8px;
        }
        .menu a:hover {
            color: #ff69b4;
        }
        .menu-toggle {
            cursor: pointer;
            padding: 8px;
            display: inline-block;
            position: relative;
        }
        .hamburger {
            display: inline-block;
            width: 25px;
            height: 18px;
            position: relative;
        }
        .hamburger span {
            display: block;
            position: absolute;
            height: 2px;
            width: 100%;
            background: #fff;
            border-radius: 2px;
            opacity: 1;
            left: 0;
            transform: rotate(0deg);
            transition: .25s ease-in-out;
        }
        .hamburger span:nth-child(1) {
            top: 0px;
        }
        .hamburger span:nth-child(2) {
            top: 7px;
        }
        .hamburger span:nth-child(3) {
            top: 14px;
        }
        .menu-text {
            display: none;
            color: #fff;
            margin-left: 8px;
            font-size: 14px;
        }
        .menu-toggle:hover .hamburger {
            display: none;
        }
        .menu-toggle:hover .menu-text {
            display: inline-block;
        }
        .background-banner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .time {
            font-size: 14px;
            color: #666;
        }

        /* New style for search form */
        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-form {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 600px;
        }

        .search-form input[type="text"] {
            width: 70%;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #ff69b4;
            border-radius: 25px 0 0 25px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            border-color: #ff1493;
            box-shadow: 0 0 8px rgba(255, 105, 180, 0.6);
        }

        .search-form button[type="submit"] {
            width: 30%;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-form button[type="submit"]:hover {
            background-color: #ff1493;
        }

        /* For mobile devices */
        @media (max-width: 768px) {
            .search-form input[type="text"] {
                width: 60%;
            }
            
            .search-form button[type="submit"] {
                width: 40%;
            }
        }

        /* استایل جدید برای صفحه هنرمندان */
        .artists-grid {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .artists-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
        }

        .artist-item {
            width: 30%;
            margin-bottom: 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            text-align: center;
        }

        .artist-item:hover {
            transform: translateY(-5px);
        }

        .artist-item img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .artist-item h3 {
            font-size: 16px;
            margin: 10px 0;
            color: #961576;
        }

        .artist-item p {
            font-size: 14px;
            margin: 10px;
            color: #666;
        }

        .artist-item a {
            display: block;
            text-align: center;
            color: white;
            padding: 10px;
            text-decoration: none;
            margin: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        

        .artist-item a:hover {
            background-color: #cf26ab;
        }
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            background-color: transparent;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 0 auto;
            width: fit-content;
        }

        .pagination a {
            padding: 10px 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #ff69b4;
            margin: 0 5px;
            border-radius: 5px;
            font-weight: bold;
            color: #ff69b4;
        }

        .pagination a.active {
            background-color: #fff;
            color: #ff69b4;
            border: 2px solid #ff69b4;
        }

        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }

        .pagination .prev, .pagination .next {
            background-color: #ff69b4;
            color: white;
            cursor: pointer;
            border: 2px solid #ff69b4;
        }

        .pagination .prev:hover, .pagination .next:hover {
            background-color: #ddd;
            color: #ff69b4;
        }

        /* برای دستگاه‌های موبایل */
        @media (max-width: 768px) {
            .artists-row {
                flex-direction: column;
                align-items: center;
            }

            .artist-item {
                width: 80%;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
                <div class="menu-toggle" onclick="toggleMenu()">
                    <div class="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="menu-text">Menu</span>
                    </div>
                    <ul class="menu">
                        <li><a href="index.php">Home Page</a></li>
                        <li><a href="Album.php">Album</a></li>
                        <li><a href="music-videos.php">Music Video</a></li>
                        <li><a href="artists.php">Artists</a></li>
                        <li><a href="playlists.php">Playlists</a></li>
                        <li><a href="aboutus.php">About Us</a></li>
                        <?php if(isset($_SESSION['user_id']) || isset($_SESSION['is_admin'])): ?>
                        <li><a href="user/profile.php">Profile</a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                        </nav>
    </header>
    <main>
    <div class="search-container">
        <form class="search-form" action="artists.php" method="GET">
            <input type="text" name="query" placeholder="Search...">
            <button type="submit">Search</button>
        </form>
    </div>
<main>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
        </div>
    <div class="artists-container">
        <div class="artists-grid">
            <div class="artist-item">
                <!-- Artist content here -->
            </div>
        </div>
    </div>
</main>
</body>
<!-- <footer style="text-align: center; position: fixed; bottom: 0; left: 0; width: 100%; padding: 10px 0;">
    <p style="color: pink; margin: 0;">&copy; <?php echo date('Y'); ?> Meshki. All rights reserved.</p>
</footer> -->

</html>
