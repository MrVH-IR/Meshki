<?php
if (isset($_GET['id'])) {
    $songId = $_GET['id'];

    // Connecting to database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "meshki";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die(json_encode(["success" => false, "error" => "Error connecting to database: " . $conn->connect_error]));
    }

    // Deleting song from database
    $sql = "DELETE FROM tblsongs WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $songId);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => "Error deleting song: " . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["success" => false, "error" => "Song ID not provided."]);
}
?>