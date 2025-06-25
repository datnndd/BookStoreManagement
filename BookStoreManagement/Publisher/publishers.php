<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

$success_message = '';
$error_message = '';

if (isset($_GET['success']) && $_GET['success'] === 'delete') {
    $success_message = "Publisher deleted successfully!";
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] === 'delete_failed') {
        $error_message = "Cannot delete publisher. This publisher may be used in books.";
    } else {
        $error_message = "Error: " . htmlspecialchars($_GET['error']);
    }
}

$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'id';
$search_value = isset($_GET['search_value']) ? trim($_GET['search_value']) : '';
$is_search_active = !empty($search_value);

$sql = "SELECT * FROM publishers";
if (!empty($search_value)) {
    $search_value = $conn->real_escape_string($search_value);
    if ($search_type == 'id') {
        $sql .= " WHERE publisher_id = '$search_value'";
    } elseif ($search_type == 'name') {
        $sql .= " WHERE publisher_name LIKE '%$search_value%'";
    }
}
$sql .= " ORDER BY publisher_name ASC";
$list_publishers = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Publisher Management</title>
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

        th,
        td {
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

        .address {
            max-width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .button,
        .search-btn {
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

        .button:hover,
        .search-btn:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .no-publishers {
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .search-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-input {
                max-width: 100%;
            }

            .button-container {
                flex-direction: column;
                gap: 10px;
                align-items: flex-start;
            }

            table {
                font-size: 14px;
            }

            .actions {
                flex-direction: column;
                gap: 4px;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="button-container">
            <h2><i class="fas fa-building"></i> Publisher Management</h2>
            <div>
                <a href="add_publisher.php" class="button">
                    <i class="fas fa-plus"></i> Add New Publisher
                </a>
                <a href="../index.php" target="_top" class="return-button">
                    <i class="fas fa-arrow-left"></i> Return
                </a>
            </div>
        </div>

        <form method="GET" class="search-container" style="margin-bottom: 20px;">
            <select name="search_type" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                <option value="id" <?php echo ($search_type == 'id') ? 'selected' : ''; ?>>Search by ID</option>
                <option value="name" <?php echo ($search_type == 'name') ? 'selected' : ''; ?>>Search by Name</option>
            </select>
            <input type="text" name="search_value" placeholder="Enter search keyword..."
                value="<?php echo htmlspecialchars($search_value); ?>" class="search-input">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i> Search
            </button>
            <?php if ($is_search_active): ?>
                <a href="publishers.php" class="button">
                    <i class="fas fa-times"></i> Clear Search
                </a>
            <?php endif; ?>
        </form>

        <?php if (!empty($success_message)): ?>
            <p class="success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <?php if ($list_publishers->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Publisher Name</th>
                        <th>Address</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($publisher = $list_publishers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($publisher['publisher_id']); ?></td>
                            <td><?php echo htmlspecialchars($publisher['publisher_name']); ?></td>
                            <td class="address" title="<?php echo htmlspecialchars($publisher['address']); ?>">
                                <?php echo htmlspecialchars($publisher['address']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($publisher['email']); ?></td>
                            <td><?php echo htmlspecialchars($publisher['phone_number']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($publisher['create_at'])); ?></td>
                            <td class="actions">
                                <a href="edit_publisher.php?id=<?php echo $publisher['publisher_id']; ?>" class="edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <a href="delete_publisher.php?id=<?php echo $publisher['publisher_id']; ?>"
                                    class="delete" onclick="return confirm('Are you sure you want to delete this publisher?')">
                                    <i class="fas fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-publishers">
                <i class="fas fa-info-circle" style="font-size: 48px; color: #6c757d; margin-bottom: 16px;"></i>
                <h3>No publishers found</h3>
                <p><?php echo $is_search_active ? 'No publishers match your search criteria.' : 'No publishers have been added to the system yet.'; ?></p>
                <?php if ($is_search_active): ?>
                    <a href="publishers.php" class="button" style="margin-top: 16px;">
                        <i class="fas fa-arrow-left"></i> View all publishers
                    </a>
                <?php else: ?>
                    <a href="add_publisher.php" class="button" style="margin-top: 16px;">
                        <i class="fas fa-plus"></i> Add first publisher
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>