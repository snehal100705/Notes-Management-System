<?php
include("connection.php");

$now = date("Y-m-d H:i:s"); // Current timestamp
$next_hour = date("Y-m-d H:i:s", strtotime("+1 hour")); // One hour from now

// Query to find reminders due in the next hour
$query = "
    SELECT n.id, n.title, n.content, n.reminder_date, u.email 
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.reminder_date IS NOT NULL 
    AND n.reminder_date BETWEEN '$now' AND '$next_hour'
";

$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $to = $row['email'];
        $subject = "Reminder: " . $row['title'];
        $message = "Hi,\n\nThis is a reminder for your note: " . $row['title'] . "\n\n" . $row['content'] . "\n\nReminder Time: " . $row['reminder_date'];
        $headers = "From: noreply@yourwebsite.com";

        if (mail($to, $subject, $message, $headers)) {
            echo "Reminder sent to " . $to . " for note ID: " . $row['id'] . "\n";
        } else {
            echo "Failed to send reminder to " . $to . "\n";
        }
    }
} else {
    echo "No reminders due in the next hour.\n";
}

$conn->close();
?>
