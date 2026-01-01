<?php
//session_start(); // Ensure session is started before using session variables
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infinity Notes</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <style>
       .navbar-custom {
            background-color:rgb(10, 14, 11) !important; /* Custom green color */
        }
        .navbar-custom .navbar-nav .nav-link,
        .navbar-custom .navbar-brand {
            color: white !important; /* Ensuring text is white */
        }
        .navbar-custom .dropdown-menu {
            background-color:rgb(6, 9, 7); /* Dropdown matches navbar */
        }
        .navbar-custom .dropdown-menu .dropdown-item {
            color: white !important; /* Dropdown text white */
        }
        .navbar-custom .dropdown-menu .dropdown-item:hover {
            background-color:rgb(15, 28, 18) !important; /* Darker green on hover */
        }
        
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">
        <img src="assist/logo.png" alt="Logo" width="40" height="40" class="rounded-circle me-2">
        Infinity Notes</a>
        <!-- Hamburger Menu for Mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">🏠 Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="add_note.php">📝 Add Note</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="view_notes.php">📂 View Notes</a>
                </li>

                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="bi bi-person text-white"></i> <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?>
</a>

                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="settings.php">⚙ Profile & Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php">🚪 Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Bootstrap JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
