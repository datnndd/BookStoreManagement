<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';
$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'id';
$search_value = isset($_GET['search_value']) ? trim($_GET['search_value']) : '';
$is_search_active = !empty($search_value);

$sql = "SELECT * FROM author";
if (!empty($search_value)) {
    $search_value = $conn->real_escape_string($search_value);
    if ($search_type == 'id') {
        $sql .= " WHERE author_id = '$search_value'";
    } elseif ($search_type == 'name') {
        $sql .= " WHERE author_name LIKE '%$search_value%'";
    }
}
$list_author = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Author Management</title>
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
            position: relative;
        }

        h2 {
            color: #1e3a8a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #1e3a8a;
            color: white;
            position: sticky;
            top: 0;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .bio {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        img {
            max-width: 80px;
            height: auto;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .actions a {
            padding: 8px 12px;
            color: white;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .actions a.edit {
            background-color: #10b981;
        }

        .actions a.delete {
            background-color: #ef4444;
        }

        .actions a:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .search-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .search-input {
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex: 1;
            max-width: 400px;
            transition: all 0.2s;
        }

        .search-input:focus {
            border-color: #1e3a8a;
            box-shadow: 0 0 0 2px rgba(30, 58, 138, 0.2);
            outline: none;
        }

        .button, .search-btn {
            padding: 10px 16px;
            background: #1e3a8a;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .button:hover, .search-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .no-authors {
            text-align: center;
            padding: 30px;
            color: #6c757d;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 20px 0;
        }

        .return-button {
            background: #1e3a8a;
            color: white;
            padding: 10px 16px;
            border-radius: 4px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .return-button:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

            .actions {
                flex-direction: column;
                gap: 6px;
            }

            .actions a {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="button-container">
            <h2><i class="fas fa-user-edit"></i> Author Management</h2>
            <div>
                <a href="add_author.php" class="button"><i class="fas fa-plus"></i> Add New Author</a>
                <a href="../index.php" target="_top" class="return-button"><i class="fas fa-arrow-left"></i> Return</a>
            </div>
        </div>

        <div class="search-container">
            <button class="search-btn" onclick="setSearchType('id')"><i class="fas fa-search"></i> Search by ID</button>
            <button class="search-btn" onclick="setSearchType('name')"><i class="fas fa-search"></i> Search by Name</button>
        </div>

        <div class="search-container" id="searchContainer">
            <form method="GET" style="display: flex; gap: 10px; width: 100%;">
                <input type="text" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>" class="search-input" placeholder="<?php echo $search_type == 'id' ? 'Enter Author ID' : 'Enter Author Name'; ?>">
                <input type="hidden" name="search_type" value="<?php echo $search_type; ?>">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <?php if ($list_author->num_rows > 0): ?>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Birthday</th>
                            <th>Country</th>
                            <th>Bio</th>
                            <th>Image</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $list_author->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['author_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['birthday'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($row['country'] ?? 'N/A'); ?></td>
                                <td class="bio"><?php echo htmlspecialchars($row['bio'] ?? 'N/A'); ?></td>
                                <td>
                                    <?php if (!empty($row['url_image'])): ?>
                                        <img src="<?php echo htmlspecialchars($row['url_image']); ?>" alt="Author Image">
                                    <?php else: ?>
                                        <span style="color:#999;">No image</span>
                                    <?php endif; ?>
                                </td>
                                <td class="actions">
                                    <a class="edit" href="edit_author.php?author_id=<?php echo htmlspecialchars($row['author_id']); ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a class="delete" href="delete_author.php?author_id=<?php echo htmlspecialchars($row['author_id']); ?>" 
                                       onclick="return confirm('Are you sure you want to delete this author?');">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="no-authors">No authors found.</div>
        <?php endif; ?>
        
        <?php $conn->close(); ?>
    </main>

    <script>
        function setSearchType(type) {
            document.querySelector('input[name="search_type"]').value = type;
            const searchInput = document.querySelector('.search-input');
            searchInput.placeholder = type === 'id' ? 'Enter Author ID' : 'Enter Author Name';
            searchInput.value = '';
            document.getElementById('searchContainer').style.display = 'flex';
            searchInput.focus();
        }
    </script>
</body>
</html>