console.log("Album.js بارگذاری شد");

document.addEventListener('DOMContentLoaded', function() {
    const uploadAlbumBtn = document.getElementById('uploadAlbumBtn');
    const uploadAlbumFormContainer = document.getElementById('uploadAlbumFormContainer');
    const closeFormBtn = document.getElementById('closeFormBtn');
    const albumFolder = document.getElementById('albumFolder');

    if (uploadAlbumBtn) {
        uploadAlbumBtn.addEventListener('click', function() {
            if (uploadAlbumFormContainer) {
                uploadAlbumFormContainer.style.display = 'block';
                console.log("دکمه آپلود کلیک شد");
                console.log(uploadAlbumFormContainer);
            } else {
                console.error("المان فرم آپلود آلبوم پیدا نشد");
            }
        });
    } else {
        console.error("دکمه آپلود آلبوم پیدا نشد");
    }

    if (closeFormBtn) {
        closeFormBtn.addEventListener('click', function() {
            if (uploadAlbumFormContainer) {
                uploadAlbumFormContainer.style.display = 'none';
            }
        });
    }

    if (albumFolder) {
        albumFolder.addEventListener('change', function(event) {
            const folder = event.target.files;
            if (folder.length > 0) {
                console.log('پوشه انتخاب شد:', folder);
            } else {
                console.log('هیچ پوشه‌ای انتخاب نشده است');
            }
        });
    }
});
