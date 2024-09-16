<?php
session_start();
include 'configure.php';
include 'CSS/common_functions.php';

// خواندن محتوای قالب
$template = file_get_contents('template/index.tpl');

// بررسی وضعیت ورود کاربر
if (isset($_SESSION['user_id'])) {
    // کاربر وارد شده است
    $user_menu = '
    <li><a href="../meshki/profile.php">پروفایل من</a></li>
    <li><a href="../meshki/logout.php" onclick="event.preventDefault(); document.getElementById(\'logout-form\').submit();">خروج</a></li>
    <form id="logout-form" action="../meshki/logout.php" method="POST" style="display: none;"></form>
    ';
    
    // جایگزینی بخش مربوط به منوی کاربر
    $template = preg_replace(
        '/\<\?php if\(isset\(\$_SESSION\[\'user_id\'\]\)\): \?\>.*?\<\?php else: \?\>.*?\<\?php endif; \?\>/s',
        $user_menu,
        $template
    );
} else {
    // کاربر وارد نشده است
    $guest_menu = '
    <li><a href="../meshki/login.php">ورود</a></li>
    <li><a href="../meshki/register.php">ثبت نام</a></li>
    ';
    
    // جایگزینی بخش مربوط به منوی مهمان
    $template = preg_replace(
        '/\<\?php if\(isset\(\$_SESSION\[\'user_id\'\]\)\): \?\>.*?\<\?php else: \?\>.*?\<\?php endif; \?\>/s',
        $guest_menu,
        $template
    );
}

