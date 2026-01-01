<?php
session_start();
include "connection.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission (Save Edited Voice Note)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id"], $_POST["title"], $_POST["category"], $_POST["transcribed_text"])) {
    $id = intval($_POST["id"]);
    $title = trim($_POST["title"]);
    $category = trim($_POST["category"]);
    $transcribed_text = trim($_POST["transcribed_text"]);

    $query = "UPDATE voice_notes SET title = ?, category = ?, transcribed_text = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $title, $category, $transcribed_text, $id);

    if ($stmt->execute()) {
        $message = "<span class='text-success'>✅ Voice note updated successfully.</span>";
    } else {
        $message = "<span class='text-danger'>❌ Failed to update voice note.</span>";
    }

    $stmt->close();
}

// Fetch the voice note details
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p class='text-danger'>Invalid request: No voice note selected.</p>";
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT title, category, transcribed_text, file_path FROM voice_notes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

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

    <?php if (isset($message)) echo "<p>$message</p>"; ?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" name="title" id="title" value="<?php echo htmlspecialchars($voiceNote['title']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <input type="text" class="form-control" name="category" id="category" value="<?php echo htmlspecialchars($voiceNote['category']); ?>" required>
        </div>

        <div class="mb-3">
            <label for="languageSelect" class="form-label">Select Language</label>
            <select id="languageSelect" class="form-control">
                <option value="en-US">English</option>
                <option value="hi-IN">Hindi</option>
                <option value="es-ES">Spanish</option>
                <option value="fr-FR">French</option>
                <option value="de-DE">German</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="transcribed_text" class="form-label">Voice-to-Text Notes</label>
            <textarea class="form-control" name="transcribed_text" id="transcribed_text" rows="4"><?php echo htmlspecialchars($voiceNote['transcribed_text']); ?></textarea>
        </div>

        <!-- 🎤 Voice-to-Text Button -->
        <button type="button" id="startRecording" class="btn btn-success">🎤 Start Recording</button>
        <span id="recordingStatus" class="text-primary ms-2"></span>

        <!-- 🔊 Play Previous Voice Note -->
        <?php if (!empty($voiceNote['file_path'])): ?>
            <div class="mt-3">
                <label>Previous Voice Note:</label><br>
                <audio controls>
                    <source src="uploads/<?php echo $voiceNote['file_path']; ?>" type="audio/mpeg">
                    Your browser does not support the audio tag.
                </audio>
            </div>
        <?php endif; ?>

        <button type="submit" class="btn btn-primary mt-3">💾 Save Changes</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        let recognition;
        if ('webkitSpeechRecognition' in window) {
            recognition = new webkitSpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = $("#languageSelect").val();

            $("#languageSelect").change(function() {
                recognition.lang = $(this).val();
            });

            recognition.onstart = function() {
                $("#recordingStatus").text("Listening...");
                $("#startRecording").prop("disabled", true);
            };

            recognition.onresult = function(event) {
                let transcript = event.results[0][0].transcript;
                $("#transcribed_text").val(transcript);
            };

            recognition.onend = function() {
                $("#recordingStatus").text("");
                $("#startRecording").prop("disabled", false);
            };

            recognition.onerror = function(event) {
                $("#recordingStatus").text("Error: " + event.error);
                $("#startRecording").prop("disabled", false);
            };

            $("#startRecording").click(function() {
                recognition.start();
            });
        } else {
            $("#startRecording").prop("disabled", true);
            $("#recordingStatus").text("Speech Recognition Not Supported in this Browser.");
        }
    });
</script>

</body>
</html>
