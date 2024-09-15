<?php
session_start();
require_once 'CSS/common_functions.php';
include 'configure.php';


// دریافت ویدیوها از پایگاه داده
$sql = "SELECT * FROM tblvids ORDER BY upload_date DESC";
$result = $conn->query($sql);

echo generate_header("موزیک ویدیوها");
?>

<link rel="stylesheet" href="CSS/music-videos.css">

<main>
    <div class="background-banner">
        <img src="./admin/banners/bgbanner.jpg" alt="بنر پس زمینه" class="background-image">
    </div>
    
    <div id="uploadedVideos"></div>
    
    <div class="video-grid">
        <?php
        $count = 0;
        while($row = $result->fetch_assoc()) {
            if ($count % 4 == 0) {
                echo '<div class="video-row">';
            }
            ?>
            <div class="video-item" data-video-path="<?php echo htmlspecialchars($row['videoPath']); ?>">
                <img src="<?php echo htmlspecialchars($row['thumbnailPath']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="video-thumbnail">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
            </div>
            <?php
            $count++;
            if ($count % 4 == 0) {
                echo '</div>';
            }
        }
        if ($count % 4 != 0) {
            echo '</div>';
        }
        ?>
    </div>
    
    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <video id="videoPlayer" controls>
                <source src="" type="video/mp4">
                مرورگر شما از پخش ویدیو پشتیبانی نمی‌کند.
            </video>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('videoModal');
        var videoPlayer = document.getElementById('videoPlayer');
        var closeBtn = document.getElementsByClassName('close')[0];

        document.querySelectorAll('.video-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var videoPath = this.getAttribute('data-video-path');
                videoPlayer.src = videoPath;
                modal.style.display = 'block';
            });
        });

        closeBtn.onclick = function() {
            modal.style.display = 'none';
            videoPlayer.pause();
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = 'none';
                videoPlayer.pause();
            }
        }
    });
    </script>
</main>

<?php
echo generate_footer();
$conn->close();
?>

