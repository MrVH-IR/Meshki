<?php
if (isset($_GET['id'])) {
    $songId = $_GET['id'];

    try {
        // Connecting to database
        $conn = connectToDatabase();

        // Deleting song from database
        $sql = "DELETE FROM tblsongs WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $songId]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "error" => "Error deleting song: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Song ID not provided."]);
}
?>