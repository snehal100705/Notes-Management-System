<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Infinity Notes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            background: #ffffff;
            padding: 50px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }
        .login-container h1 {
            margin-bottom: 20px;
            font-size: 44px;
            color: #333;
        }
        p {
            margin-top: 15px;
            font-size: 20px;
        }   
        .form-control {
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .link {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>🔑 Login</h1>
        <form action="login_db.php" method="post">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" required placeholder="Enter Your Email">
            </div>
            <div class="mb-3">
                <input type="password" name="password" class="form-control" required placeholder="Enter Your Password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <p>Don't have an account? Register Here ! <a href="registration.php">Register Here</a></p>
        </form>
    </div>
</body>
</html>
