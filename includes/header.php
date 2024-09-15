<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'مشکی'; ?></title>
    <style>
        /* استایل‌های عمومی را اینجا قرار دهید */
        body {
            font-family: 'Vazir', Tahoma, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            overflow: hidden;
            padding: 20px;
        }
        header {
            background: #000000;
            color: #ffffff;
            padding: 20px 0;
            border-bottom: #e8491d 3px solid;
        }
        header a {
            color: #ffffff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }
        header ul {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        header li {
            display: inline;
            padding: 0 20px;
        }
        header #branding {
            float: right;
        }
        header #branding h1 {
            margin: 0;
        }
        header nav {
            float: left;
            margin-top: 10px;
        }
        header .highlight, header .current a {
            color: #e8491d;
            font-weight: bold;
        }
        header a:hover {
            color: #ffffff;
            font-weight: bold;
        }
        /* اضافه کردن استایل‌های دیگر مورد نیاز */
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1><span class="highlight">Meshki</span> وب‌سایت</h1>
            </div>
            <nav>
                <ul>
                    <li<?php echo $currentPage == 'home' ? ' class="current"' : ''; ?>><a href="index.php">خانه</a></li>
                    <li<?php echo $currentPage == 'music_videos' ? ' class="current"' : ''; ?>><a href="music_videos.php">موزیک ویدیوها</a></li>
                    <li<?php echo $currentPage == 'aboutus' ? ' class="current"' : ''; ?>><a href="aboutus.php">درباره ما</a></li>
                    <li<?php echo $currentPage == 'contact' ? ' class="current"' : ''; ?>><a href="contact.php">تماس با ما</a></li>
                </ul>
            </nav>
        </div>
    </header>
