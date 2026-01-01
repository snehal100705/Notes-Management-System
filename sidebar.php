<?php

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar {
            width: 250px;
            height: 100vh;
            background: #1b1f23;
            color: white;
            position: fixed;
            padding-top: 50px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar a {
            color: white;
            display: flex;
            align-items: center;
            padding: 20px 20px;
            text-decoration: none;
            font-size: 16px;
            transition: background 0.3s;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar a:hover {
            background:rgb(75, 101, 128);
        }
        .logout {
            text-align: center;
            background: #dc3545;
            color: white;
            padding: 5px;
            font-size: 16px;
            text-decoration: none;
            margin: 60px;
            border-radius: 5px;
        }
        .logout:hover {
            background: #b02a37;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div>
            <!-- <h4 class="text-center">📓 Notes System</h4> -->
            <a href="dashboard.php"><i class="fas fa-home"></i> Home</a>
            <a href="add_note.php"><i class="fas fa-plus-circle"></i> Add Note</a>
            <a href="view_notes.php"><i class="fas fa-folder-open"></i> View All Notes</a>
            <a href="voice_to_text.php"><i class="fa fa-microphone"></i> Voice-to-Text</a>
            <a href="view_voice_notes.php" class="btn btn-primary">🎙️ View Voice Notes</a>

            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
        </div>
        <a href="logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</body>
</html>
