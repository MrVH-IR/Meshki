<?php
session_start();
include 'template/artists.tpl';

// تنظیمات pagination
$items_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

try {
    $db = new PDO('mysql:host=localhost;dbname=meshki', 'root', '');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // دریافت تعداد کل هنرمندان
    $count_stmt = $db->query('SELECT COUNT(DISTINCT artist) as total FROM tblsongs');
    $total_artists = $count_stmt->fetchColumn();
    
    // دریافت هنرمندان با pagination
    $stmt = $db->prepare('SELECT DISTINCT artist, upload_date FROM tblsongs ORDER BY upload_date DESC LIMIT :offset, :items_per_page');
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':items_per_page', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $artists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // نمایش هنرمندان با عکس
    foreach ($artists as $artist) {
        $artist_name = htmlspecialchars($artist['artist']);
        $image_path = 'admin/artists/' . $artist_name . '.jpg'; // فرض می‌کنیم که تصاویر با فرمت jpg هستند
        
        if (file_exists($image_path)) {
            echo '<img src="' . $image_path . '" alt="' . $artist_name . '" style="width: 100px; height: 100px;">';
        } else {
            echo '<img src="admin/artists/default.jpg" alt="تصویر پیش‌فرض" style="width: 100px; height: 100px;">'; // تصویر پیش‌فرض در صورت عدم وجود تصویر هنرمند
        }
        
        echo $artist_name . ' - تاریخ آپلود: ' . $artist['upload_date'] . '<br>';
    }
    
    // ایجاد لینک‌های pagination
    $total_pages = ceil($total_artists / $items_per_page);
    echo '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        echo '<a href="?page=' . $i . '">' . $i . '</a> ';
    }
    echo '</div>';
    
} catch (PDOException $e) {
    echo 'خطا در اتصال: ' . $e->getMessage();
}

?>
