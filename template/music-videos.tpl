<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Videos | Meshki</title>
    <link rel="stylesheet" href="../CSS/music-videos.css">
    <!-- <link rel="stylesheet" href="../CSS/pagination.css"> -->
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
            background-attachment: fixed;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: auto;
            overflow: hidden;
            padding: 20px;
            padding-bottom: 70px; /* این مقدار را متناسب با ارتفاع pagination تنظیم کنید */
        }
        .video-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 20px;
        }
        .video-item {
            position: relative;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }
        .video-item:hover {
            transform: scale(1.05);
        }
        .video-thumbnail {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .video-info {
            padding: 15px;
        }
        .video-title {
            font-size: 18px;
            margin: 0 0 10px 0;
        }
        .video-description {
            font-size: 14px;
            color: #666;
        }
        .video-player {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .video-container {
            position: relative;
            width: 80%;
            max-width: 800px;
        }
        .close-btn {
            position: absolute;
            top: -40px;
            right: 0;
            color: #fff;
            font-size: 30px;
            cursor: pointer;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 20px;
            cursor: pointer;
            z-index: 10;
        } 
        /* استایل جدید برای منوی کاربری */
        .user-menu {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 1000;
        }
        .user-menu a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 10px;
        }
        
        /* اضافه کردن استایل‌های جدید برای pagination */
        .pagination-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .pagination {
            display: inline-flex;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 25px;
            padding: 5px;
        }

        .pagination a {
            color: #fff;
            background-color: #ff69b4;
            padding: 10px 15px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: 2px solid #ff69b4;
            margin: 0 5px;
            border-radius: 20px;
            font-weight: bold;
        }

        .pagination a.active {
            background-color: #fff;
            color: #ff69b4;
        }

        .pagination a:hover:not(.active) {
            background-color: #ff1493;
            border-color: #ff1493;
            transform: scale(1.1);
        }

        .pagination .prev, .pagination .next {
            background-color: #f1f1f1;
            color: #ff69b4;
            border-color: #f1f1f1;
        }

        .pagination .prev:hover, .pagination .next:hover {
            background-color: #ff69b4;
            color: #fff;
            border-color: #ff69b4;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="video-grid">
            <?php foreach ($musicVideos as $video): ?>
            <div class="video-item" data-video-id="<?php echo $video['id']; ?>">
                <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <button class="delete-btn" onclick="deleteVideo(<?php echo $video['id']; ?>)">×</button>
                <?php endif; ?>
                <img src="<?php echo $video['thumbnailPath']; ?>" alt="<?php echo $video['title']; ?>" class="video-thumbnail">
                <div class="video-info">
                    <h3 class="video-title"><?php echo $video['title']; ?></h3>
                    <p class="video-description"><?php echo $video['description']; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
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
    </div>

    <div class="video-player">
        <div class="video-container">
            <span class="close-btn">&times;</span>
            <iframe width="100%" height="450" src="" frameborder="0" allowfullscreen></iframe>
        </div>
    </div>

    <footer>
        <p>&copy; <?php echo date('Y'); ?>Meshki. All rights reserved.</p>
    </footer>
</body>
</html>
