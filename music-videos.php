<?php
session_start();
require_once 'CSS/common_functions.php';
include './includes/init.php';

$admin = false;
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true){
    $user_id = true;
}else{
    $admin = true;
}

// Pagination settings
$videos_per_page = 12;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $videos_per_page;

// Query to get the total number of videos
$count_sql = "SELECT COUNT(*) as total FROM tblvids";
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch(PDO::FETCH_ASSOC);
$total_videos = $count_row['total'];
$total_pages = ceil($total_videos / $videos_per_page);

// Query to get the videos with a limit on the number
$sql = "SELECT * FROM tblvids ORDER BY upload_date DESC LIMIT :offset, :videos_per_page";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':videos_per_page', $videos_per_page, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo generate_header("Music Videos");
?>

<link rel="stylesheet" href="CSS/music-videos.css">
<!-- <link rel="stylesheet" href="CSS/pagination.css"> -->

<main>
    <div class="background-banner">
        <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
    </div>
    
    <div class="video-grid">
        <?php
        $count = 0;
        if (count($result) > 0) {
            foreach($result as $row) {
                if ($count % 4 == 0) {
                    echo '<div class="video-row">';
                }
                ?>
                <div class="video-item" data-video-path="<?php echo htmlspecialchars($row['videoPath']); ?>">
                    <?php if ($admin): ?>
                        <button class="delete-btn" onclick="deleteVideo(<?php echo $row['id']; ?>)">×</button>
                    <?php endif; ?>
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
        } else {
            echo '<p>No videos to display.</p>';
        }
        ?>
    </div>
    
    <div class="pagination-container">
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>" class="prev">Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>" class="<?php echo $page == $i ? 'active' : ''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>" class="next">Next</a>
            <?php endif; ?>
        </div>
    </div>

    <div id="videoModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <video id="videoPlayer" controls>
                <source src="" type="video/mp4">
                Your browser does not support the video tag.
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

    function deleteVideo(videoId) {
        if (confirm('Are you sure you want to delete this video?')) {
            window.location.href = 'delete_video.php?id=' + videoId;
        }
    }
    </script>
</main>

<?php
echo generate_footer();
?>
