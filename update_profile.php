<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $query = "UPDATE users SET username = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $username, $email, $user_id);

    if ($stmt->execute()) {
        $_SESSION['user_name'] = $username;
        header("Location: profile.php?success=Profile updated successfully");
    } else {
        header("Location: profile.php?error=Failed to update profile");
    }
}
?>
