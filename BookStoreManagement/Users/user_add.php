<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone_number"];
    $address = $_POST["address"];

    if ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        if ($role === "customer" && (empty($full_name) || empty($phone) || empty($address))) {
            $error = "Vui lòng nhập đầy đủ thông tin khách hàng.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email, role, full_name, phone_number, address) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $username, $hashed, $email, $role, $full_name, $phone, $address);

            if ($stmt->execute()) {
                header("Location: users.php");
                exit();
            } else {
                $error = "Lỗi: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm người dùng</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            padding: 30px;
            color: #333;
        }

        main {
            background: #fff;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        h2 {
            font-size: 22px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        label {
            display: block;
            margin: 12px 0 6px;
            font-weight: bold;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select,
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button[type="submit"] {
            margin-top: 20px;
            padding: 10px 16px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            width: 100%;
        }

        button[type="submit"]:hover {
            background-color: #2980b9;
        }

        .message {
            margin-top: 16px;
            padding: 12px;
            border-radius: 6px;
            font-size: 14px;
        }

        .success {
            background-color: #eafaf1;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .error {
            background-color: #fdecea;
            color: #c0392b;
            border: 1px solid #f5c6cb;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #3498db;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            body {
                padding: 16px;
            }

            main {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <main>
        <h2>Thêm người dùng mới</h2>
        
        <?php if (!empty($error)) echo "<div class='message error'>$error</div>"; ?>
        
        <form method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Xác nhận mật khẩu</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <label for="role">Vai trò</label>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="customer">Customer</option>
            </select>

            <label for="full_name">Họ tên</label>
            <input type="text" id="full_name" name="full_name">

            <label for="phone_number">Số điện thoại</label>
            <input type="text" id="phone_number" name="phone_number">

            <label for="address">Địa chỉ</label>
            <textarea id="address" name="address" rows="3"></textarea>

            <button type="submit"><i class="fas fa-plus"></i> Thêm người dùng</button>
        </form>

        <a href="users.php">&larr; Quay lại danh sách</a>
    </main>
</body>
</html>