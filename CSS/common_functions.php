<?php
ini_set('error_log', 'C:/xampp/htdocs/Meshki/my_errors.log');
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
            #uploadVideoFormContainer {
                display: none;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background-color: #800080; /* تغییر رنگ به بنفش */
                padding: 20px;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                width: 90%;
                max-width: 500px;
            }
            #uploadVideoFormContainer h3 {
                margin-bottom: 20px;
                font-size: 20px;
                text-align: center;
            }
            #uploadVideoFormContainer input[type="text"],
            #uploadVideoFormContainer input[type="file"],
            #uploadVideoFormContainer textarea {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                border: 1px solid #ccc;
                border-radius: 5px;
                font-size: 14px;
            }
            #uploadVideoFormContainer input[name="videoName"] {
                font-size: 10px; /* کوچک تر کردن فیلد نام ویدیو */
            },
            #uploadVideoFormContainer input[name="artist"] {
                font-size: 12px; /* کوچک تر کردن فیلد نام ویدیو و هنرمند */
            }
            #uploadVideoFormContainer .custom-file-upload {
                display: block;
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                background-color: #f1f1f1;
                border: 1px dashed #ccc;
                border-radius: 5px;
                text-align: center;
                cursor: pointer;
                color: red; /* تغییر رنگ به قرمز */
            }
            #uploadVideoFormContainer .upload-form-buttons {
                display: flex;
                justify-content: space-between;
            }
            #uploadVideoFormContainer .upload-form-buttons button {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
            #uploadVideoFormContainer .upload-form-buttons button[type="submit"] {
                background-color: #4CAF50;
                color: white;
            }
            #uploadVideoFormContainer .upload-form-buttons button[type="submit"]:hover {
                background-color: #45a049;
            }
            #uploadVideoFormContainer .upload-form-buttons button[type="button"] {
                background-color: #f44336;
                color: white;
            }
            #uploadVideoFormContainer .upload-form-buttons button[type="button"]:hover {
                background-color: #e53935;
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
        <div id="uploadVideoFormContainer" style="display: none;">
            <div id="uploadVideoForm">
                <span class="close-btn" onclick="closeUploadVideoForm()">&times;</span>
                <h3>آپلود ویدیو جدید</h3>
                <form id="uploadVideoFormElement" enctype="multipart/form-data">
                    <input type="text" name="videoName" placeholder="نام ویدیو" required>
                    <input type="text" name="artist" placeholder="نام هنرمند" required>
                    <input type="file" name="thumbnail" id="thumbnailFile" accept="image/*" required style="display: none;">
                    <label for="thumbnailFile" class="custom-file-upload">انتخاب تصویر پوستر</label>
                    <span id="thumbnailFileName"></span>
                    <input type="file" name="video" id="videoFile" accept="video/*" required style="display: none;">
                    <label for="videoFile" class="custom-file-upload">انتخاب فایل ویدیو</label>
                    <span id="videoFileName"></span>
                    <div class="upload-form-buttons">
                        <button type="submit">آپلود</button>
                        <button type="button" onclick="closeUploadVideoForm()">بستن</button>
                    </div>
                </form>
            </div>
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
            function closeUploadVideoForm() {
                document.getElementById('uploadVideoFormContainer').style.display = 'none';
            }
            document.getElementById('uploadVideoBtn').addEventListener('click', function() {
                document.getElementById('uploadVideoFormContainer').style.display = 'block';
            });
            document.getElementById('thumbnailFile').addEventListener('change', function() {
                document.getElementById('thumbnailFileName').textContent = this.files[0] ? this.files[0].name : '';
            });
            document.getElementById('videoFile').addEventListener('change', function() {
                document.getElementById('videoFileName').textContent = this.files[0] ? this.files[0].name : '';
            });
            document.getElementById('uploadVideoFormElement').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                fetch('upload_video.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    alert('ویدیو با موفقیت آپلود شد.');
                    window.location.reload(); // بارگذاری مجدد صفحه برای نمایش ویدیوهای جدید
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('خطا در آپلود ویدیو.');
                });
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
// ایجاد فایل upload_video.php برای پردازش آپلود ویدیو
$upload_video_file = <<<EOT
<?php
if (\$_SERVER["REQUEST_METHOD"] == "POST") {
    // دریافت اطلاعات فرم
    \$title = \$_POST['title'];
    \$description = \$_POST['description'];

    // آپلود فایل ویدیو
    \$target_dir_video = "admin/vdata/";
    \$target_file_video = \$target_dir_video . basename(\$_FILES["video"]["name"]);
    if (move_uploaded_file(\$_FILES["video"]["tmp_name"], \$target_file_video)) {
        // آپلود فایل پوستر
        \$target_dir_thumbnail = "admin/vdata/thumbnails/";
        \$target_file_thumbnail = \$target_dir_thumbnail . basename(\$_FILES["thumbnail"]["name"]);
        if (move_uploaded_file(\$_FILES["thumbnail"]["tmp_name"], \$target_file_thumbnail)) {
            // ذخیره اطلاعات در دیتابیس
            \$servername = "localhost";
            \$username = "root";
            \$password = "";
            \$dbname = "meshki";
            \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);
            
            if (\$conn->connect_error) {
                die("Connection failed: " . \$conn->connect_error);
            }

            \$sql = "INSERT INTO tblvids (title, description, videoPath, thumbnailPath, upload_date)
            VALUES (?, ?, ?, ?, NOW())";
            

            \$stmt = \$conn->prepare(\$sql);
            \$stmt->bind_param("ssss", \$title, \$description, \$target_file_video, \$target_file_thumbnail);

            if (\$stmt->execute()) {
                \$video_id = \$stmt->insert_id;
                \$response = [
                    'success' => true,
                    'video' => [
                        'id' => \$video_id,
                        'title' => \$title,
                        'description' => \$description,
                        'videoPath' => \$target_file_video,
                        'thumbnailPath' => \$target_file_thumbnail
                    ]
                ];
                echo json_encode(\$response);
            } else {
                echo json_encode(['success' => false, 'error' => "خطا در آپلود ویدیو: " . \$stmt->error]);
            }

            \$stmt->close();
            \$conn->close();
        } else {
            echo json_encode(['success' => false, 'error' => "خطا در آپلود فایل پوستر."]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => "خطا در آپلود فایل ویدیو."]);
    }
}
?>
EOT;

