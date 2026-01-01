<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");

if (isset($_GET['id'])) {
    $note_id = $_GET['id'];

    // Get file path before deleting the note
    $query = "SELECT file_path FROM notes WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $note_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $note = $result->fetch_assoc();
    
    if ($note) {
        // Delete file from server if it exists
        if (!empty($note['file_path']) && file_exists("uploads/" . $note['file_path'])) {
            unlink("uploads/" . $note['file_path']);
        }

        // Delete note from database
        $delete_query = "DELETE FROM notes WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $note_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Note deleted successfully!'); window.location.href='view_notes.php';</script>";
        } else {
            echo "<script>alert('Error deleting note.'); window.location.href='view_notes.php';</script>";
        }
    } else {
        echo "<script>alert('Note not found.'); window.location.href='view_notes.php';</script>";
    }
}

// Close the connection
$stmt->close();
$conn->close();
?>
