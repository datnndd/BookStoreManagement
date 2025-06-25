<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Thêm Tác giả</title>
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
        <h2>Thêm Tác giả</h2>
        <form method="POST" action="">
            <label for="author_name">Tên:</label>
            <input type="text" id="author_name" name="author_name" required>

            <label for="birthday">Ngày sinh:</label>
            <input type="date" id="birthday" name="birthday" required>

            <label for="country">Quốc gia:</label>
            <input type="text" id="country" name="country" required>

            <label for="bio">Tiểu sử:</label>
            <textarea id="bio" name="bio" rows="4"></textarea>

            <label for="url_image">URL Ảnh:</label>
            <input type="url" id="url_image" name="url_image">

            <button type="submit"><i class="fas fa-plus"></i> Thêm</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $name = $_POST['author_name'];
            $birth = $_POST['birthday'];
            $country = $_POST['country'];
            $bio = $_POST['bio'];
            $url_image = $_POST['url_image'];

            if (empty($name) || empty($birth) || empty($country)) {
                echo "<div class='message error'>Vui lòng điền đầy đủ các trường bắt buộc!</div>";
            } else {
                $sql = "INSERT INTO author(author_name, birthday, country, bio, url_image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $name, $birth, $country, $bio, $url_image);

                if ($stmt->execute()) {
                    header("Location: authors.php");
                    exit();
                } else {
                    echo "<div class='message error'>Lỗi: " . $stmt->error . "</div>";
                }
                $stmt->close();
            }
        }
        ?>

        <a href="authors.php">&larr; Quay lại danh sách</a>
    </main>
</body>

</html>
