<?php
function generate_header($title, $search_query = '') {
    $header = <<<EOT
    <!DOCTYPE html>
    <html lang="fa" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>$title | مشکی</title>
        <link rel="stylesheet" href="CSS/common.css">
        <link rel="stylesheet" href="CSS/pagination.css">
        <style>
            .upload-btn {
                background-color: #4CAF50;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            .upload-btn:hover {
                background-color: #45a049;
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
                    <span class="menu-text">منو</span>
                </div>
                <ul class="menu">
                    <li><a href="index.php">صفحه اصلی</a></li>
                    <li><a href="music.php">موزیک</a></li>
                    <li><a href="music-videos.php">موزیک ویدیو</a></li>
                    <li><a href="artists.php">هنرمندان</a></li>
                    <li><a href="playlists.php">پلی‌لیست‌ها</a></li>
                    <li><a href="aboutus.php">درباره ما</a></li>
                    <?php if(isset(\$_SESSION['user_id']) || isset(\$_SESSION['is_admin'])): ?>
                    <li><a href="profile.php">پروفایل من</a></li>
                    <li><a href="logout.php">خروج</a></li>
                    <?php else: ?>
                    <li><a href="login.php">ورود</a></li>
                    <li><a href="register.php">ثبت نام</a></li>
                    <?php endif; ?>
                </ul>
                <?php if(isset(\$_SESSION['is_admin']) && \$_SESSION['is_admin'] === true): ?>
                    <button id="uploadVideoBtn" class="upload-btn">آپلود ویدیو</button>
                    <div id="uploadFormContainer" class="upload-form-container" style="display: none;">
                     <h3>آپلود ویدیو جدید</h3>
                  <form id="uploadForm" action="./upload_video.php" method="post" enctype="multipart/form-data">
                     <input type="text" name="artist" placeholder="نام هنرمند" required>
                    <input type="text" name="title" placeholder="نام موزیک ویدیو" required>
                    <textarea name="description" placeholder="توضیحات" required></textarea>
                    <input type="text" name="tags" placeholder="تگ‌ها (با کاما جدا کنید)" required>
                    <input type="file" name="videoFile" id="videoFile" accept="video/*" required>
                    <input type="file" name="thumbnailFile" id="thumbnailFile" accept="image/*" required>
                    <button type="submit">آپلود</button>
                    <button type="button" id="closeFormBtn">بستن</button>
            </form>
        </div>
                <?php endif; ?>
            </nav>
            <script>
        
        document.getElementById('uploadVideoBtn').addEventListener('click', function() {
            document.getElementById('uploadFormContainer').style.display = 'block';
        });

        
        document.getElementById('closeFormBtn').addEventListener('click', function() {
            document.getElementById('uploadFormContainer').style.display = 'none';
        });
    </script>
            // <div class="search-container">
            //     <form action="search.php" method="GET" class="search-form">
            //         <input type="text" name="q" placeholder="جستجوی آهنگ یا هنرمند" value="$search_vids" required>
            //         <button type="submit">جستجو</button>
            //     </form>
            </div>
        </header>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="بنر پس زمینه" class="background-image">
        </div>
    EOT;
    return $header;
}

function generate_footer() {
    $footer = <<<EOT
        <footer>
            <!-- فوتر را اینجا قرار دهید -->
        </footer>
        <script>
            function toggleMenu() {
                var menu = document.querySelector('.menu');
                menu.classList.toggle('active');
            }
            function togglePlayer(button) {
                var player = button.nextElementSibling;
                if (player.style.display === "none") {
                    player.style.display = "block";
                    button.textContent = "نمایش کمتر";
                } else {
                    player.style.display = "none";
                    button.textContent = "نمایش بیشتر";
                }
            }
        </script>
    </body>
    </html>
    EOT;
    return $footer;
}

function generate_music_container($song) {
    $music_container = <<<EOT
    <div class="music-container">
        <h2 class="music-title">{$song["songName"]} - {$song["artist"]}</h2>
        <img src="{$song["posterPath"]}" alt="{$song["songName"]} پوستر" class="music-poster">
        <p class="music-description">{$song["description"]}</p>
        <div class="music-tags">
    EOT;
    
    $tags = explode(',', $song["tags"]);
    foreach ($tags as $tag) {
        $music_container .= '<span>' . trim($tag) . '</span>';
    }
    
    $music_container .= <<<EOT
        </div>
        <button class="show-more-btn" onclick="togglePlayer(this)">نمایش بیشتر</button>
        <div class="music-player" style="display: none;">
            <audio controls>
                <source src="{$song["songPath"]}" type="audio/mpeg">
                مرورگر شما از پخش صوتی پشتیبانی نمی‌کند.
            </audio>
        </div>
    </div>
    EOT;
    
    return $music_container;
}

function generate_pagination($total_pages, $current_page, $base_url) {
    $pagination = '<div class="pagination-container"><div class="pagination">';
    if ($current_page > 1) {
        $pagination .= "<a href=\"{$base_url}&page=" . ($current_page-1) . "\" class=\"prev\">قبلی</a>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        $pagination .= "<a href=\"{$base_url}&page={$i}\" " . ($current_page == $i ? 'class="active"' : '') . ">{$i}</a>";
    }
    if ($current_page < $total_pages) {
        $pagination .= "<a href=\"{$base_url}&page=" . ($current_page+1) . "\" class=\"next\">بعدی</a>";
    }
    $pagination .= '</div></div>';
    return $pagination;
}