<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css">
</head>
<body>
    <h1>User Profile</h1>
    <div style="text-align: center;">
        <div style="border: 0px solid #ff66b2; border-radius: 10px; padding: 20px; background-color: transparent; animation: rotate-border 2s linear infinite;">
            <div style="width: 200px; position: absolute; left: 10px; bottom: 10px; background-color: rgba(255, 255, 255, 0.8); border-radius: 10px; padding: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);">
                <h2 style="text-align: center;">Profile Picture</h2>
                <form action="upload_profile_picture.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_picture" accept="image/*" required style=" color: transparent; padding: 10px; border-radius: 5px;">
                    <button type="submit">Upload</button>
                </form>
                <form action="del-picprofile.php" id="delimage" method="POST">
                    <button type="submit">Delete</button>
                </form>
                <form action="change_profile_picture.php" method="POST" enctype="multipart/form-data">
                    <input type="file" name="change_profile_picture" accept="image/*" required style=" color: transparent; border: none; padding: 10px; border-radius: 5px;">
                    <button type="submit">Change</button>
                </form>
            </div>
        </div>
    <div style="margin-top: 20px;">
        <h3>Registration Date: <span id="registrationDate" >{registration_date}</span></h3>
    </div>
    <div style="margin-top: 20px;">
        <h3>Select your favorite music genre</h3>
        <div id="recommendedMusic">
            <select id="genreSelect">
                <option value="">Select genre</option>
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
            <div id="music_player"></div>
        </div>
    </div>
    <script src="profile.js"></script>
</body>
</html>
