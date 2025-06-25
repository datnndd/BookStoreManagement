<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Author</title>
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
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="date"],
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
        }

        .back-link-bottom:hover {
            text-decoration: underline;
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
    <h2>Edit Author</h2>

    <?php
    $author_id = $_GET['author_id'] ?? 0;

    $stmt_get_author = $conn->prepare("SELECT * FROM author WHERE author_id = ?");
    $stmt_get_author->bind_param('i', $author_id);
    $stmt_get_author->execute();
    $result = $stmt_get_author->get_result();
    $author = $result->fetch_assoc();
    $stmt_get_author->close();

    if (!$author) {
        echo "<p>Author not found.</p>";
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['author_name'];
        $birth = $_POST['birthday'];
        $country = $_POST['country'];
        $bio = $_POST['bio'];
        $url_image = $_POST['url_image'];

        $stmt_update = $conn->prepare("UPDATE author SET author_name = ?, birthday = ?, country = ?, bio = ?, url_image = ? WHERE author_id = ?");
        $stmt_update->bind_param("sssssi", $name, $birth, $country, $bio, $url_image, $author_id);

        if ($stmt_update->execute()) {
            header("Location: authors.php");
            exit();
        } else {
            echo "<p style='color:red;'>Error: " . $stmt_update->error . "</p>";
        }
        $stmt_update->close();
    }

    $conn->close();
    ?>

    <form method="POST">
        <label>Name:</label>
        <input type="text" name="author_name" value="<?= htmlspecialchars($author['author_name']) ?>" required>

        <label>Birthday:</label>
        <input type="date" name="birthday" value="<?= $author['birthday'] ?>" required>

        <label>Country:</label>
        <input type="text" name="country" value="<?= $author['country'] ?>" required>

        <label>Bio:</label>
        <textarea name="bio" rows="4"><?= htmlspecialchars($author['bio']) ?></textarea>

        <label>URL Image:</label>
        <input type="url" name="url_image" value="<?= htmlspecialchars($author['url_image']) ?>">

        <input type="submit" value="Update Author">
    </form>

    <a class="back-link-bottom" href="authors.php">← Back to Author List</a>
</main>
</body>
</html>