// اضافه کردن کد جاوااسکریپت برای نمایش فرم آپلود
$upload_form_script = '
<script>
document.addEventListener("DOMContentLoaded", function() {
    var uploadBtn = document.getElementById("uploadBtn");
    if (uploadBtn) {
        uploadBtn.addEventListener("click", function() {
            var uploadForm = document.createElement("div");
            uploadForm.innerHTML = `
                <div id="uploadFormContainer" class="upload-form-container">
                    <h3>آپلود آهنگ جدید</h3>
                    <form id="uploadForm" enctype="multipart/form-data" method="POST" action="upload_song.php">
                        <input type="text" name="artist" placeholder="نام هنرمند" required>
                        <input type="text" name="songName" placeholder="نام آهنگ" required>
                        <input type="file" name="song" accept="audio/*" required><label for="song">آپلود آهنگ</label>
                        <input type="file" name="poster" accept="image/*" required><label for="poster">آپلود پوستر</label>
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
        });
    }
});

function closeUploadForm() {
    var uploadFormContainer = document.getElementById("uploadFormContainer");
    if (uploadFormContainer) {
        uploadFormContainer.remove();
    }
}

function deleteSong(songId) {
    if (confirm("آیا مطمئن هستید که می‌خواهید این آهنگ را حذف کنید؟")) {
        fetch("delete_song.php?id=" + songId, {
            method: "GET"
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert("آهنگ با موفقیت حذف شد.");
                location.reload();
            } else {
                alert("خطا در حذف آهنگ: " + data.error);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("خطا در ارتباط با سرور");
        });
    }
}
</script>
';

// اضافه کردن اسکریپت به انتهای تمپلیت
$template .= $upload_form_script;

// جایگزینی متغیرهای جلسه در قالب
$template = str_replace('<?php if(isset($_SESSION[\'is_admin\']) && $_SESSION[\'is_admin\'] === true): ?>', 
    (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) ? '' : '<!--', $template);
$template = str_replace('<?php endif; ?>', 
    (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true) ? '' : '-->', $template);

// نمایش قالب
echo $template;
// نمایش پیام موفقیت آپلود
if (isset($_GET['upload_success']) && $_GET['upload_success'] == 'true') {
    echo '<div class="success-message">آهنگ با موفقیت آپلود شد.</div>';
}
// نمایش آهنگ آپلود شده
// نمایش آهنگ‌های آپلود شده
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "meshki";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("خطا در اتصال به پایگاه داده: " . $conn->connect_error);
}

// تنظیمات صفحه‌بندی
$songs_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $songs_per_page;

// کوئری برای دریافت آهنگ‌ها
$sql = "SELECT * FROM tblsongs ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $songs_per_page);
$stmt->execute();
$result = $stmt->get_result();

// محاسبه تعداد کل صفحات
$sql_count = "SELECT COUNT(*) as total FROM tblsongs";
$result_count = $conn->query($sql_count);
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $songs_per_page);

if ($result->num_rows > 0) {
    echo '<div class="music-containers">';
    while($row = $result->fetch_assoc()) {
        echo '<div class="music-container">';
        echo '<h2 class="music-title">' . $row["songName"] . ' - ' . $row["artist"] . '</h2>';
        echo '<img src="' . $row["posterPath"] . '" alt="' . $row["songName"] . ' پوستر" class="music-poster">';
        echo '<p class="music-description">' . $row["description"] . '</p>';
        echo '<div class="music-tags">';
        $tags = explode(',', $row["tags"]);
        foreach ($tags as $tag) {
            echo '<span>' . trim($tag) . '</span>';
        }
        echo '</div>';
        echo '<button class="show-more-btn" onclick="togglePlayer(this)">نمایش بیشتر</button>';
        echo '<div class="music-player" style="display: none;">';
        echo '<audio controls>';
        echo '<source src="' . $row["songPath"] . '" type="audio/mpeg">';
        echo 'مرورگر شما از پخش صوتی پشتیبانی نمی‌کند.';
        echo '</audio>';
        echo '</div>';
        echo '<button class="delete-btn" onclick="deleteSong(' . $row["id"] . ')">X</button>';
        echo '</div>';
    }
    
    // اضافه کردن کنترل‌های صفحه‌بندی
    echo '<div class="pagination-container">';
    echo '<div class="pagination">';
    if ($page > 1) {
        echo '<a href="?page='.($page-1).'" class="prev">قبلی</a>';
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?page='.$i.'" '.($page == $i ? 'class="active"' : '').'>'.$i.'</a>';
    }
    if ($page < $total_pages) {
        echo '<a href="?page='.($page+1).'" class="next">بعدی</a>';
    }
    echo '</div>';
    echo '</div>';
    
    echo '<script>
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
    </script>';
} else {
    echo "هیچ آهنگی یافت نشد.";
}

$conn->close();

$content = "محتوای اصلی صفحه";

// ایجاد فایل upload_song.php برای پردازش آپلود آهنگ
$upload_song_file = <<<EOT
<?php
if (\$_SERVER["REQUEST_METHOD"] == "POST") {
    // دریافت اطلاعات فرم
    \$artist = \$_POST['artist'];
    \$songName = \$_POST['songName'];
    \$description = \$_POST['description'];
    \$tags = \$_POST['tags'];

    // آپلود فایل آهنگ
    \$target_dir_song = "mdata/";
    \$target_file_song = \$target_dir_song . basename(\$_FILES["song"]["name"]);
    move_uploaded_file(\$_FILES["song"]["tmp_name"], \$target_file_song);

    // آپلود فایل پوستر
    \$target_dir_poster = "admin/posters/";
    \$target_file_poster = \$target_dir_poster . basename(\$_FILES["poster"]["name"]);
    move_uploaded_file(\$_FILES["poster"]["tmp_name"], \$target_file_poster);

    // ذخیره اطلاعات در دیتابیس
    \$servername = "localhost";
    \$username = "root";
    \$password = "";
    \$dbname = "meshki";

    \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

    if (\$conn->connect_error) {
        die("Connection failed: " . \$conn->connect_error);
    }

    \$sql = "INSERT INTO tblsongs (artist, songName, songPath, posterPath, description, tags)
    VALUES (?, ?, ?, ?, ?, ?)";

    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param("ssssss", \$artist, \$songName, \$target_file_song, \$target_file_poster, \$description, \$tags);

    if (\$stmt->execute()) {
        echo "<script>alert('آهنگ با موفقیت آپلود شد.'); window.location.href = 'index.php';</script>";
    } else {
        echo "خطا در آپلود آهنگ: " . \$stmt->error;
    }

    \$stmt->close();
    \$conn->close();
}
?>
EOT;

file_put_contents('upload_song.php', $upload_song_file);

// ایجاد فایل delete_song.php برای پردازش حذف آهنگ
$delete_song_file = <<<EOT
<?php
if (isset(\$_GET['id'])) {
    \$songId = \$_GET['id'];

    // اتصال به دیتابیس
    \$servername = "localhost";
    \$username = "root";
    \$password = "";
    \$dbname = "meshki";

    \$conn = new mysqli(\$servername, \$username, \$password, \$dbname);

    if (\$conn->connect_error) {
        die(json_encode(["success" => false, "error" => "خطا در اتصال به پایگاه داده: " . \$conn->connect_error]));
    }

    // حذف آهنگ از دیتابیس
    \$sql = "DELETE FROM tblsongs WHERE id = ?";
    \$stmt = \$conn->prepare(\$sql);
    \$stmt->bind_param("i", \$songId);

    if (\$stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "خطا در حذف آهنگ: " . \$stmt->error]);
    }

    \$stmt->close();
    \$conn->close();
} else {
    echo json_encode(["success" => false, "error" => "شناسه آهنگ ارسال نشده است."]);
}
?>
EOT;

file_put_contents('delete_song.php', $delete_song_file);
