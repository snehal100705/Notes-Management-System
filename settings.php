<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: registration.php");
    exit();
}

include("connection.php");
include("sidebar.php");  // Ensure this sets up your $conn

// Enable error reporting for debugging (optional)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$user_id = $_SESSION['user_id'];
$success_message = "";
$error_message = "";

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_name = isset($_POST['name']) ? trim($_POST['name']) : "";
    $new_email = isset($_POST['email']) ? trim($_POST['email']) : "";
    
    if (empty($new_name) || empty($new_email)) {
        $error_message = "Please fill in all fields.";
    } else {
        $update_query = "UPDATE users_registration SET name = ?, email = ? WHERE id = ?";
        if ($stmt = $conn->prepare($update_query)) {
            $stmt->bind_param("ssi", $new_name, $new_email, $user_id);
            if ($stmt->execute()) {
                $_SESSION['user_name'] = $new_name;
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Failed to update profile: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $error_message = "Prepare failed: " . $conn->error;
        }
    }
}

// Retrieve current user details
$query = "SELECT name, email FROM users_registration WHERE id = ?";
if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    die("Prepare failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include("navbar.php"); ?>

    <div class="container mt-5">
        <h2>⚙ Account Settings</h2>
        <p>Update your profile information below.</p>
        
        <?php if (!empty($success_message)) : ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)) : ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form action="settings.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input 
                    type="text" 
                    name="name"  
                    id="name" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($user['name']); ?>" 
                    required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control" 
                    value="<?php echo htmlspecialchars($user['email']); ?>" 
                    required>
            </div>

            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
</body>
</html>
