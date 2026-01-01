<?php
session_start();
include("connection.php"); // Include database connection

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["content"])) {
    if (!isset($_SESSION["user_id"])) {
        echo json_encode(["status" => "error", "message" => "User not authenticated."]);
        exit();
    }

    $user_id = $_SESSION["user_id"];
    $title = trim($_POST["title"] ?? "Untitled Note");
    $content = trim($_POST["content"]);
    $category = trim($_POST["category"] ?? 'General');

    if (empty($content)) {
        echo json_encode(["status" => "error", "message" => "Note content cannot be empty."]);
        exit();
    }

    // Generate a unique filename
    $file_name = "voice_note_" . time() . "_" . rand(1000, 9999) . ".txt";
    $file_path = "uploads/" . $file_name;

    // Ensure the uploads directory exists
    if (!is_dir("uploads")) {
        mkdir("uploads", 0777, true);
    }

    // Save the voice-to-text content inside the file
    if (file_put_contents($file_path, $content) !== false) {
        // Insert into voice_notes table
        $query = "INSERT INTO voice_notes (user_id, title, file_path, category, created_at) 
                  VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("isss", $user_id, $title, $file_path, $category);
            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Voice note saved successfully!", "redirect" => "view_voice_notes.php"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Error saving voice note: " . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Database error: Failed to prepare statement."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save file."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
