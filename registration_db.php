<?php
session_start();
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash password
    $gender = $_POST['gender'];

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users_registration WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<script>alert('Email already registered! Login Please...'); window.location.href='login.php';</script>";
    } else {
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users_registration (name, email, phone, password, gender) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $password, $gender);

        if ($stmt->execute()) {
            header("location: login.php");
        } else {
            echo "<script>alert('Registration failed! Try again.'); window.location.href='registration.php';</script>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
