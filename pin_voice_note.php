<?php
session_start();
include "connection.php";

header("Content-Type: application/json");

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit();
}

// Check if the ID is provided
if (!isset($_POST["id"]) || empty($_POST["id"])) {
    echo json_encode(["status" => "error", "message" => "No ID received"]);
    exit();
}

$id = intval($_POST["id"]);

// Fetch the current pinned status of the voice note
$query = "SELECT pinned FROM voice_notes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($pinned);
$stmt->fetch();
$stmt->close();

if ($pinned === null) {
    echo json_encode(["status" => "error", "message" => "Voice note not found"]);
    exit();
}

// Toggle the pinned status
$newPinStatus = $pinned ? 0 : 1;

// Update the pinned status in the database
$updateQuery = "UPDATE voice_notes SET pinned = ? WHERE id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ii", $newPinStatus, $id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Pin status updated", "new_status" => $newPinStatus]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update pin status"]);
}

$stmt->close();
$conn->close();
?>
