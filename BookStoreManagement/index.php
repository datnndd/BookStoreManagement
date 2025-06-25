<?php
require 'Verify/auth.php';
require 'Verify/db_connect.php';
$username = $_SESSION["user"]["username"];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang quản lý nhà sách</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f2f5;
            color: #1a1a1a;
        }

        header {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: white;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header h1 i {
            font-size: 32px;
        }

        header a.logout {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        header a.logout:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .container {
            display: flex;
            height: calc(100vh - 80px);
            background-color: #f0f2f5;
        }

        nav {
            width: 280px;
            background-color: #ffffff;
            padding: 30px 20px;
            border-right: 1px solid #e5e7eb;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.05);
        }

        nav h3 {
            margin-bottom: 25px;
            font-size: 20px;
            color: #1e3a8a;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
        }

        nav ul {
            list-style-type: none;
        }

        nav ul li {
            margin-bottom: 12px;
        }

        nav ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #4b5563;
            font-weight: 500;
            padding: 12px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        nav ul li a:hover {
            background-color: #f3f4f6;
            color: #1e3a8a;
            transform: translateX(5px);
        }

        nav ul li a i {
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        main {
            flex: 1;
            padding: 0;
            background-color: #f0f2f5;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
            background-color: #ffffff;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            nav {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e5e7eb;
                padding: 20px;
            }

            iframe {
                height: calc(100vh - 80px - 200px);
            }

            header {
                padding: 15px 20px;
            }

            header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>

<body>

    <header>
        <h1><i class="fas fa-book-open-reader"></i> Nhà Sách Trí Tuệ</h1>

        <div style="display: flex; align-items: center; gap: 15px;">
            <span style="color: white; font-weight: bold;">
                <i class="fas fa-user-circle"></i> Hello <?= htmlspecialchars($username) ?>
            </span>
            <a href="Verify/logout.php" class="logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
        </div>
    </header>

    <div class="container">
        <nav>
            <h3><i class="fas fa-bars"></i> Chức năng</h3>
            <ul>
                <li><a href="BookManager/books.php" target="mainFrame"><i class="fas fa-book"></i> Quản lý sách</a></li>
                <li><a href="AuthorManager/authors.php" target="mainFrame"><i class="fas fa-pen-nib"></i> Quản lý tác giả</a></li>
                <li><a href="Categories/categories.php" target="mainFrame"><i class="fas fa-tags"></i> Quản lý thể loại</a></li>
                <li><a href="Publisher/publishers.php" target="mainFrame"><i class="fas fa-building"></i> Quản lý NXB</a></li>
                <li><a href="Users/users.php" target="mainFrame"><i class="fas fa-users"></i> Quản lý người dùng</a></li>
                <li><a href="Orders/orders.php" target="mainFrame"><i class="fas fa-receipt"></i> Quản lý đơn hàng</a></li>
            </ul>
        </nav>

        <main>
            <iframe name="mainFrame" src="dashboard.php"></iframe>
        </main>
    </div>

</body>

</html>