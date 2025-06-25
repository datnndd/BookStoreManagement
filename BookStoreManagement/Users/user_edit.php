<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';


$id = $_GET['id'] ?? null;
if (!$id) exit("Thiếu ID user.");

$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
if (!$data) exit("User không tồn tại.");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $role = $_POST["role"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $full_name = $_POST["full_name"];
    $phone = $_POST["phone_number"];
    $address = $_POST["address"];

    if (!empty($password)) {
        if ($password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp.";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=?, password=?, full_name=?, phone_number=?, address=? WHERE user_id=?");
            $stmt->bind_param("sssssssi", $username, $email, $role, $hashed, $full_name, $phone, $address, $id);
        }
    } else {
        $stmt = $conn->prepare("UPDATE users SET username=?, email=?, role=?, full_name=?, phone_number=?, address=? WHERE user_id=?");
        $stmt->bind_param("ssssssi", $username, $email, $role, $full_name, $phone, $address, $id);
    }

    if (!isset($error)) {
        if ($stmt->execute()) {
            header("Location: users.php");
            exit();
        } else {
            $error = "Lỗi: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa người dùng</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f2f4f8;
            margin: 0;
            padding: 40px;
        }
        .form-container {
            max-width: 650px;
            margin: auto;
            background: #fff;
            padding: 35px 45px;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            color: #34495e;
            margin-bottom: 25px;
        }
        label {
            font-weight: 600;
            margin: 12px 0 6px;
            display: block;
            color: #333;
        }
        input, select, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            margin-bottom: 10px;
        }
        input:focus, select:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        .error {
            color: red;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 14px;
            background-color: #3498db;
            border: none;
            color: white;
            font-weight: bold;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        .back {
            margin-top: 20px;
            text-align: center;
        }
        a.button {
            text-decoration: none;
            background: #2ecc71;
            color: white;
            padding: 10px 16px;
            border-radius: 6px;
            font-weight: 500;
        }
        a.button:hover {
            background: #27ae60;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sửa thông tin người dùng</h2>
        <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="post">
            <label>Username</label>
            <input name="username" required value="<?= htmlspecialchars($data['username']) ?>">

            <label>Email</label>
            <input name="email" type="email" required value="<?= htmlspecialchars($data['email']) ?>">

            <label>Đổi mật khẩu (bỏ trống nếu không thay)</label>
            <input name="password" type="password">

            <label>Xác nhận mật khẩu</label>
            <input name="confirm_password" type="password">

            <label>Vai trò</label>
            <select name="role" required>
                <option value="admin" <?= $data['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="customer" <?= $data['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
            </select>

            <label>Họ tên</label>
            <input name="full_name" value="<?= htmlspecialchars($data['full_name']) ?>">

            <label>Số điện thoại</label>
            <input name="phone_number" value="<?= htmlspecialchars($data['phone_number']) ?>">

            <label>Địa chỉ</label>
            <textarea name="address" rows="3"><?= htmlspecialchars($data['address']) ?></textarea>

            <button type="submit">💾 Cập nhật thông tin</button>
        </form>
        <div class="back">
            <a href="users.php" class="button">← Quay lại danh sách</a>
        </div>
    </div>
</body>
</html>
