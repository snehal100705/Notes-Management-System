<?php
if (isset($_GET['file'])) {
    $file = "uploads/" . basename($_GET['file']); // Prevent directory traversal attacks

    if (file_exists($file)) {
        header("Content-Type: text/plain");
        header("Content-Disposition: inline; filename=" . basename($file));
        readfile($file);
        exit;
    } else {
        echo "❌ File not found!";
    }
}
?>
