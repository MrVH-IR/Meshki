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
    try {
        $conn = connectToDatabase();
        $sql = "INSERT INTO tblsongs (artist, songName, songPath, posterPath, description, tags, genre, upload_date)
        VALUES (:artist, :songName, :songPath, :posterPath, :description, :tags, :genre, CURDATE())";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':artist' => $artist,
            ':songName' => $songName,
            ':songPath' => $target_file_song,
            ':posterPath' => $target_file_poster,
            ':description' => $description,
            ':tags' => $tags,
            ':genre' => $genre
        ]);

        echo "<script>alert('Song uploaded successfully.'); window.location.href = 'index.php';</script>";
    } catch (PDOException $e) {
        echo "Error uploading song: " . $e->getMessage();
    }
}
?>