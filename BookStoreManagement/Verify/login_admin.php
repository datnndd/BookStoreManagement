<?php
session_start();
require 'db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user"] = $user;
        header("Location: ../index.php");
        exit();
    } else {
        $error = "❌ Sai tên đăng nhập hoặc mật khẩu.";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Đăng nhập hệ thống</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(0);
            transition: all 0.3s ease;
        }

        .login-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
        }

        input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        input::placeholder {
            color: #999;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #FFD700;
            color: #DC3545;
            border: none;
            border-radius: 25px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        button:hover {
            background: #FFC107;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }

        button:active {
            transform: translateY(0);
        }

        .error {
            color: #dc3545;
            background: #ffe6e6;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            border: 1px solid #ffcccc;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .login-icon {
            text-align: center;
            margin-bottom: 20px;
        }

        .login-icon img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
            
        }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div class="login-container">
        <div class="login-icon">
            <img src="download.png" alt="User Icon">
        </div>
        <h2>ĐĂNG NHẬP ADMIN</h2>
        <?php if (!empty($error)) echo "<div class='error'><i class='fas fa-exclamation-circle'></i> $error</div>"; ?>
        <form method="post">
            <div class="form-group">
                <label for="username">Username <span style="color: red;">*</span></label>
                <input type="text" id="username" name="username" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="password">Password <span style="color: red;">*</span></label>
                <input type="password" id="password" name="password" placeholder="Mật khẩu" required>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>

</html>