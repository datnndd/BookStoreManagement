<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

$search_type = isset($_GET['search_type']) ? $_GET['search_type'] : 'id';
$search_value = isset($_GET['search_value']) ? trim($_GET['search_value']) : '';

if (!empty($search_value)) {
    if ($search_type == 'id') {
        $sql = "SELECT o.*, u.username AS customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE o.order_id = '$search_value'";
    } elseif ($search_type == 'customer') {
        $sql = "SELECT o.*, u.username AS customer_name 
                FROM orders o 
                LEFT JOIN users u ON o.user_id = u.user_id 
                WHERE u.username LIKE '%$search_value%'";
    }
} else {
    $sql = "SELECT o.*, u.username AS customer_name 
            FROM orders o 
            LEFT JOIN users u ON o.user_id = u.user_id 
            ORDER BY o.order_date DESC";
}

$list_orders = $conn->query($sql);
$order_count = $list_orders->num_rows;

$view_order_id = $_GET['view'] ?? 0;
$view_order = null;
$view_details = null;
if ($view_order_id) {
    $orderResult = $conn->query("SELECT o.*, u.username as customer_name 
                                 FROM orders o
                                 LEFT JOIN users u ON o.user_id = u.user_id
                                 WHERE o.order_id = '$view_order_id'");
    if ($orderResult && $orderResult->num_rows > 0) {
        $view_order = $orderResult->fetch_assoc();
        $view_details = $conn->query("SELECT od.*, b.title as book_title 
                                     FROM order_details od
                                     LEFT JOIN books b ON od.book_id = b.book_id
                                     WHERE od.order_id = '$view_order_id'
                                     ORDER BY od.order_detail_id ASC");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Order Management</title>
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
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-pending {
            background-color: #fef3c7;
            color: #92400e;
        }

        .badge-processing {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .badge-completed {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-cancelled {
            background-color: #fee2e2;
            color: #991b1b;
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

        .actions a.view {
            background-color: #3b82f6;
        }

        .actions a.edit {
            background-color: #10b981;
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

        .button,
        .search-btn {
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

        .button:hover,
        .search-btn:hover {
            background: #1d4ed8;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .no-orders {
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

        .order-count {
            background: #1e3a8a;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 14px;
            margin-left: 10px;
        }

        .order-detail-section {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .order-info {
            background: #f0f4f8;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .order-info h3 {
            margin-top: 0;
            color: #1e3a8a;
        }

        .order-info p {
            margin: 5px 0;
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
            <h2><i class="fas fa-shopping-cart"></i> Order Management <span class="order-count"><?php echo $order_count; ?></span></h2>
            <div>
                <a href="add_order.php" class="button"><i class="fas fa-plus"></i> Add New Order</a>
                <a href="../index.php" target="_top" class="return-button"><i class="fas fa-arrow-left"></i> Return</a>
            </div>
        </div>

        <div class="search-container">
            <a href="?search_type=id" class="search-btn <?php echo $search_type == 'id' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Search by ID</a>
            <a href="?search_type=customer" class="search-btn <?php echo $search_type == 'customer' ? 'active' : ''; ?>"><i class="fas fa-search"></i> Search by Customer</a>
        </div>

        <div class="search-container">
            <form method="GET" style="display: flex; gap: 10px; width: 100%;">
                <input type="text" name="search_value" value="<?php echo htmlspecialchars($search_value); ?>" class="search-input" placeholder="<?php echo $search_type == 'id' ? 'Enter Order ID' : 'Enter Customer Name'; ?>">
                <input type="hidden" name="search_type" value="<?php echo $search_type; ?>">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i> Search</button>
            </form>
        </div>

        <?php if ($order_count == 0): ?>
            <div class="no-orders">No orders found.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $list_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['customer_name'] ?? 'N/A'); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row['order_date'])); ?></td>
                            <td>
                                <?php
                                $status = $row['status'];
                                $status_class = '';
                                $status_text = '';

                                switch ($status) {
                                    case 'đang xử lý':
                                    default:
                                        $status_class = 'badge-processing';
                                        $status_text = 'đang xử lý';
                                        break;
                                    case 'đã giao':
                                        $status_class = 'badge-completed';
                                        $status_text = 'đã giao';
                                        break;
                                    case 'đã huỷ':
                                        $status_class = 'badge-cancelled';
                                        $status_text = 'đã huỷ';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?>">
                                    <?php echo $status_text; ?>
                                </span>
                            </td>
                            <td class="actions">
                                <a class="view" href="?view=<?php echo $row['order_id']; ?>"><i class="fas fa-eye"></i> View</a>
                                <a class="edit" href="edit_order.php?id=<?php echo $row['order_id']; ?>"><i class="fas fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <?php if ($view_order && $view_details): ?>
            <div class="order-detail-section">
                <h3><i class="fas fa-info-circle"></i> Order Details #<?php echo $view_order_id; ?></h3>

                <div class="order-info">
                    <h3>Order Information</h3>
                    <p><strong>Order ID:</strong> <?php echo $view_order_id; ?></p>
                    <p><strong>Status:</strong>
                        <?php
                        $status = $view_order['status'];
                        $status_class = '';
                        $status_text = '';

                        switch ($status) {
                            case 'đang xử lý':
                            default:
                                $status_class = 'badge-processing';
                                $status_text = 'đang xử lý';
                                break;
                            case 'đã giao':
                                $status_class = 'badge-completed';
                                $status_text = 'đã giao';
                                break;
                            case 'đã huỷ':
                                $status_class = 'badge-cancelled';
                                $status_text = 'đã huỷ';
                                break;
                        }
                        ?>
                        <span class="badge <?php echo $status_class; ?>">
                            <?php echo $status_text; ?>
                        </span>
                    </p>
                    <p><strong>Order Date:</strong> <?php echo date('d/m/Y H:i', strtotime($view_order['order_date'])); ?></p>
                </div>

                <h3>Order Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Book</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        while ($detail = $view_details->fetch_assoc()):
                            $subtotal = $detail['quantity'] * $detail['price'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($detail['book_title']); ?></td>
                                <td><?php echo htmlspecialchars($detail['quantity']); ?></td>
                                <td><?php echo number_format($detail['price'], 2); ?></td>
                                <td><?php echo number_format($subtotal, 2); ?></td>
                            </tr>
                        <?php endwhile; ?>
                        <tr style="background-color: #f0f4f8; font-weight: bold;">
                            <td colspan="3" style="text-align: right;">Total:</td>
                            <td><?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tbody>
                </table>

                <div style="text-align: right; margin-top: 20px;">
                    <a href="orders.php" class="button">Back to Orders</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>

</html>