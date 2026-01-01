<?php
session_start();
include "connection.php";

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["status" => "error", "message" => "User not authenticated."]);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"])) {
    $id = intval($_POST["id"]);

    // Debugging: Check if ID is received
    if (!$id) {
        echo json_encode(["status" => "error", "message" => "Invalid ID received."]);
        exit();
    }

    // Get file path
    $query = "SELECT file_path FROM voice_notes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($filePath);
    $stmt->fetch();
    $stmt->close();

    // Delete the file
    if (!empty($filePath) && file_exists("uploads/" . $filePath)) {
        if (!unlink("uploads/" . $filePath)) {
            echo json_encode(["status" => "error", "message" => "Failed to delete file."]);
            exit();
        }
    }

    // Delete from database
    $deleteQuery = "DELETE FROM voice_notes WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Voice note deleted successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to delete voice note."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
