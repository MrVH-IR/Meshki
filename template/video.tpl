<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>آپلود ویدیو</title>
    <link rel="stylesheet" href="./CSS/video.css"> <!-- لینک فایل CSS برای استایل‌دهی -->
</head>
<body>
    <div class="upload-container">
        <h2>آپلود موزیک ویدیو</h2>
        <form id="uploadVideoForm" action="upload_video.php" method="POST" enctype="multipart/form-data">
            <!-- فیلدهای مورد نیاز برای آپلود ویدیو -->
            <label for="artist">نام هنرمند:</label>
            <input type="text" id="artist" name="artist" placeholder="نام هنرمند" required>

            <label for="videoName">نام موزیک ویدیو:</label>
            <input type="text" id="videoName" name="videoName" placeholder="نام موزیک ویدیو" required>

            <label for="videoFile">فایل ویدیو:</label>
            <input type="file" id="videoFile" name="videoFile" accept="video/*" required>

            <label for="posterFile">پوستر:</label>
            <input type="file" id="posterFile" name="posterFile" accept="image/*" required>

            <label for="description">توضیحات:</label>
            <textarea id="description" name="description" placeholder="توضیحات موزیک ویدیو" required></textarea>

            <label for="tags">تگ‌ها:</label>
            <input type="text" id="tags" name="tags" placeholder="تگ‌ها (با کاما جدا کنید)" required>

            <button type="submit">آپلود ویدیو</button>
        </form>
    </div>

    <script src="video.js"></script> <!-- لینک به فایل جاوااسکریپت در صورت نیاز -->
</body>
</html>
