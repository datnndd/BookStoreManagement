<?php
require 'Verify/auth.php';
require 'Verify/db_connect.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>📊 Dashboard</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f2f5;
            padding: 30px;
            color: #1a1a1a;
        }

        h1 {
            color: #1e3a8a;
            margin-bottom: 30px;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card h3 {
            color: #4b5563;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        th,
        td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #1e3a8a;
            color: white;
            font-weight: 500;
            font-size: 15px;
        }

        td {
            color: #4b5563;
            font-size: 14px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f9fafb;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            .cards {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>

    <h1>Dashboard</h1>

    <div class="cards">
        <?php
        $totalBooks = $conn->query("SELECT COUNT(*) AS total FROM books")->fetch_assoc()['total'];
        $totalAuthors = $conn->query("SELECT COUNT(*) AS total FROM author")->fetch_assoc()['total'];
        $totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
        $totalRevenue = $conn->query("
        SELECT SUM(od.quantity * od.price) AS revenue
        FROM order_details od
        JOIN orders o ON od.order_id = o.order_id
        WHERE o.status = 'đã giao'
    ")->fetch_assoc()['revenue'];
        ?>
        <div class="card">
            <h3>Tổng số sách</h3>
            <p><?php echo $totalBooks; ?></p>
        </div>
        <div class="card">
            <h3>Tổng số tác giả</h3>
            <p><?php echo $totalAuthors; ?></p>
        </div>
        <div class="card">
            <h3>Tổng số đơn hàng</h3>
            <p><?php echo $totalOrders; ?></p>
        </div>
        <div class="card">
            <h3>Tổng doanh thu</h3>
            <p><?php echo number_format($totalRevenue); ?> VNĐ</p>
        </div>
    </div>

    <h2>📦 Đơn hàng gần nhất</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Khách hàng</th>
            <th>Ngày đặt</th>
            <th>Trạng thái</th>
            <th>Sách</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Tổng tiền</th>
        </tr>
        <?php
        $sql = "
        SELECT o.order_id, u.full_name, o.order_date, o.status, b.title, od.quantity, od.price
        FROM orders o
        JOIN users u ON o.user_id = u.user_id
        JOIN order_details od ON o.order_id = od.order_id
        JOIN books b ON b.book_id = od.book_id
        ORDER BY o.order_date DESC
        LIMIT 10;
    ";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $tong = $row['quantity'] * $row['price'];
            echo "<tr>
            <td>{$row['order_id']}</td>
            <td>{$row['full_name']}</td>
            <td>{$row['order_date']}</td>
            <td>{$row['status']}</td>
            <td>{$row['title']}</td>
            <td>{$row['quantity']}</td>
            <td>" . number_format($row['price']) . " VNĐ</td>
            <td>" . number_format($tong) . " VNĐ</td>
        </tr>";
        }
        ?>
    </table>

</body>

</html>