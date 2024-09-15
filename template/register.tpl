<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ثبت نام | مشکی</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('./admin/banners/register.gif');
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-container {
            background-color: #ffffff;
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
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h2>ثبت نام</h2>
        <form action="register.php" method="post">
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">ایمیل:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">رمز عبور:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">تکرار رمز عبور:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">ثبت نام</button>
        </form>
    </div>
</body>
</html>
