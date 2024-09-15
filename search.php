<?php
session_start();
include 'configure.php';
include 'CSS/common_functions.php';

$search_query = isset($_GET['q']) ? $_GET['q'] : '';

// تنظیمات صفحه‌بندی
$songs_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $songs_per_page;

// کوئری جستجو
$sql = "SELECT * FROM tblsongs WHERE 
        artist LIKE ? OR 
        songName LIKE ? OR 
        description LIKE ? OR 
        tags LIKE ? 
        ORDER BY id DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
$search_param = "%$search_query%";
$stmt->bind_param("ssssii", $search_param, $search_param, $search_param, $search_param, $offset, $songs_per_page);
$stmt->execute();
$result = $stmt->get_result();

// محاسبه تعداد کل نتایج
$sql_count = "SELECT COUNT(*) as total FROM tblsongs WHERE 
              artist LIKE ? OR 
              songName LIKE ? OR 
              description LIKE ? OR 
              tags LIKE ?";
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param("ssss", $search_param, $search_param, $search_param, $search_param);
$stmt_count->execute();
$result_count = $stmt_count->get_result();
$row_count = $result_count->fetch_assoc();
$total_pages = ceil($row_count['total'] / $songs_per_page);

// تولید HTML
echo generate_header("نتایج جستجو برای \"$search_query\"", $search_query);

echo "<main>";
echo "<h1>نتایج جستجو برای \"$search_query\"</h1>";
echo "<div class=\"music-containers\">";

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo generate_music_container($row);
    }
} else {
    echo "<p>هیچ نتیجه‌ای یافت نشد.</p>";
}

echo "</div>";

$base_url = "?q=" . urlencode($search_query);
echo generate_pagination($total_pages, $page, $base_url);

echo "</main>";

echo generate_footer();

$stmt->close();
$stmt_count->close();
$conn->close();
