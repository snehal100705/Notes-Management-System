<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo json_encode(["status" => "error", "message" => "Unauthorized access."]);
    exit();
}

$note_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Check if the note exists
$checkQuery = "SELECT is_favorite FROM notes WHERE id = ?";
$stmt = $conn->prepare($checkQuery);
$stmt->bind_param("i", $note_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Toggle is_favorite
    $newStatus = $row['is_favorite'] ? 0 : 1;

    // Update the favorite status
    $updateQuery = "UPDATE notes SET is_favorite = ? WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ii", $newStatus, $note_id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "is_favorite" => $newStatus]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update note."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Note not found."]);
}

exit();
