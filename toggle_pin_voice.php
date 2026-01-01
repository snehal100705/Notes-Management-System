<?php
session_start();
include "connection.php";

if (!isset($_POST['id']) || !isset($_POST['pinned'])) {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
    exit();
}

$id = intval($_POST['id']);
$currentPinned = intval($_POST['pinned']);
$newPinned = $currentPinned ? 0 : 1;

// Update the pinned status in the database
$query = "UPDATE voice_notes SET pinned = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $newPinned, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "pinned" => $newPinned]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update pin status."]);
}

$stmt->close();
$conn->close();
?>
