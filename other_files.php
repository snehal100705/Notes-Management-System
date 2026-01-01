<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");
include("navbar.php");
include("sidebar.php");

$query = "SELECT * FROM notes WHERE file_path NOT LIKE '%.pdf' AND file_path NOT LIKE '%.jpg' AND file_path NOT LIKE '%.png' AND file_path NOT LIKE '%.jpeg' AND file_path NOT LIKE '%.gif'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PDF Files</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>📄 PDF Files</h2>
    <ul class="list-group">
        <?php while ($row = $result->fetch_assoc()): ?>
            <li class="list-group-item">
                <?php echo htmlspecialchars($row['title']); ?>
                <a href="uploads/<?php echo urlencode($row['file_path']); ?>" target="_blank" class="btn btn-primary float-end">View</a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
</body>
</html>
