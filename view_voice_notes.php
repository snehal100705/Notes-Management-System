<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include "connection.php";

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch voice notes with ID
$query = "SELECT vn.id, vn.title, vn.category, vn.file_path, 
                 u.name AS uploaded_by, vn.pinned 
          FROM voice_notes vn
          LEFT JOIN users_registration u ON vn.uploaded_by = u.id
          ORDER BY vn.created_at DESC";

$result = mysqli_query($conn, $query);

// Error Handling for SQL Query
if (!$result) {
    die("<p class='text-danger'>SQL Error: " . mysqli_error($conn) . "</p>");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice Notes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
        }
        .table-container {
            overflow-x: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .btn-warning {
            color: black;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <?php include("navbar.php"); ?>

        <h2>🎙️ Voice Notes</h2>
        <a href="dashboard.php" class="btn btn-secondary mb-3">⬅️ Back to Dashboard</a>

        <div class="table-container">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>File</th>
                        <th>Uploaded By</th>
                        <th>Pin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr id="row-<?php echo $row['id']; ?>">
                        <td><?php echo !empty($row['title']) ? htmlspecialchars($row['title']) : 'Untitled'; ?></td>
                        <td><?php echo !empty($row['category']) ? htmlspecialchars($row['category']) : 'Uncategorized'; ?></td>
                        <td>
                            <?php if (!empty($row['file_path'])): ?>
                                <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank" class="btn btn-primary">
                                    📄 View
                                </a>
                            <?php else: ?>
                                <span class="text-danger">No File Found</span>
                            <?php endif; ?>
                        </td>
                        <!-- <td>?php echo htmlspecialchars($row['email']); ?></td> -->
                        <td><?php echo isset($row['uploaded_by']) ? htmlspecialchars($row['uploaded_by']) : 'N/A'; ?></td>
                        <td>
                            <button class="btn btn-warning pin-btn" data-id="<?php echo $row['id']; ?>" data-pinned="<?php echo $row['pinned']; ?>">
                                <?php echo $row['pinned'] ? '📌 Unpin' : '📌 Pin'; ?>
                            </button>
                        </td>
                        <td>
                            <a href="edit_voice_note.php?id=<?php echo $row['id']; ?>" class="btn btn-info">✏️ edit</a>
                            <a href="delete_voice_note.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">🗑️ delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $(".pin-btn").click(function() {
                let button = $(this);
                let noteId = button.data("id");
                let pinned = button.data("pinned");

                $.ajax({
                    url: "toggle_pin_voice.php",
                    type: "POST",
                    data: { id: noteId, pinned: pinned },
                    success: function(response) {
                        let result = JSON.parse(response);
                        if (result.status === "success") {
                            button.data("pinned", result.pinned);
                            button.html(result.pinned ? "📌 Unpin" : "📌 Pin");
                        } else {
                            alert("Error: " + result.message);
                        }
                    },
                    error: function() {
                        alert("An error occurred while updating the pin status.");
                    }
                });
            });
        });
    </script>

</body>
</html>
