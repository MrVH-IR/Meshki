<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Meshki</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./admin/banners/login.gif');
            background-size: 100% auto;
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            color: #666;
        }
        input {
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #ff69b4;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 10px;
        }
        button:hover {
            background-color: #ff1493;
        }
        .register-btn {
            background-color: #4CAF50;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }
        .register-btn:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login to Meshki</h2>
        <form action="login.php" method="post">
            <label for="username">Username :</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password :</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error-message">{{error_message}}</p>
        <?php endif; ?>
        <p>Don't have an account? <a href="register.php">Register</a></p>
        <button class="register-btn" onclick="window.location.href='register.php'">Register</button>
    </div>
</body>
</html>
