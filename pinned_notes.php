<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");
include("navbar.php");
include("sidebar.php");

$user_id = $_SESSION['user_id'];

// Fetch pinned text notes
$query_notes = "SELECT id, title, content, file_path FROM notes WHERE user_id = ? AND is_favorite = 1";
$stmt_notes = $conn->prepare($query_notes);
$stmt_notes->bind_param("i", $user_id);
$stmt_notes->execute();
$result_notes = $stmt_notes->get_result();

// Fetch pinned voice notes
$query_voice = "SELECT id, title, category, file_path FROM voice_notes WHERE pinned = 1";
$result_voice = $conn->query($query_voice);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pinned Notes & Voice Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>📌 Pinned Notes & Voice Notes</h2>

    <!-- Pinned Text Notes -->
    <h4 class="mt-4">📄 Pinned Text Notes</h4>
    <div class="list-group">
        <?php while ($row = $result_notes->fetch_assoc()): ?>
            <div class="list-group-item">
                <h5><?php echo htmlspecialchars($row['title']); ?></h5>
                <p><?php echo substr(htmlspecialchars($row['content']), 0, 100); ?>...</p>
                
                <!-- View File Button (if file exists) -->
                <?php if (!empty($row['file_path'])): ?>
                    <a href="uploads/<?php echo urlencode($row['file_path']); ?>" target="_blank" class="btn btn-success">📄 View File</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pinned Voice Notes -->
    <h4 class="mt-4">🎙️ Pinned Voice Notes</h4>
    <div class="list-group">
        <?php while ($row = $result_voice->fetch_assoc()): ?>
            <div class="list-group-item">
                <h5><?php echo htmlspecialchars($row['title']); ?> (<?php echo htmlspecialchars($row['category']); ?>)</h5>

                <!-- Play Audio if Available -->
                <?php if (!empty($row['file_path'])): ?>
                    <audio controls>
                        <source src="<?php echo htmlspecialchars($row['file_path']); ?>" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                <?php else: ?>
                    <p class="text-danger">No audio file found.</p>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </div>

</div>
</body>
</html>
