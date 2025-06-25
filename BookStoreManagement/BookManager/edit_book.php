<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            padding: 40px;
            margin: 0;
        }

        main {
            background-color: white;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 22px;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
        }

        input[type="text"],
        input[type="number"],
        input[type="url"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        input[type="submit"] {
            margin-top: 24px;
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 18px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 6px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .back-link-bottom {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
            width: 100%;
            text-align: center;
            padding: 10px 0;
        }

        .back-link-bottom:hover {
            text-decoration: underline;
        }

        p {
            color: #721c24;
            background-color: #f8d7da;
            padding: 12px;
            border-radius: 6px;
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px;
            }

            main {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <main>
        <h2>Edit Book</h2>

        <?php
        $book_id = $_GET['book_id'] ?? 0;

        // Lấy dữ liệu sách theo ID
        $stmt_get_book = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
        $stmt_get_book->bind_param('i', $book_id);
        $stmt_get_book->execute();
        $result = $stmt_get_book->get_result();
        $book = $result->fetch_assoc();
        $stmt_get_book->close();

        if (!$book) {
            echo "<p>Book not found.</p>";
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $title = $_POST['title'];
            $price = $_POST['price'];
            $quantity = $_POST['quantity'];
            $description = $_POST['description'];
            $url_image = $_POST['url_image'];
            $publisher_id = $_POST['publisher_id'];

            $stmt_update_book = $conn->prepare("UPDATE books SET title = ?, price = ?, quantity = ?, description = ?, url_image = ?, publisher_id = ? WHERE book_id = ?");
            $stmt_update_book->bind_param("sdisssi", $title, $price, $quantity, $description, $url_image, $publisher_id, $book_id);

            if ($stmt_update_book->execute()) {
                header("Location: books.php");
                exit();
            } else {
                echo "<p style='color:red;'>Error: " . $stmt_update_book->error . "</p>";
            }
            $stmt_update_book->close();
        }

        $conn->close();
        ?>

        <form method="POST" action="">
            <label>Title:</label>
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

            <label>Price:</label>
            <input type="number" name="price" min="0" value="<?= $book['price'] ?>" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" min="0" value="<?= $book['quantity'] ?>" required>

            <label>Description:</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($book['description']) ?></textarea>

            <label>URL Image:</label>
            <input type="url" name="url_image" value="<?= htmlspecialchars($book['url_image']) ?>">

            <label>Publisher ID:</label>
            <input type="text" name="publisher_id" value="<?= htmlspecialchars($book['publisher_id']) ?>" required>

            <input type="submit" value="Update Book">
        </form>

        <a class="back-link-bottom" href="books.php">← Back to Book List</a>
    </main>
</body>

</html>