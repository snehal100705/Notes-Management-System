<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
include("connection.php");
// include("navbar.php");
// include("sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voice-to-Text</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            background-color: #f8f9fa;
            overflow: hidden;
        }

        /* Sidebar with Attached Navbar */
        /* .sidebar {
            width: 250px;
            height: 100vh;
            background: #212529;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
        }

        /* Sidebar Navbar 
        .sidebar .navbar {
            width: 100%;
            background: #000;
            padding: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar .logo {
            font-size: 20px;
            font-weight: bold;
        } */

        /* Main Content */
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Voice-to-Text Card */
        .container-box {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            max-width: 700px;
            width: 100%;
            text-align: center;
        }

        .select-container {
            margin-top: 15px;
        }

        /* Loading Indicator */
        .loading {
            display: none;
            font-size: 14px;
            color: #28a745;
        }
    </style>
</head>
<body>

    <!-- Sidebar (Navbar Inside) --> 
    <div class="sidebar"> 
        <!-- Navbar inside Sidebar -->
         <!-- <div class="navbar">
            <span class="logo">📓 Notes App</span>
        </div> -->

        <!-- Include Sidebar Content -->
        <?php include("sidebar.php"); ?>
        7058412641
        </div>
    <!-- <div class="navbar">
            ?php include("navbar.php"); ?>
    </div> -->
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-box">
            <h2>🎙️ Voice-to-Text Note</h2>

            <!-- Title Input -->
            <div class="select-container">
                <label for="title"><b>Title:</b></label>
                <input type="text" id="title" class="form-control" placeholder="Enter note title...">
            </div>

            <!-- Language Selection -->
            <div class="select-container">
                <label for="languageSelect"><b>Select Language:</b></label>
                <select id="languageSelect" class="form-select">
                    <option value="en-US">English (US)</option>
                    <option value="hi-IN">Hindi</option>
                    <option value="mr-IN">Marathi</option>
                    <option value="es-ES">Spanish</option>
                    <option value="fr-FR">French</option>
                    <option value="de-DE">German</option>
                    <option value="zh-CN">Chinese (Mandarin)</option>
                    <option value="ar-SA">Arabic</option>
                    <option value="ru-RU">Russian</option>
                </select>
            </div>

            <!-- Category Selection -->
            <div class="select-container">
                <label for="categorySelect"><b>Select Category:</b></label>
                <select id="categorySelect" class="form-select">
                    <option value="General">General</option>
                    <option value="Work">Work</option>
                    <option value="Personal">Personal</option>
                    <option value="Ideas">Ideas</option>
                    <option value="Reminders">Reminders</option>
                </select>
            </div>

            <!-- Text Area -->
            <textarea id="noteText" class="form-control mt-3" placeholder="Type or use voice input..."></textarea>

            <!-- Buttons -->
            <button id="startRecording" class="btn btn-success mt-3">
                <i class="fas fa-microphone"></i> Start Voice Input
            </button>

            <button id="saveNote" class="btn btn-primary mt-3">
                <i class="fas fa-save"></i> Save Note
            </button>

            <!-- Status Messages -->
            <p id="status" class="mt-2"></p>
            <p class="loading">⏳ Processing....</p>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const startButton = document.getElementById("startRecording");
            const saveButton = document.getElementById("saveNote");
            const titleInput = document.getElementById("title");
            const noteText = document.getElementById("noteText");
            const status = document.getElementById("status");
            const languageSelect = document.getElementById("languageSelect");
            const categorySelect = document.getElementById("categorySelect");
            const loadingIndicator = document.querySelector(".loading");

            if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                const recognition = new SpeechRecognition();

                recognition.continuous = false;
                recognition.interimResults = false;

                startButton.addEventListener("click", function () {
                    recognition.lang = languageSelect.value;
                    recognition.start();
                    status.textContent = "🎤 Listening...";
                    startButton.disabled = true;
                });

                recognition.onresult = function (event) {
                    const transcript = event.results[0][0].transcript;
                    noteText.value += transcript + " ";
                    status.textContent = "✅ Voice input added!";
                    startButton.disabled = false;
                };

                recognition.onerror = function (event) {
                    status.textContent = "❌ Error: " + event.error;
                    startButton.disabled = false;
                };

                recognition.onend = function () {
                    status.textContent = "🛑 Stopped listening.";
                    startButton.disabled = false;
                };

                saveButton.addEventListener("click", function () {
                    const title = titleInput.value.trim();
                    const noteContent = noteText.value.trim();
                    const category = categorySelect.value;

                    if (title === "") {
                        status.textContent = "⚠️ Title is required!";
                        return;
                    }

                    loadingIndicator.style.display = "block";

                    fetch("save_voice_note.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "title=" + encodeURIComponent(title) + 
                              "&content=" + encodeURIComponent(noteContent) + 
                              "&category=" + encodeURIComponent(category)
                    })
                    .then(response => response.json())
                    .then(data => {
                        loadingIndicator.style.display = "none";
                        status.textContent = data.message;
                        if (data.status === "success") {
                            titleInput.value = "";
                            noteText.value = "";
                            setTimeout(() => window.location.href = "view_voice_notes.php", 1000);
                        }
                    })
                    .catch(error => {
                        loadingIndicator.style.display = "none";
                        status.textContent = "❌ Error saving note.";
                    });
                });

            } else {
                startButton.disabled = true;
                status.textContent = "❌ Voice input not supported.";
            }
        });
    </script>

</body>
</html>
