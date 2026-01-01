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

// Ensure the database connection is active
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch categories
$category_query = "SELECT id, category_name FROM categories";
$category_result = $conn->query($category_query);

if (!$category_result) {
    die("Error fetching categories: " . $conn->error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $category = intval($_POST['category'] ?? 0);
    $reminder_date = !empty($_POST['reminder_date']) ? $_POST['reminder_date'] : NULL;

    if (empty($title) || empty($content) || empty($category)) {
        echo "<script>alert('All fields except Reminder Date are required!');</script>";
    } else {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $file_path = NULL;

        if (!empty($_FILES['file']['name'])) {
            $file_name = time() . '_' . basename($_FILES['file']['name']);
            $target_file = $upload_dir . $file_name;
            if (move_uploaded_file($_FILES['file']['tmp_name'], $target_file)) {
                $file_path = $file_name;
            } else {
                echo "<script>alert('File upload failed!');</script>";
            }
        }

        $stmt = $conn->prepare("INSERT INTO notes (user_id, title, content, category_id, file_path, reminder_date) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $user_id, $title, $content, $category, $file_path, $reminder_date);

        if ($stmt->execute()) {
            echo "<script>alert('Note added successfully!'); window.location.href='view_notes.php';</script>";
        } else {
            echo "<script>alert('Error adding note: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Note</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; font-family: Arial, sans-serif; }
        .container { max-width: 700px; background: white; padding: 20px; border-radius: 5px; margin-top: 50px; }
        .btn-primary { width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h3 class="text-center">Add a Note</h3>
        <form action="add_note.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select" required>
                    <option value="">Select Category</option>
                    <?php while ($row = $category_result->fetch_assoc()) { ?>
                        <option value="<?php echo (int)$row['id']; ?>">
                            <?php echo htmlspecialchars($row['category_name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Reminder Date & Time (Optional)</label>
                <input type="datetime-local" name="reminder_date" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Upload File (Optional)</label>
                <input type="file" name="file" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Save Note</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
