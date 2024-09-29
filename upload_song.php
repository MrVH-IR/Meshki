<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Receiving form data
    $artist = $_POST['artist'];
    $songName = $_POST['songName'];
    $description = $_POST['description'];
    $tags = $_POST['tags'];
    $genre = $_POST['genre'];

    // Uploading song file
    $target_dir_song = "mdata/";
    $target_file_song = $target_dir_song . basename($_FILES["song"]["name"]);
    move_uploaded_file($_FILES["song"]["tmp_name"], $target_file_song);

    // Uploading poster file
    $target_dir_poster = "admin/posters/";
    $target_file_poster = $target_dir_poster . basename($_FILES["poster"]["name"]);
    move_uploaded_file($_FILES["poster"]["tmp_name"], $target_file_poster);

    // Saving data to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO tblsongs (artist, songName, songPath, posterPath, description, tags, genre, upload_date)
    VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $artist, $songName, $target_file_song, $target_file_poster, $description, $tags, $genre);

    if ($stmt->execute()) {
        echo "<script>alert('Song uploaded successfully.'); window.location.href = 'index.php';</script>";
    } else {
        echo "Error uploading song: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>