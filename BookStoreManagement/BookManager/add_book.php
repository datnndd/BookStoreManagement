<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thêm Sách</title>
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
        input[type="date"],
        input[type="url"],
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
        <h2>Thêm Sách</h2>
        <form method="POST" action="">
            <label for="title">Tên sách:</label>
            <input type="text" id="title" name="title" required>

            <label for="price">Giá:</label>
            <input type="number" id="price" name="price" min="0" required>

            <label for="quantity">Số lượng:</label>
            <input type="number" id="quantity" name="quantity" min="0" required>

            <label for="description">Mô tả:</label>
            <textarea id="description" name="description" rows="4"></textarea>

            <label for="url_image">URL Ảnh:</label>
            <input type="url" id="url_image" name="url_image">

            <label for="publisher_id">ID Nhà xuất bản:</label>
            <input type="text" id="publisher_id" name="publisher_id" required>

            <button type="submit"><i class="fas fa-plus"></i> Thêm</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $description = $_POST['description'];
            $url_image = $_POST['url_image'];
            $publisher_id = $_POST['publisher_id'];

            if (empty($title) || empty($price) || empty($quantity) || empty($publisher_id)) {
                echo "<div class='message error'>Vui lòng điền đầy đủ các trường bắt buộc!</div>";
            } else {
                $sql = "INSERT INTO books(title, price, quantity, description, url_image, publisher_id) 
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sdisss", $title, $price, $quantity, $description, $url_image, $publisher_id);

                if ($stmt->execute()) {
                    header("Location: books.php");
                    exit();
                } else {
                    echo "<div class='message error'>Lỗi: " . $stmt->error . "</div>";
                }
                $stmt->close();
            }
        }
        ?>

        <a href="books.php">&larr; Quay lại danh sách</a>
    </main>
</body>

</html>