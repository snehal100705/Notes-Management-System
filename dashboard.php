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

// Fetch statistics using a single query (without recent note)
$query = "
   SELECT 
        (SELECT COUNT(*) FROM notes) AS totalNotes,
        (SELECT COUNT(*) FROM notes WHERE is_favorite = 1) AS pinnedNotes
";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("ii", $user_id, $user_id);
// $stmt->execute();
// $result = $stmt->get_result()->fetch_assoc();

$result = $conn->query($query)->fetch_assoc();

$stats = [
    'totalNotes' => $result['totalNotes'] ?? 0,
    'pinnedNotes' => $result['pinnedNotes'] ?? 0
];

// Fetch latest note separately
$recentNoteQuery = "SELECT id, title FROM notes WHERE user_id = ? ORDER BY id DESC LIMIT 1";
$stmtRecent = $conn->prepare($recentNoteQuery);
$stmtRecent->bind_param("i", $user_id);
$stmtRecent->execute();
$recentResult = $stmtRecent->get_result()->fetch_assoc();

$recentNoteId = $recentResult['id'] ?? null;
$recentNoteTitle = $recentResult['title'] ?? 'No Recent Note';

// Fetch file types
$fileCounts = ['pdf' => 0, 'image' => 0, 'other' => 0];
$fileTypeQuery = "SELECT file_path FROM notes WHERE user_id = ?";
$stmtFile = $conn->prepare($fileTypeQuery);
$stmtFile->bind_param("i", $user_id);
$stmtFile->execute();
$fileResult = $stmtFile->get_result();

while ($row = $fileResult->fetch_assoc()) {
    $ext = strtolower(pathinfo($row['file_path'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'png', 'jpeg', 'gif'])) {
        $fileCounts['image']++;
    } elseif ($ext === 'pdf') {
        $fileCounts['pdf']++;
    } else {
        $fileCounts['other']++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - Infinity Notes</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
       .stat-card {
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            font-weight: bold;
            box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.2);
            width: 450px;
            height: 180px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: scale(1.05);
        }

        .content {
            margin-left: 250px;
            padding: 30px;
        }

        .row-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 65px;
            margin-bottom: 70px;
        }

        h5 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 28px;
        }
    </style>
</head>
<body>
<div class="content">
    <h2 class="mb-4">Welcome, <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>!</h2>

    <div class="row-container">
        <a href="view_notes.php" class="card stat-card bg-primary text-white text-decoration-none">
        <i class="fa-solid fa-note-sticky fa-2x"></i>
        <h5>Total Notes</h5>
            <h2><?php echo $stats['totalNotes']; ?></h2>
        </a>
        <a href="pinned_notes.php" class="card stat-card bg-warning text-dark text-decoration-none">
        <i class="fa-solid fa-thumbtack fa-2x"></i>    
        <h5>Pinned Notes</h5>
            <h2><?php echo $stats['pinnedNotes']; ?></h2>
        </a>
        <a href="<?php echo $recentNoteId ? 'view.php?id=' . $recentNoteId : '#'; ?>" class="card stat-card bg-success text-white text-decoration-none">
        <i class="fa-solid fa-clock fa-2x"></i>    
        <h5>Latest Note</h5>
            <h4><?php echo htmlspecialchars($recentNoteTitle); ?></h4>
        </a>
    </div>

    <!-- <h4 class="mt-4">File Type Summary</h4> -->
    <div class="row-container">
        <a href="pdf_files.php" class="card stat-card bg-danger text-white text-decoration-none">
        <i class="fa-solid fa-file-pdf fa-2x"></i>    
        <h5>PDF Files</h5>
            <h2><?php echo $fileCounts['pdf']; ?></h2>
        </a>
        <a href="image_files.php" class="card stat-card bg-info text-white text-decoration-none">
        <i class="fa-solid fa-image fa-2x"></i>    
        <h5>Image Files</h5>
            <h2><?php echo $fileCounts['image']; ?></h2>
        </a>
        <a href="other_files.php" class="card stat-card bg-secondary text-white text-decoration-none">
        <i class="fa-solid fa-file fa-2x"></i>    
        <h5>Other Files</h5>
            <h2><?php echo $fileCounts['other']; ?></h2>
        </a>
    </div>

    <div class="mt-4">
    <h4>Recently Added Notes</h4>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody id="recent-notes-list">
                    <!-- Notes will be added dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    fetch('recent_notes.php') // Call PHP script to get recent notes
        .then(response => response.json())
        .then(notes => {
            const tableBody = document.getElementById("recent-notes-list");
            tableBody.innerHTML = ""; // Clear previous content

            if (notes.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="3" class="text-center">No recent notes found</td></tr>`;
                return;
            }

            notes.forEach((note, index) => {
                 const row = document.createElement("tr");
                row.innerHTML = `
                    <td>${index + 1}</td>
                    <td><a href="view.php?id=${note.id}">${note.title}</a></td>
                    <td>${new Date(note.created_at).toLocaleString()}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error("Error fetching notes:", error));
});


function searchNotes() {
    let query = document.getElementById("searchInput").value.trim();
    if (query === "") {
        document.getElementById("searchResults").innerHTML = "";
        return;
    }

    let xhr = new XMLHttpRequest();
    xhr.open("GET", "search_results.php?query=" + encodeURIComponent(query), true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("searchResults").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
</script>

</div>
</div>
</body>
</html>