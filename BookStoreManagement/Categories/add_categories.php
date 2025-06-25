<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;
    $category_name = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($category_id === null || $category_id <= 0) {
        $error = "Vui lòng nhập mã danh mục hợp lệ (lớn hơn 0).";
    } elseif (empty($category_name)) {
        $error = "Vui lòng nhập tên danh mục.";
    } else {
        $check_sql = "SELECT category_id FROM categories WHERE category_id = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $category_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        if ($check_result->num_rows > 0) {
            $error = "Mã danh mục $category_id đã tồn tại.";
        } else {
            $sql = "INSERT INTO categories (category_id, category_name, description) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                $error = "Lỗi chuẩn bị truy vấn: " . $conn->error;
            } else {
                $stmt->bind_param("iss", $category_id, $category_name, $description);
                if ($stmt->execute()) {
                    header("Location: categories.php");
                    exit();
                } else {
                    $error = "Lỗi khi thêm: " . $stmt->error;
                }
                $stmt->close();
            }
        }
        $check_stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục</title>
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
        input[type="number"],
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
        <h2>Thêm Danh mục</h2>
        <form method="POST" action="">
            <label for="category_id">Mã danh mục:</label>
            <input type="number" id="category_id" name="category_id" required>

            <label for="category_name">Tên danh mục:</label>
            <input type="text" id="category_name" name="category_name" required>

            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <button type="submit"><i class="fas fa-plus"></i> Thêm</button>
        </form>

        <?php if (!empty($error)): ?>
            <div class='message error'><?php echo $error; ?></div>
        <?php endif; ?>

        <a href="categories.php">&larr; Quay lại danh sách</a>
    </main>
</body>

</html>
<?php $conn->close(); ?>