<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

$error = '';

$order_id = $_GET['id'] ?? 0;
$order_id = $conn->real_escape_string($order_id);
if (!$order_id) {
    header('Location: orders.php');
    exit();
}

$orderQuery = "SELECT o.*, u.username as customer_name 
               FROM orders o
               LEFT JOIN users u ON o.user_id = u.user_id
               WHERE o.order_id = '$order_id'";
$orderResult = $conn->query($orderQuery);

if (!$orderResult || $orderResult->num_rows == 0) {
    header(header: 'Location: orders.php');
    exit();
}

$order_data = $orderResult->fetch_assoc();

$users = $conn->query("SELECT * FROM users WHERE role = 'customer' ORDER BY username");
$users_data = [];
while ($user = $users->fetch_assoc()) {
    $users_data[] = $user;
}

$books_list = $conn->query("SELECT * FROM books ORDER BY title");
$books_data = [];
while ($book = $books_list->fetch_assoc()) {
    $books_data[] = $book;
}

$currentDetails = [];
$detailsQuery = "SELECT od.*, b.title as book_title 
                FROM order_details od
                LEFT JOIN books b ON od.book_id = b.book_id
                WHERE od.order_id = '$order_id'
                ORDER BY od.order_detail_id ASC";
$detailsResult = $conn->query($detailsQuery);
if ($detailsResult) {
    while ($detail = $detailsResult->fetch_assoc()) {
        $currentDetails[] = $detail;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? $order_data['user_id'];
    $status = $_POST['status'] ?? $order_data['status'];
    $books = $_POST['books'] ?? [];
    $quantities = $_POST['quantities'] ?? [];
    $prices = $_POST['prices'] ?? [];
    $book_rows = $_POST['book_rows'] ?? count($currentDetails);

    if (isset($_POST['add_row'])) {
        $book_rows++;
    }

    if (isset($_POST['remove_row'])) {
        $remove_index = (int)$_POST['remove_row'];
        array_splice($books, $remove_index, 1);
        array_splice($quantities, $remove_index, 1);
        array_splice($prices, $remove_index, 1);
        $book_rows--;
    }

    if (isset($_POST['books'])) {
        for ($i = 0; $i < count($books); $i++) {
            $prices[$i] = get_book_price($books[$i], $books_data);
        }
    }

    if (isset($_POST['save_order'])) {
        if (empty($user_id)) {
            $error = 'Please select a customer!';
        } elseif (empty($books[0])) {
            $error = 'Please select at least one book!';
        } else {
            try {
                $conn->begin_transaction();

                $updateQuery = "UPDATE orders SET user_id = '$user_id', status = '$status' WHERE order_id = '$order_id'";
                $updateResult = $conn->query($updateQuery);

                if (!$updateResult) {
                    throw new Exception("Failed to update order: " . $conn->error);
                }

                $deleteResult = $conn->query("DELETE FROM order_details WHERE order_id = '$order_id'");
                if (!$deleteResult) {
                    throw new Exception("Failed to delete old order details: " . $conn->error);
                }

                for ($i = 0; $i < count($books); $i++) {
                    if (empty($books[$i]) || empty($quantities[$i]) || empty($prices[$i])) continue;

                    $book_id = $conn->real_escape_string($books[$i]);
                    $quantity = $conn->real_escape_string($quantities[$i]);
                    $price = $conn->real_escape_string($prices[$i]);

                    $checkResult = $conn->query("SELECT quantity FROM books WHERE book_id = '$book_id'");
                    $bookQuantity = $checkResult->fetch_assoc()['quantity'];

                    if ($bookQuantity < $quantity) {
                        throw new Exception("Book ID $book_id doesn't have enough stock. Available quantity: $bookQuantity");
                    }

                    $insertResult = $conn->query("INSERT INTO order_details (order_id, book_id, quantity, price) VALUES ('$order_id', '$book_id', '$quantity', '$price')");
                    if (!$insertResult) {
                        throw new Exception("Failed to add order detail: " . $conn->error);
                    }
                }

                $conn->commit();
                header('Location: orders.php');
                exit();
            } catch (Exception $e) {
                $conn->rollback();
                $error = 'An error occurred: ' . $e->getMessage();
            }
        }
    }
} else {
    // Lần đầu load form, lấy dữ liệu từ DB
    $user_id = $order_data['user_id'];
    $status = $order_data['status'];
    $books = array_column($currentDetails, 'book_id');
    $quantities = array_column($currentDetails, 'quantity');
    $prices = array_column($currentDetails, 'price');
    $book_rows = count($books) > 0 ? count($books) : 1;
}

// Hàm lấy giá sách theo book_id
function get_book_price($book_id, $books_data)
{
    foreach ($books_data as $book) {
        if ($book['book_id'] == $book_id) return $book['price'];
    }
    return '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Order</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            padding: 20px;
            background-color: #f8f9fa;
        }

        main {
            max-width: 800px;
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        select,
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .book-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }

        .book-row select {
            flex: 2;
        }

        .book-row input[type="number"] {
            flex: 1;
        }

        .book-row .price-input {
            flex: 1;
            background-color: #f8f9fa;
        }

        .button {
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

        .button:hover {
            background: #1d4ed8;
        }

        .button.secondary {
            background: #6b7280;
        }

        .button.secondary:hover {
            background: #4b5563;
        }

        .button.danger {
            background: #ef4444;
        }

        .button.danger:hover {
            background: #dc2626;
        }

        .button.success {
            background: #10b981;
        }

        .button.success:hover {
            background: #059669;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            background: #fee2e2;
            color: #991b1b;
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
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <main>
        <a href="orders.php" class="return-button"><i class="fas fa-arrow-left"></i> Back to Orders</a>

        <h2><i class="fas fa-edit"></i> Edit Order #<?php echo $order_id; ?></h2>

        <?php if ($error): ?>
            <div class="message"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="book_rows" value="<?php echo $book_rows; ?>">
            <div class="form-group">
                <label>Customer</label>
                <select name="user_id" required>
                    <option value="">Select Customer</option>
                    <?php foreach ($users_data as $user): ?>
                        <option value="<?php echo $user['user_id']; ?>" <?php if ($user_id == $user['user_id']) echo 'selected'; ?>><?php echo $user['username']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="đang xử lý" <?php echo ($status == 'đang xử lý') ? 'selected' : ''; ?>>đang xử lý</option>
                    <option value="đã giao" <?php echo ($status == 'đã giao') ? 'selected' : ''; ?>>đã giao</option>
                    <option value="đã huỷ" <?php echo ($status == 'đã huỷ') ? 'selected' : ''; ?>>đã huỷ</option>
                </select>
            </div>

            <div class="form-group">
                <label>Books</label>
                <div id="booksContainer">
                    <?php for ($i = 0; $i < $book_rows; $i++): ?>
                        <div class="book-row">
                            <select name="books[]" required onchange="this.form.submit()">
                                <option value="">Select Book</option>
                                <?php foreach ($books_data as $book): ?>
                                    <option value="<?php echo $book['book_id']; ?>" data-price="<?php echo $book['price']; ?>" <?php if (isset($books[$i]) && $books[$i] == $book['book_id']) echo 'selected'; ?>><?php echo $book['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="quantities[]" placeholder="Quantity" min="1" required value="<?php echo isset($quantities[$i]) ? htmlspecialchars($quantities[$i]) : ''; ?>">
                            <input type="number" name="prices[]" placeholder="Price" readonly class="price-input" value="<?php echo isset($books[$i]) ? get_book_price($books[$i], $books_data) : ''; ?>">
                            <button type="submit" name="remove_row" value="<?php echo $i; ?>" class="button danger"><i class="fas fa-times"></i></button>
                        </div>
                    <?php endfor; ?>
                </div>
                <button type="submit" name="add_row" class="button success"><i class="fas fa-plus"></i> Add Book</button>
            </div>

            <div class="button-container">
                <a href="orders.php" class="button secondary">Cancel</a>
                <button type="submit" name="save_order" class="button">Update Order</button>
            </div>
        </form>
    </main>
</body>

</html>