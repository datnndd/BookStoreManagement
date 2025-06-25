<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'id';
$search_value = isset($_GET['search_value']) ? trim($_GET['search_value']) : '';
$is_search_active = !empty($search_value);

$sql = "SELECT * FROM books";
if (!empty($search_value)) {
    $search_value = $conn->real_escape_string($search_value);
    if ($search_type == 'id') {
        $sql .= " WHERE book_id = '$search_value'";
    } elseif ($search_type == 'title') {
        $sql .= " WHERE title LIKE '%$search_value%'";
    }
}
$list_books = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Book Management</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }

        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #1e3a8a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-container {
        width: 100%;
        overflow-x: auto;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0; 
        min-width: 800px;
    }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #1e3a8a;
            color: white;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .description {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #6c757d;
        }

        img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
        }

        .actions a {
            padding: 6px 12px;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            margin-right: 5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .actions a.edit {
            background-color: #10b981;
        }

        .actions a.delete {
            background-color: #ef4444;
        }

        .search-container {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .search-input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex: 1;
            max-width: 400px;
        }

        .button, .search-btn {
            padding: 8px 16px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }

        .button:hover, .search-btn:hover {
            background: #1d4ed8;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .no-books {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .return-button {
            background: #1e3a8a;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .search-input {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="button-container">
            <h2><i class="fas fa-book"></i> Book Management</h2>
            <div>
                <a href="add_book.php" class="button"><i class="fas fa-plus"></i> Add New Book</a>
                <a href="../index.php" target="_top" class="return-button"><i class="fas fa-arrow-left"></i> Return</a>
            </div>
        </div>

        <div class="search-container">
            <button class="search-btn" onclick="setSearchType('id')"><i class="fas fa-search"></i> Search by ID</button>
            <button class="search-btn" onclick="setSearchType('title')"><i class="fas fa-search"></i> Search by Title</button>
        </div>

        <div class="search-container" id="searchContainer">
            <form method="GET" style="display: flex; gap: 10px; width: 100%;">
                <input type="text" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>" class="search-input" placeholder="<?php echo $search_type == 'id' ? 'Enter Book ID' : 'Enter Book Title'; ?>">
                <input type="hidden" name="search_type" value="<?php echo $search_type; ?>">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <?php
        if ($list_books->num_rows > 0) {
            echo "<table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Description</th>
                            <th>Cover Image</th>
                            <th>Publisher ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>";
            while ($row = $list_books->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['book_id']) . "</td>
                        <td>" . htmlspecialchars($row['title']) . "</td>
                        <td>" . htmlspecialchars($row['price']) . "</td>
                        <td>" . htmlspecialchars($row['quantity']) . "</td>
                        <td class='description'>" . htmlspecialchars($row['description'] ?? 'N/A') . "</td>
                        <td>";
                echo !empty($row['url_image']) 
                    ? "<img src='" . htmlspecialchars($row['url_image']) . "' alt='Book Cover'>" 
                    : "<span style='color:#999;'>No image</span>";
                echo "</td>
                        <td>" . htmlspecialchars($row['publisher_id']) . "</td>
                        <td class='actions'>
                            <a class='edit' href='edit_book.php?book_id=" . htmlspecialchars($row['book_id']) . "'><i class='fas fa-edit'></i> Edit</a>
                            <a class='delete' href='delete_book.php?book_id=" . htmlspecialchars($row['book_id']) . "' onclick=\"return confirm('Are you sure you want to delete this book?');\"><i class='fas fa-trash'></i> Delete</a>
                        </td>
                    </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='no-books'>No books found.</div>";
        }
        
        $conn->close();
        ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
        <script>
            function setSearchType(type) {
                document.querySelector('input[name="search_type"]').value = type;
                const searchInput = document.querySelector('.search-input');
                searchInput.placeholder = type === 'id' ? 'Enter Book ID' : 'Enter Book Title';
                searchInput.value = '';
                document.getElementById('searchContainer').style.display = 'flex';
                searchInput.focus();
            }
        </script>
    </main>
</body>

</html>