<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Category</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f5;
            padding: 40px;
        }

        main {
            background-color: white;
            padding: 30px;
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            margin-top: 20px;
            background-color: #007BFF;
            border: none;
            color: white;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .error {
            color: #ff0000;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<main>
    <h2>Sửa Danh Mục</h2>

    <?php
    $category_id = $_GET['category_id'] ?? 0;

    // Lấy dữ liệu danh mục theo ID
    $stmt_get_category = $conn->prepare("SELECT * FROM categories WHERE category_id = ?");
    $stmt_get_category->bind_param('i', $category_id);
    $stmt_get_category->execute();
    $result = $stmt_get_category->get_result();
    $category = $result->fetch_assoc();
    $stmt_get_category->close();

    if (!$category) {
        echo "<p class='error'>Category not found.</p>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $category_name = $_POST['category_name'];
        $description = $_POST['description'];

        $stmt_update_category = $conn->prepare("UPDATE categories SET category_name = ?, description = ? WHERE category_id = ?");
        $stmt_update_category->bind_param("ssi", $category_name, $description, $category_id);

        if ($stmt_update_category->execute()) {
            header("Location: categories.php?success=Category updated successfully");
            exit();
        } else {
            echo "<p class='error'>Error: " . $stmt_update_category->error . "</p>";
        }
        $stmt_update_category->close();
    }

    $conn->close();
    ?>

    <form method="POST" action="">
        <label>Mã thể loại:</label>
        <input type="number" name="category_id_display" value="<?= htmlspecialchars($category['category_id']) ?>" readonly>

        <label>Tên thể loại:</label>
        <input type="text" name="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>

        <label>Mô tả:</label>
        <textarea name="description" rows="4"><?= htmlspecialchars($category['description'] ?? '') ?></textarea>

        <input type="submit" value="Cập Nhật">
    </form>

    <a href="categories.php">← Back to Category List</a>
</main>
</body>
</html>