echo "<div class=\"video-grid\">";
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<div class="video-item" data-video-path="' . htmlspecialchars($row['videoPath']) . '">';
        echo '<img src="' . htmlspecialchars($row['thumbnailPath']) . '" alt="' . htmlspecialchars($row['title']) . '" class="video-thumbnail">';
        echo '<h3>' . htmlspecialchars($row['title']) . '</h3>';
        echo '<p>' . htmlspecialchars($row['description']) . '</p>';
        echo '<button class="play-btn" onclick="playVideo(\'' . htmlspecialchars($row['videoPath']) . '\')">پخش</button>';
        echo '</div>';
    }
} else {
    echo '<p>هیچ ویدیویی برای نمایش وجود ندارد.</p>';
}
echo "</div>";

echo '<script>
function playVideo(videoPath) {
    var modal = document.getElementById("videoModal");
    var videoPlayer = document.getElementById("videoPlayer");
    videoPlayer.src = videoPath;
    modal.style.display = "block";
}
</script>';
