<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");
include("sidebar.php");

if (!isset($_GET['id'])) {
    header("Location: view_notes.php");
    exit();
}

$note_id = $_GET['id'];

// Fetch existing note (REMOVED USER-SPECIFIC FILTER)
$query = "SELECT * FROM notes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $note_id);
$stmt->execute();
$result = $stmt->get_result();
$note = $result->fetch_assoc();

if (!$note) {
    header("Location: view_notes.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $file_name = $note['file_path']; // Default to existing file

    // Handle file upload (if a new file is uploaded)
    if (!empty($_FILES['file']['name'])) {
        $target_dir = "uploads/";
        
        // Delete the old file if it exists
        if (!empty($note['file_path']) && file_exists($target_dir . $note['file_path'])) {
            unlink($target_dir . $note['file_path']);
        }

        // Secure file upload
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf', 'docx'];
        $file_ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_extensions)) {
            die("<p style='color:red;'>Invalid file format! Only JPG, PNG, PDF, and DOCX are allowed.</p>");
        }

        $file_name = time() . '_' . basename($_FILES['file']['name']);
        $target_file = $target_dir . $file_name;

        if (!move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
            die("<p style='color:red;'>File upload failed!</p>");
        }
    }

    // Update note in database
    $stmt = $conn->prepare("UPDATE notes SET title = ?, content = ?, file_path = ? WHERE id = ?");
    $stmt->bind_param("sssi", $title, $content, $file_name, $note_id);

    if ($stmt->execute()) {
        echo "<script>alert('Note updated successfully!'); window.location.href='view_notes.php';</script>";
    } else {
        die("Database error: " . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Edit Note</h2>
        <form action="edit_note.php?id=<?php echo $note_id; ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($note['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="4" required><?php echo htmlspecialchars($note['content']); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Current File</label>
                <p>
                    <?php if (!empty($note['file_path'])) { ?>
                        <a href="uploads/<?php echo urlencode($note['file_path']); ?>" target="_blank">📄 View File</a>
                    <?php } else { ?>
                        No File
                    <?php } ?>
                </p>
            </div>
            <div class="mb-3">
                <label class="form-label">Upload New File (Optional)</label>
                <input type="file" name="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary w-100">Update Note</button>
            <a href="view_notes.php" class="btn btn-secondary w-100 mt-2">Cancel</a>
        </form>
    </div>
</body>
</html>
