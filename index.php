<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Infinity Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f4f4f4, #d9e8fc);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            text-align: center;
        }
        .container {
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .icon {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
        }
        .btn-custom {
            background: #007bff;
            color: white;
            font-size: 18px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            transition: 0.3s;
            margin-top: 15px;
        }
        .btn-custom:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" alt="Notes Icon" class="icon"> -->
        <img src="assist/logo.png" alt="Logo" width="130" height="130" class="rounded-circle me-2">

        <h2>Welcome to Infinity Notes</h2>
        <p>Organize your notes easily and access them anytime.</p>
        <a href="registration.php" class="btn btn-custom">Get Started</a>
    </div>

</body>
</html>
