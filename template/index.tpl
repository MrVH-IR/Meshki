<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download and Online Play Music and Music Video | Meshki</title>
    <meta name="description" content="The best place to download, listen to, and watch new music and music videos online. The largest collection of high-quality music and music videos.">
    <meta name="keywords" content="download music, online play, music video, new song, free download">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Download and Online Play Music and Music Video | Meshki">
    <meta property="og:description" content="The best place to download, listen to, and watch new music and music videos online. The largest collection of high-quality music and music videos.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.meshki.com">
    <link rel="canonical" href="https://www.meshki.com">
    <link rel="stylesheet" href="pagination.css">
</head>
<body>
    <header>
        <nav>
            <div class="menu-toggle">
                <div class="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <span class="menu-text">Menu</span>
            </div>
            <ul class="menu">
                <li><a href="../meshki/index.php">Home Page</a></li>
                <li><a href="../meshki/album.php">Album</a></li>
                <li><a href="../meshki/music-videos.php">Music Video</a></li>
                <li><a href="../meshki/artists.php">Artists</a></li>
                <li><a href="../meshki/playlists.php">Playlists</a></li>
                <li><a href="../meshki/aboutus.php">About Us</a></li>
                {{USER_MENU}}
            </ul>
            <?php if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                <button id="uploadBtn" class="upload-btn">Upload</button>
            <?php endif; ?>
        </nav>
        <div class="search-container">
            <form action="search.php" method="GET" class="search-form">
                <input type="text" name="q" placeholder="Search for song or artist" required>
                <button type="submit">Search</button>
            </form>
        </div>
    </header>

    <main>
        <div class="background-banner">
            <img src="./admin/banners/bgbanner.jpg" alt="Background Banner" class="background-image">
        </div>
        <div id="uploadedSongs"></div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.querySelector('.menu-toggle');
            const menu = document.querySelector('.menu');
            
            menuToggle.addEventListener('click', function() {
                menu.classList.toggle('active');
                menuToggle.classList.toggle('active');
            });

            // Adding new code to display uploaded songs
            const uploadBtn = document.getElementById('uploadBtn');
            const uploadedSongs = document.getElementById('uploadedSongs');

            if (uploadBtn) {
                uploadBtn.addEventListener('click', function() {
                    const uploadForm = document.createElement('div');
                    uploadForm.innerHTML = `
                        <div id="uploadFormContainer">
                            <h3>Upload New Song</h3>
                            <form id="uploadForm" enctype="multipart/form-data">
                                <input type="text" name="artist" placeholder="Artist Name" required>
                                <input type="text" name="songName" placeholder="Song Name" required>
                                <input type="file" name="song" id="songFile" accept="audio/*" required style="display: none;">
                                <label for="songFile" class="custom-file-upload">Select Song File</label>
                                <span id="songFileName"></span>
                                <input type="file" name="poster" id="posterFile" accept="image/*" required style="display: none;">
                                <label for="posterFile" class="custom-file-upload">Select Poster Image</label>
                                <span id="posterFileName"></span>
                                <textarea name="description" placeholder="Song Description" required></textarea>
                                <input type="text" name="tags" placeholder="Tags (separated by commas)" required>
                                <select name="genre" required>
                                    <option value="">Select Genre</option>
                                    <option value="pop">Pop</option>
                                    <option value="rock">Rock</option>
                                    <option value="hip-hop">Hip Hop</option>
                                    <option value="electronic">Electronic</option>
                                    <option value="classical">Classical</option>
                                    <option value="jazz">Jazz</option>
                                    <option value="country">Country</option>
                                    <option value="r-and-b">R&B</option>
                                    <option value="indie">Indie</option>
                                    <option value="folk">Folk</option>
                                    <option value="other">Other</option>
                                </select>
                                <div class="upload-form-buttons">
                                    <button type="submit">Upload</button>
                                    <button type="button" onclick="closeUploadForm()">Close</button>
                                </div>
                            </form>
                        </div>
                    `;
                    document.body.appendChild(uploadForm);

                    const form = document.getElementById('uploadForm');
                    const songFile = document.getElementById('songFile');
                    const posterFile = document.getElementById('posterFile');
                    const songFileName = document.getElementById('songFileName');
                    const posterFileName = document.getElementById('posterFileName');

                    songFile.addEventListener('change', function() {
                        songFileName.textContent = this.files[0] ? this.files[0].name : '';
                    });

                    posterFile.addEventListener('change', function() {
                        posterFileName.textContent = this.files[0] ? this.files[0].name : '';
                    });

                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const formData = new FormData(form);
                        
                        // Sending data to the server (this part should be coordinated with your server-side code)
                        fetch('upload_song.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if(data.success) {
                                // Display uploaded song
                                const songElement = createSongElement(data.song);
                                uploadedSongs.insertBefore(songElement, uploadedSongs.firstChild);
                                closeUploadForm();
                            } else {
                                alert('Error uploading song');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error connecting to server');
                        });
                    });
                });
            }

            function createSongElement(song) {
                const songElement = document.createElement('div');
                songElement.className = 'music-container';
                songElement.innerHTML = `
                    <button class="delete-btn">X</button>
                    <h2 class="music-title">${song.artist} - ${song.songName}</h2>
                    <img src="${song.posterPath}" alt="${song.artist} - ${song.songName}" class="music-poster" onerror="this.onerror=null; this.src='./admin/posters/Check-For-Error-Message-min.gif'; this.alt='Error loading image';">
                    <p class="music-description">${song.description}</p>
                    <div class="music-tags">
                        ${song.tags.split(',').map(tag => `<span>#${tag.trim()}</span>`).join('')}
                    </div>
                    <p class="music-genre">Genre: ${song.genre}</p>
                    <button class="show-more-btn">Show More</button>
                    <div class="music-player" style="display: none;">
                        <audio src="${song.songPath}"></audio>
                        <div class="controls">
                            <span class="time">00:00 / 00:00</span>
                            <div class="progress-bar">
                                <div class="progress"></div>
                            </div>
                            <button class="play-pause-btn">
                                Play
                            </button>
                        </div>
                    </div>
                `;

                // Adding event listener for "Show More" button in new song
                const newShowMoreBtn = songElement.querySelector('.show-more-btn');
                const newMusicPlayer = songElement.querySelector('.music-player');
                newShowMoreBtn.addEventListener('click', function() {
                    if (newMusicPlayer.style.display === 'none') {
                        newMusicPlayer.style.display = 'block';
                    } else {
                        newMusicPlayer.style.display = 'none';
                    }
                });

                return songElement;
            }

            function closeUploadForm() {
                const uploadFormContainer = document.getElementById('uploadFormContainer');
                if (uploadFormContainer) {
                    uploadFormContainer.remove();
                }
            }

            var logoutLink = document.querySelector('a[href="../meshki/logout.php"]');
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to log out?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>

    <style>
        body {
            padding-top: 60px;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        nav {
            background-color: #000;
            padding: 8px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .menu {
            display: none;
            list-style-type: none;
            padding: 0;
            margin: 0;
            position: absolute;
            top: 100%;
            right: 100;
            background-color: #000;
            width: 200px;
        }
        .menu.active {
            display: block;
        }
        .menu li {
            margin-bottom: 8px;
        }
        .menu a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
            font-size: 14px;
            display: block;
            padding: 8px;
        }
        .menu a:hover {
            color: #ff69b4;
        }
        .menu-toggle {
            cursor: pointer;
            padding: 8px;
            display: inline-block;
            position: relative;
        }
        .hamburger {
            display: inline-block;
            width: 25px;
            height: 18px;
            position: relative;
        }
        .hamburger span {
            display: block;
            position: absolute;
            height: 2px;
            width: 100%;
            background: #fff;
            border-radius: 2px;
            opacity: 1;
            left: 0;
            transform: rotate(0deg);
            transition: .25s ease-in-out;
        }
        .hamburger span:nth-child(1) {
            top: 0px;
        }
        .hamburger span:nth-child(2) {
            top: 7px;
        }
        .hamburger span:nth-child(3) {
            top: 14px;
        }
        .menu-text {
            display: none;
            color: #fff;
            margin-left: 8px;
            font-size: 14px;
        }
        .menu-toggle:hover .hamburger {
            display: none;
        }
        .menu-toggle:hover .menu-text {
            display: inline-block;
        }
        .background-banner {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .background-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .music-container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: transparent;
            border: none;
            font-size: 20px;
            color: #ff69b4;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .delete-btn:hover {
            color: #ff1493;
            transform: scale(1.2);
        }
        .music-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .music-poster {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }
        .music-description {
            margin-bottom: 10px;
        }
        .music-tags {
            margin-bottom: 15px;
        }
        .music-tags span {
            display: inline-block;
            margin-right: 5px;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .music-genre {
            margin-bottom: 15px;
            font-weight: bold;
        }
        .show-more-btn {
            background-color: #ff69b4;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .music-player {
            background-color: #f8f8f8;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row-reverse;
        }
        .play-pause-btn {
            background-color: #ff69b4;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .play-pause-btn:hover {
            background-color: #ff1493;
        }
        .progress-bar {
            flex-grow: 1;
            height: 5px;
            background-color: #ddd;
            margin: 0 15px;
            border-radius: 5px;
            overflow: hidden;
            cursor: pointer;
            direction: ltr;
        }
        .progress {
            height: 100%;
            background-color: #ff69b4;
            width: 0;
            float: right;
        }
        .time {
            font-size: 14px;
            color: #666;
        }
        .upload-btn {
            background-color: #ff69b4;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .upload-btn:hover {
            background-color: #ff1493;
        }
        #uploadFormContainer {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #740665;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            z-index: 1000;
            max-width: 400px;
            width: 90%;
            box-sizing: border-box;
        }
        #uploadFormContainer h3 {
            color: #ff69b4;
            margin-bottom: 20px;
            text-align: center;
        }
        #uploadForm input[type="text"],
        #uploadForm input[type="file"],
        #uploadForm textarea,
        #uploadForm select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        #uploadForm input[type="file"] {
            padding: 10px 0;
        }
        #uploadForm input[type="file"] + label {
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-top: 5px;
            transition: background-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        #uploadForm input[type="file"] + label:hover {
            background-color: #ff69b4;
        }
        #uploadForm button {
            background-color: #ff69b4;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            margin-right: 10px;
            width: calc(50% - 5px);
            box-sizing: border-box;
        }
        #uploadForm button:hover {
            background-color: #ff1493;
        }
        #uploadForm button[type="button"] {
            background-color: #f0f0f0;
            color: #333;
        }
        #uploadForm button[type="button"]:hover {
            background-color: #e0e0e0;
        }
        .upload-form-buttons {
            text-align: center;
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .success-message {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            text-align: center;
        }
        .custom-file-upload {
            background-color: #f0f0f0;
            color: #333;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            display: inline-block;
            margin-top: 5px;
            transition: background-color 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }
        .custom-file-upload:hover {
            background-color: #ff69b4;
            color: white;
        }
        #songFileName, #posterFileName {
            display: block;
            margin-top: 5px;
            font-size: 12px;
            color: #f0f0f0;
        }

        /* New style for search form */
        .search-container {
            display: flex;
            justify-content: center;
            margin: 20px 0;
        }

        .search-form {
            display: flex;
            justify-content: center;
            width: 100%;
            max-width: 600px;
        }

        .search-form input[type="text"] {
            width: 70%;
            padding: 12px 20px;
            font-size: 16px;
            border: 2px solid #ff69b4;
            border-radius: 25px 0 0 25px;
            outline: none;
            transition: all 0.3s ease;
        }

        .search-form input[type="text"]:focus {
            border-color: #ff1493;
            box-shadow: 0 0 8px rgba(255, 105, 180, 0.6);
        }

        .search-form button[type="submit"] {
            width: 30%;
            padding: 12px 20px;
            font-size: 16px;
            background-color: #ff69b4;
            color: white;
            border: none;
            border-radius: 0 25px 25px 0;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .search-form button[type="submit"]:hover {
            background-color: #ff1493;
        }

        /* For mobile devices */
        @media (max-width: 768px) {
            .search-form input[type="text"] {
                width: 60%;
            }
            
            .search-form button[type="submit"] {
                width: 40%;
            }
        }
    </style>
</body>
<footer style="text-align: center; position: fixed; bottom: 0; left: 0; width: 100%; padding: 10px 0;">
    <p style="color: pink; margin: 0;">&copy; <?php echo date('Y'); ?> Meshki. All rights reserved.</p>
</footer>
</html>
