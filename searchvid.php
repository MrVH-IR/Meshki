<?php
require 'configure.php';
// Start Generation Here
$search_query = isset($_GET['q']) ? $_GET['q'] : '';

// تنظیمات صفحه‌بندی
$songs_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $songs_per_page;

// کوئری جستجو
$sql = "SELECT * FROM tblvids WHERE 
        title LIKE ? OR 
        description LIKE ? 
        ORDER BY upload_date DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$search_param = "%$search_query%";
$stmt->bind_param("ssii", $search_param, $search_param, $offset, $songs_per_page);
$stmt->execute();
$result = $stmt->get_result();

// محاسبه تعداد کل نتایج
$sql_count = "SELECT COUNT(*) as total FROM tblvids WHERE 
              title LIKE ? OR 
              description LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("ss", $search_param, $search_param);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $songs_per_page);

// تولید HTML
echo "<h1>نتایج جستجو برای \"$search_query\"</h1>";
echo "<div class=\"video-grid\">";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="video-item" data-video-path="' . htmlspecialchars($row['videoPath']) . '">';
        echo '<img src="' . htmlspecialchars($row['thumbnailPath']) . '" alt="' . htmlspecialchars($row['title']) . '" class="video-thumbnail">';
        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
        echo '</div>';
    }
} else {
    echo '<p>هیچ ویدیویی برای نمایش وجود ندارد.</p>';
}
echo "</div>";
