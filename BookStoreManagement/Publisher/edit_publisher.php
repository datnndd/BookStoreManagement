<?php
require '../Verify/auth.php';
require '../Verify/db_connect.php';

$error = '';
$success = '';
$publisher_id = '';
$publisher_name = '';
$address = '';
$email = '';
$phone_number = '';

if (isset($_GET['id'])) {
    $publisher_id = $_GET['id'];
    $sql = "SELECT * FROM publishers WHERE publisher_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $publisher_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $publisher_name = $row['publisher_name'];
        $address = $row['address'];
        $email = $row['email'];
        $phone_number = $row['phone_number'];
    } else {
        $error = "Publisher not found.";
    }
    $stmt->close();
} else {
    $error = "No publisher ID provided.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $publisher_id = $_POST['publisher_id'] ?? '';
    $publisher_name = trim($_POST['publisher_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');

    if (empty($publisher_name) || empty($address) || empty($phone_number) || empty($email)) {
        $error = 'Please fill in all required fields!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format!';
    } elseif (!preg_match('/^\d+$/', $phone_number)) {
        $error = 'Phone number must be numeric!';
    } else {
        $check_sql = "SELECT COUNT(*) as count FROM publishers WHERE LOWER(publisher_name) = LOWER(?) AND publisher_id != ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("si", $publisher_name, $publisher_id);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            $error = 'Publisher name already exists!';
        } else {
            $sql = "UPDATE publishers SET publisher_name = ?, address = ?, email = ?, phone_number = ? WHERE publisher_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $publisher_name, $address, $email, $phone_number, $publisher_id);

            if ($stmt->execute()) {
                $success = "Publisher updated successfully!";
            } else {
                $error = "Failed to update publisher. Please try again.";
            }
            $stmt->close();
        }
        $check_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Publisher</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: #f0f2f5;
            padding: 40px;
            color: #1a1a1a;
        }

        main {
            background: #fff;
            padding: 40px;
            max-width: 800px;
            margin: auto;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #1e3a8a;
            margin-bottom: 30px;
            font-size: 28px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
            padding: 12px 24px;
            background: #1e3a8a;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .button:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            text-align: center;
            margin-bottom: 20px;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #fecaca;
            font-weight: 500;
        }

        .success {
            background: #dcfce7;
            color: #166534;
            text-align: center;
            margin-bottom: 20px;
            padding: 16px;
            border-radius: 8px;
            border: 1px solid #bbf7d0;
            font-weight: 500;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        label {
            font-weight: 500;
            display: block;
            margin: 20px 0 8px;
            color: #4b5563;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 12px 16px;
            margin-top: 4px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #1a1a1a;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus {
            outline: none;
            border-color: #1e3a8a;
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px 24px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
        }

        .submit-btn:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .submit-btn:disabled {
            background: #9ca3af;
            cursor: not-allowed;
            transform: none;
        }

        .required {
            color: #ef4444;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: #3498db;
            font-size: 14px;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px;
            }

            main {
                padding: 20px;
            }

            form {
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <main>
        <h2><i class="fas fa-edit"></i> Edit Publisher</h2>

        <?php if (!empty($error)): ?>
            <div class="error">
                <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success">
                <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($error) || !empty($success)): ?>
            <form method="POST" action="">
                <input type="hidden" name="publisher_id" value="<?php echo htmlspecialchars($publisher_id); ?>">

                <label for="publisher_name">
                    Publisher Name <span class="required">*</span>
                </label>
                <input type="text" id="publisher_name" name="publisher_name"
                    value="<?php echo htmlspecialchars($publisher_name); ?>"
                    required placeholder="Enter publisher name">

                <label for="address">
                    Address <span class="required">*</span>
                </label>
                <textarea id="address" name="address" required
                    placeholder="Enter publisher address"><?php echo htmlspecialchars($address); ?></textarea>

                <label for="email">
                    Email <span class="required">*</span>
                </label>
                <input type="email" id="email" name="email"
                    value="<?php echo htmlspecialchars($email); ?>"
                    required placeholder="Enter publisher email">

                <label for="phone_number">
                    Phone Number <span class="required">*</span>
                </label>
                <input type="text" id="phone_number" name="phone_number"
                    value="<?php echo htmlspecialchars($phone_number); ?>"
                    required placeholder="Enter phone number">

                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i> Update Publisher
                </button>
            </form>
        <?php endif; ?>

        <a href="publishers.php" class="button">
            <i class="fas fa-arrow-left"></i> Back to list
        </a>
    </main>
</body>

</html>