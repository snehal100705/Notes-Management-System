<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");
include("navbar.php");
include("sidebar.php");

if (!isset($_GET['id'])) {
    echo "Invalid Note ID.";
    exit();
}

$note_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT title, content FROM notes WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $note_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    echo "Note not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .note-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            background:rgb(186, 213, 239);
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        p {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="note-container p-4">
        <h2><?php echo htmlspecialchars($note['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($note['content'])); ?></p>
        <a href="view_notes.php" class="btn btn-secondary mt-3">Back to Notes</a>
    </div>
</div>

</body>
</html>
