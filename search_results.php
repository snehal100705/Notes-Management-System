<?php
include("connection.php");
include("navbar.php");
// session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p class='text-danger'>User not logged in.</p>";
    exit();
}

$user_id = $_SESSION['user_id'];
$search_query = isset($_GET['query']) ? trim($_GET['query']) : '';

if ($search_query) {
    $query = "SELECT id, title, file_path FROM notes WHERE user_id = ? AND title LIKE ?";
    $stmt = $conn->prepare($query);
    $search_param = "%" . $search_query . "%";
    $stmt->bind_param("is", $user_id, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="row">';
        while ($row = $result->fetch_assoc()) {
            echo '
                <div class="col-md-4">
                    <div class="card p-3 mb-3">
                        <h5>' . htmlspecialchars($row["title"]) . '</h5>
                        <a href="' . htmlspecialchars($row["file_path"]) . '" class="btn btn-primary" target="_blank">View</a>
                    </div>
                </div>';
        }
        echo '</div>';
    } else {
        echo "<p class='text-danger'>No notes found.</p>";
    }
}
?>
