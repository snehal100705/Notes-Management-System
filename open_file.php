<?php
if (!isset($_GET['file'])) {
    die("Invalid request.");
}

$file = urldecode($_GET['file']);
$file_path = __DIR__ . "/" . $file; // Adjust path if needed

if (!file_exists($file_path)) {
    die("File not found.");
}

// Get MIME type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file_path);
finfo_close($finfo);

header("Content-Type: $mime_type");
header("Content-Length: " . filesize($file_path));
readfile($file_path);
exit();
?>
