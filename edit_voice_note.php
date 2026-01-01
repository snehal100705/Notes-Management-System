<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger'>Invalid request: No voice note selected.</p>";
    exit();
}

$id = intval($_GET['id']); // Sanitize ID

// Fetch the voice note details
$query = "SELECT title, category, file_path FROM voice_notes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the voice note exists
if ($result->num_rows == 0) {
    echo "<p class='text-danger'>Error: Voice note not found.</p>";
    exit();
}

$voiceNote = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Voice Note</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <h2>✏️ Edit Voice Note</h2>
    <a href="view_voice_notes.php" class="btn btn-secondary mb-3">⬅️ Back to Voice Notes</a>

    <form id="editForm" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($voiceNote['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" name="category" id="category" value="<?php echo htmlspecialchars($voiceNote['category']); ?>" required>
        </div>

        <!-- View Current Voice Note -->
        <div class="mb-3">
            <label class="form-label">Current Voice Note:</label><br>
            <?php if (!empty($voiceNote['file_path'])): ?>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($voiceNote['file_path']); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <p><a href="<?php echo htmlspecialchars($voiceNote['file_path']); ?>" target="_blank" class="btn btn-primary">📄 View File</a></p>
            <?php else: ?>
                <p class="text-danger">No voice file available.</p>
            <?php endif; ?>
        </div>

        <!-- Upload New File -->
        <div class="mb-3">
            <label for="new_file" class="form-label">Upload New Voice Note (Optional)</label>
            <input type="file" class="form-control" name="new_file" id="new_file" accept="audio/*">
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Changes</button>
    </form>

    <p id="statusMessage" class="mt-3"></p>
</div>

<script>
    $(document).ready(function() {
        $("#editForm").submit(function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: "update_voice_note.php", // Calls the PHP update script
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(response) {
                    if (response.status === "success") {
                        $("#statusMessage").html("<span class='text-success'>" + response.message + "</span>");
                    } else {
                        $("#statusMessage").html("<span class='text-danger'>" + response.message + "</span>");
                    }
                },
                error: function() {
                    $("#statusMessage").html("<span class='text-danger'>An error occurred while updating the voice note.</span>");
                }
            });
        });
    });
</script>

</body>
</html>
