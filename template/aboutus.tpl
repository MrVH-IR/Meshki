<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>درباره ما</title>
    <style>
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
        .about-section {
            background: #ffffff;
            margin-bottom: 20px;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .about-section:nth-child(even) {
            flex-direction: row-reverse;
        }
        .about-image {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 20px;
        }
        .about-content {
            flex: 1;
        }
        .about-content h2 {
            color: #35424a;
            transition: color 0.3s ease;
        }
        .about-content h2:hover {
            color: #e8491d;
        }
        .about-content p, .about-content ul {
            transition: color 0.3s ease;
        }
        .about-content p:hover, .about-content ul:hover {
            color: #e8491d;
        }
        footer {
            padding: 20px;
            margin-top: 20px;
            color: #ffffff;
            background-color: #000000;
            text-align: center;
        }
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
                    <li><a href="index.php">خانه</a></li>
                    <li class="current"><a href="aboutus.php">درباره ما</a></li>
                    <li><a href="#contact-section">تماس با ما</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="about-section">
            <img src="./admin/banners/aboutus1.jpg" alt="تصویر وب‌سایت" class="about-image">
            <div class="about-content">
                <h2>درباره وب‌سایت</h2>
                <p>
                    شروع کار با سایت به عنوان یک پروژه تولید محتوا بود و برای بارگذاری محتواهای متنوع و ارائه آن به مخاطبین خود، تلاش کردیم تا این وب‌سایت را راه‌اندازی کنیم.
                    حال تمام سعی و تلاش ما برای ارائه محتوای با کیفیت به مخاطبین خود را ادامه داده‌ایم تا بتوانیم با این وب‌سایت بهترین خدمات را به مخاطبین خود ارائه دهیم    
               </p>
            </div>
        </div>

        <div class="about-section">
            <img src="./admin/banners/aboutme.jpg" alt="تصویر شخصی" class="about-image">
            <div class="about-content">
                <h2>درباره من</h2>
                <p>
                  محمد رضا وحدت هستم بنیان گار و طراح سایت مشکی
                </p>
            </div>
        </div>

        <div class="about-section">
            <img src="./admin/banners/langsiknow.jpg" alt="تصویر مهارت‌ها" class="about-image">
            <div class="about-content">
                <h2>مهارت‌ها و تخصص‌ها</h2>
                <ul>
                    تخصص من در زمینه تولید محتوا و طراحی سایت هست
                </ul>
            </div>
        </div>

        <div id="contact-section" class="about-section">
            <img src="./admin/banners/callus.jpg" alt="تصویر تماس" class="about-image">
            <div class="about-content">
                <h2>تماس با ما</h2>
                <p>
                  ایمیل: <a href="mailto:vahdatmohammad0@gmail.com">vahdatmohammad0@gmail.com</a><br>
                  تلفن: <a href="tel:09120621901">09120621901</a>
                </p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?> مشکی . تمامی حقوق محفوظ است.</p>
    </footer>
</body>
</html>