file_put_contents('./upload_video.php', $upload_video_file);
// ایجاد فایل delete_video.php برای پردازش حذف ویدیو
$delete_video_file = <<<EOT
<?php
session_start();
if (isset(\$_SESSION['is_admin']) && \$_SESSION['is_admin'] === true) {
    if (isset(\$_GET['id'])) {
        \$videoId = \$_GET['id'];

        // اتصال به دیتابیس
        \$servername = "localhost";
        \$username = "root";
        \$password = "";
        \$dbname = "meshki";

        \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

        if (\$conn->connect_error) {
            die(json_encode(["success" => false, "error" => "خطا در اتصال به پایگاه داده: " . \$conn->connect_error]));
        }

        // حذف ویدیو از دیتابیس
        \$sql = "DELETE FROM tblvids WHERE id = ?";
        \$stmt = \$conn->prepare(\$sql);
        \$stmt->bind_param("i", \$videoId);

        if (\$stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "error" => "خطا در حذف ویدیو: " . \$stmt->error]);
        }

        \$stmt->close();
        \$conn->close();
    } else {
        echo json_encode(["success" => false, "error" => "شناسه ویدیو ارسال نشده است."]);
    }
} else {
    echo json_encode(["success" => false, "error" => "دسترسی غیرمجاز."]);
}
?>
EOT;

file_put_contents('./delete_video.php', $delete_video_file);