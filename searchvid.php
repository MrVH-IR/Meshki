<?php
include 'CSS/common_functions.php';

global $conn;
$conn = connectToDatabase();

$admin = false;
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true){
    $user_id = true;
}else{
    $admin = true;
}

$search_query = isset($_GET['q']) ? $_GET['q'] : '';

echo generate_header("جستجوی ویدیو", $search_query);

try {
    // تنظیمات صفحه‌بندی
    $videos_per_page = 5;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $videos_per_page;
    
    // کوئری جستجو
    $sql = "SELECT * FROM tblvids WHERE 
            title LIKE :search OR 
            description LIKE :search 
            ORDER BY upload_date DESC LIMIT :offset, :limit";

    $stmt = $conn->prepare($sql);
    $search_param = "%$search_query%";
    $stmt->bindParam(':search', $search_param, PDO::PARAM_STR);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $videos_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // محاسبه تعداد کل نتایج
    $sql_count = "SELECT COUNT(*) as total FROM tblvids WHERE 
                  title LIKE :search OR 
                  description LIKE :search";
    $stmt_count = $conn->prepare($sql_count);
    $stmt_count->bindParam(':search', $search_param, PDO::PARAM_STR);
    $stmt_count->execute();
    $row_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $total_pages = ceil($row_count['total'] / $videos_per_page);

    // تولید HTML
    echo "<div class='container'>";
    echo "<h1>نتایج جستجو برای \"" . htmlspecialchars($search_query) . "\"</h1>";
    echo "<div class='video-grid'>";

    if (count($result) > 0) {
        foreach($result as $row) {
            echo '<div class="video-item">';
            echo '<div class="video-thumbnail">';
            echo '<img src="' . htmlspecialchars($row['thumbnailPath']) . '" alt="' . htmlspecialchars($row['title']) . '">';
            echo '<div class="play-button" data-video-path="' . htmlspecialchars($row['videoPath']) . '"></div>';
            echo '</div>';
            echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>هیچ ویدیویی برای نمایش وجود ندارد.</p>';
    }
    echo "</div>";

    // اضافه کردن کنترل‌های صفحه‌بندی
    if ($total_pages > 1) {
        $base_url = "?q=" . urlencode($search_query);
        echo generate_pagination($total_pages, $page, $base_url);
    }

    echo "</div>";

} catch (PDOException $e) {
    echo "خطا در اتصال به پایگاه داده: " . $e->getMessage();
}

echo generate_footer();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const playButtons = document.querySelectorAll('.play-button');
    playButtons.forEach(button => {
        button.addEventListener('click', function() {
            const videoPath = this.getAttribute('data-video-path');
            const videoPlayer = `<video controls autoplay>
                                    <source src="${videoPath}" type="video/mp4">
                                    مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
                                 </video>`;
            const modal = document.createElement('div');
            modal.className = 'video-modal';
            modal.innerHTML = videoPlayer;
            document.body.appendChild(modal);
            
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        });
    });
});
</script>

<style>
    body {
        font-family: 'Vazir', Tahoma, Arial, sans-serif;
        line-height: 1.6;
        color: #333;
        margin: 0;
        padding: 0;
        background-image: url('admin/banners/bgbanner.jpg');
        background-repeat: no-repeat;
        background-size: cover;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .video-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }
    .video-item {
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .video-thumbnail {
        position: relative;
        width: 100%;
        padding-top: 56.25%; /* 16:9 Aspect Ratio */
    }
    .video-thumbnail img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60px;
        height: 60px;
        background-color: rgba(0,0,0,0.7);
        border-radius: 50%;
        cursor: pointer;
    }
    .play-button::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 55%;
        transform: translate(-50%, -50%);
        border-left: 20px solid #fff;
        border-top: 15px solid transparent;
        border-bottom: 15px solid transparent;
    }
    .video-item h3 {
        padding: 10px;
        margin: 0;
        font-size: 18px;
    }
    .video-item p {
        padding: 0 10px 10px;
        margin: 0;
        font-size: 14px;
        color: #666;
    }
    .video-modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }
    .video-modal video {
        max-width: 90%;
        max-height: 90%;
    }
</style>
