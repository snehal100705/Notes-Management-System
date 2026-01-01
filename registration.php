<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
            color: #333;
            text-align: left;
        }
        .register {
            max-width: 600px;
            width: 100%;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease-in-out;
        }
         h2 {
            text-align: center;
            width: 100%;
            font-size: 28px;
            font-weight: bold;
            color: #007bff;
        } 
        p {
            margin-top: 15px;
            font-size: 16px;
        }       
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .box {
            width: 100%;
            padding: 12px;
            margin-top: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            outline: none;
            background: #f9f9f9;
            font-size: 16px;
        }
        button {
            background: #007bff;
            color: white;
            font-size: 18px;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: 0.3s;
        }
        button:hover {
            background: #0056b3;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="register">
        <h2>📝 User Registration</h2>
        <form action="registration_db.php" method="POST">

            <label>Full Name</label>
            <input type="text" name="name" class="box" required placeholder="Enter your name">

            <label>Email</label>
            <input type="email" name="email" class="box" required placeholder="Enter your email">

            <label>Phone Number</label>
            <input type="text" name="phone" class="box" required placeholder="Enter your phone number">

            <label>Password</label>
            <input type="password" name="password" class="box" required placeholder="Enter your password">

            <label>Gender</label>
            <select name="gender" class="box" required>
                <option value="" selected disabled>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Other">Other</option>
            </select>

            <button type="submit">Register</button>
            <p>Already have an account? <a href="login.php">Login Here</a></p>

        </form>
    </div>

</body>
</html>
