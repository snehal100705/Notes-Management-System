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

$query = "SELECT notes.*, users_registration.email,
COALESCE(categories.category_name, 'Uncategorized') AS category_name, 
                 notes.file_path  
          FROM notes 
          LEFT JOIN users_registration ON notes.user_id = users_registration.id
          LEFT JOIN categories ON notes.category_id = categories.id
          ORDER BY notes.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
            color: #333;
            font-family: 'Arial', sans-serif;
        }
        .container {
            width: 90%;
            max-width: 1400px;
            height: 87vh;
            background: #ffffff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            justify-content: center;
            margin-left: 450px;
        }
        .table-responsive {
            width: 100%;
            height: 70vh;
        }
        .table {
            width: 100%;
            height: 65vh;
            border-radius: 10px;
            overflow: hidden;
        }
        .table th {
            background: #007bff;
            color: white;
            text-align: center;
        }
        .table tbody tr:nth-child(odd) {
            background: #f8f9fa;
        }
        .btn-action {
            font-size: 14px;
            padding: 6px 12px;
            border-radius: 8px;
            transition: 0.3s;
        }
        .btn-view {
            background: rgb(112, 39, 248);
            border: none;
            color: white;
        }
        .btn-warning {
            background: #ffcc00;
            color: black;
            border: none;
        }
        .btn-edit {
            background: rgb(23, 121, 200);
            border: none;
            color: white;
        }
        .btn-delete {
            background: rgba(128, 170, 243, 0.81);
            border: none;
            color: white;
        }
        .btn-back {
            background: #343a40;
            border: none;
            color: white;
            width: 100%;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">📜 All Notes</h2>

    <!-- General Search Input -->
    <div class="col-md-4 mb-2">
        <input type="text" id="tableSearch" class="form-control" placeholder="Search notes...">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Category</th>
                    <th>File</th>
                    <th>Uploaded By</th>
                    <th>Pin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="notesTable">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['content'])); ?></td>
                        <td><?php echo htmlspecialchars($row['category_name'] ?? 'Uncategorized'); ?></td>
                        <td>
    <?php 
        if (!empty($row['file_path']) && $row['file_path'] != "0") { 
            // If a file exists, show the download/view option
    ?>
        <a href="view_file.php?file=<?php echo urlencode($row['file_path']); ?>" target="_blank" class="btn btn-view btn-action">📄 View</a>
    <?php 
        } else { 
            echo "No File Found";
        } 
    ?>
</td>


                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td>
                            <button class="btn btn-warning btn-action pin-button" data-id="<?php echo $row['id']; ?>">
                                <?php echo $row['is_favorite'] ? '📌 Unpin' : '📍 Pin'; ?>
                            </button>
                        </td>
                        <td>
                            <a href="edit_note.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-edit btn-action">✏ Edit</a>
                            <a href="delete_note.php?id=<?php echo urlencode($row['id']); ?>" class="btn btn-delete btn-action" onclick="return confirm('Are you sure you want to delete this note?');">🗑 Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <a href="dashboard.php" class="btn btn-back mt-3">⬅ Back to Dashboard</a>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // General Table Search
    $("#tableSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#notesTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Pin Functionality
    $(".pin-button").click(function() {
        var button = $(this);
        var noteId = button.data("id");

        $.ajax({
            url: "pin.php",
            type: "GET",
            data: { id: noteId },
            dataType: "json",
            success: function(response) {
                if (response.status === "success") {
                    button.html(response.is_favorite ? "📌 Unpin" : "📍 Pin");
                } else {
                    alert("Error: " + response.message);
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
