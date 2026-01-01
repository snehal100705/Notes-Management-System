<?php
session_start();
include("connection.php");

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([]);
    exit();
}

$sql = "SELECT id, title, created_at FROM notes WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notes = [];

while ($row = $result->fetch_assoc()) {
    $notes[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'created_at' => $row['created_at'] // Ensure created_at is included
    ];
}

header('Content-Type: application/json');
echo json_encode($notes);
$stmt->close();
$conn->close();
?>
