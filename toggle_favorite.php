<?php
session_start();
include("connection.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // Get current favorite status
    $query = "SELECT is_favorite FROM notes WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $note_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $new_status = $row['is_favorite'] ? 0 : 1;
        
        // Toggle favorite status
        $update_query = "UPDATE notes SET is_favorite = ? WHERE id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("iii", $new_status, $note_id, $user_id);
        
        if ($update_stmt->execute()) {
            header("Location: view_notes.php");
            exit();
        }
    }
}

header("Location: view_notes.php");
exit();
?>
