<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دانلود و پخش آنلاین موزیک | مشکی</title>
    <meta name="description" content="بهترین مکان برای دانلود، گوش دادن و تماشای آنلاین موزیک‌های جدید. گسترده‌ترین مجموعه موسیقی با کیفیت بالا.">
    <meta name="keywords" content="دانلود موزیک, پخش آنلاین, آهنگ جدید, دانلود رایگان">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="دانلود و پخش آنلاین موزیک | مشکی">
    <meta property="og:description" content="بهترین مکان برای دانلود، گوش دادن و تماشای آنلاین موزیک‌های جدید. گسترده‌ترین مجموعه موسیقی با کیفیت بالا.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.meshki.com">
    <link rel="canonical" href="https://www.meshki.com">
    <link rel="stylesheet" href="pagination.css">
</head>
<body>
    <header>
        <nav>
            <div class="menu-toggle">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="menu-text">منو</span>
            </div>
            <ul class="menu">
                <li><a href="../meshki/index.php">صفحه اصلی</a></li>
                <li><a href="../meshki/music.php">موزیک</a></li>
                <li><a href="../meshki/music-videos.php">موزیک ویدیو</a></li>
                <li><a href="../meshki/artists.php">هنرمندان</a></li>
                <li><a href="../meshki/playlists.php">پلی‌لیست‌ها</a></li>
                <li><a href="../meshki/aboutus.php">درباره ما</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="../meshki/profile.php">پروفایل من</a></li>
                    <li><a href="../meshki/logout.php">خروج</a></li>
                <?php else: ?>
                    <li><a href="../meshki/login.php">ورود</a></li>
                    <li><a href="../meshki/register.php">ثبت نام</a></li>
                <?php endif; ?>
            </ul>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                <button id="uploadBtn" class="upload-btn">آپلود</button>
            <?php endif; ?>
        </nav>
    </header>

    <main>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="بنر پس زمینه" class="background-image">
        </div>
        <div id="uploadedSongs"></div>
    </main>
