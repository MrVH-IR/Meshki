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
                    <?php if(isset(\$_SESSION['user_id'])): ?>
                    <li><a href="profile.php">پروفایل من</a></li>
                    <li><a href="logout.php">خروج</a></li>
                    <?php else: ?>
                    <li><a href="login.php">ورود</a></li>
                    <li><a href="register.php">ثبت نام</a></li>
                    <?php endif; ?>
                </ul>
                <?php if(isset(\$_SESSION['is_admin']) && \$_SESSION['is_admin'] === true): ?>
                    <button id="uploadBtn" class="upload-btn">آپلود آهنگ</button>
                    <button id="newUploadBtn" class="upload-btn">آپلود ویدیو جدید</button>
                <?php endif; ?>
            </nav>
            <div class="search-container">
                <form action="search.php" method="GET" class="search-form">
                    <input type="text" name="q" placeholder="جستجوی آهنگ یا هنرمند" value="$search_query" required>
                    <button type="submit">جستجو</button>
                </form>
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
            // اضافه کردن کد مربوط به فرم آپلود
            const uploadBtn = document.getElementById('uploadBtn');
            if (uploadBtn) {
                uploadBtn.addEventListener('click', function() {
                    const uploadForm = document.createElement('div');
                    uploadForm.innerHTML = `
                        <div id="uploadFormContainer">
                            <h3>آپلود آهنگ جدید</h3>
                            <form id="uploadForm" enctype="multipart/form-data">
                                <input type="text" name="artist" placeholder="نام هنرمند" required>
                                <input type="text" name="songName" placeholder="نام آهنگ" required>
                                <input type="file" name="song" id="songFile" accept="audio/*" required style="display: none;">
                                <label for="songFile" class="custom-file-upload">انتخاب فایل آهنگ</label>
                                <span id="songFileName"></span>
                                <input type="file" name="poster" id="posterFile" accept="image/*" required style="display: none;">
                                <label for="posterFile" class="custom-file-upload">انتخاب تصویر پوستر</label>
                                <span id="posterFileName"></span>
                                <textarea name="description" placeholder="توضیحات آهنگ" required></textarea>
                                <input type="text" name="tags" placeholder="تگ‌ها (با کاما جدا کنید)" required>
                                <div class="upload-form-buttons">
                                    <button type="submit">آپلود</button>
                                    <button type="button" onclick="closeUploadForm()">بستن</button>
                                </div>
                            </form>
                        </div>
                    `;
                    document.body.appendChild(uploadForm);

                    // اضافه کردن event listener برای فرم آپلود
                    const form = document.getElementById('uploadForm');
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        // کد مربوط به ارسال فرم را اینجا قرار دهید
                    });
                });
            }
            function closeUploadForm() {
                const uploadFormContainer = document.getElementById('uploadFormContainer');
                if (uploadFormContainer) {
                    uploadFormContainer.remove();
                }
            }

            // کدهای vbutton.php
            document.addEventListener('DOMContentLoaded', function() {
                const newUploadBtn = document.getElementById('newUploadBtn');
                const uploadedVideos = document.getElementById('uploadedVideos');
                let isFormOpen = false;

                newUploadBtn.addEventListener('click', function() {
                    if (!isFormOpen) {
                        isFormOpen = true;
                        const newUploadForm = document.createElement('div');
                        newUploadForm.innerHTML = `
                            <div id="newUploadFormContainer">
                                <h3>آپلود ویدیو جدید</h3>
                                <form id="newUploadForm" enctype="multipart/form-data">
                                    <input type="text" name="title" placeholder="عنوان" required>
                                    <textarea name="description" placeholder="توضیحات" required></textarea>
                                    <input type="file" name="video" id="newVideoFile" accept="video/*" style="display: none;">
                                    <label for="newVideoFile" class="custom-file-upload">انتخاب فایل ویدیو</label>
                                    <span id="newVideoFileName"></span>
                                    <input type="file" name="thumbnail" id="newThumbnailFile" accept="image/*" style="display: none;">
                                    <label for="newThumbnailFile" class="custom-file-upload">انتخاب تصویر بندانگشتی</label>
                                    <span id="newThumbnailFileName"></span>
                                    <div class="upload-form-buttons">
                                        <button type="submit">آپلود</button>
                                        <button type="button" onclick="closeNewUploadForm()">بستن</button>
                                    </div>
                                </form>
                            </div>
                        `;
                        document.body.appendChild(newUploadForm);

                        document.getElementById('newVideoFile').addEventListener('change', function(e) {
                            document.getElementById('newVideoFileName').textContent = e.target.files[0].name;
                        });

                        document.getElementById('newThumbnailFile').addEventListener('change', function(e) {
                            document.getElementById('newThumbnailFileName').textContent = e.target.files[0].name;
                        });

                        document.getElementById('newUploadForm').addEventListener('submit', function(e) {
                            e.preventDefault();
                            const formData = new FormData(this);
                            fetch('upload_video.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('ویدیو با موفقیت آپلود شد.');
                                    closeNewUploadForm();
                                    displayUploadedVideo(data.video);
                                } else {
                                    alert('خطا در آپلود ویدیو: ' + data.error);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('خطا در آپلود ویدیو: لطفاً دوباره تلاش کنید.');
                            });
                        });
                    }
                });

                function closeNewUploadForm() {
                    const newUploadFormContainer = document.getElementById('newUploadFormContainer');
                    if (newUploadFormContainer) {
                        newUploadFormContainer.remove();
                        isFormOpen = false;
                    }
                }

                function displayUploadedVideo(video) {
                    const videoElement = document.createElement('div');
                    videoElement.className = 'video-item';
                    videoElement.innerHTML = `
                        <img src="\${video.thumbnail}" alt="\${video.title}">
                        <h3>\${video.title}</h3>
                        <p>\${video.description}</p>
                        <a href="play_video.php?id=\${video.id}">پخش ویدیو</a>
                    `;
                    uploadedVideos.prepend(videoElement);
                }
            });
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
